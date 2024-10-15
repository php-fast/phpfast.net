<?php
namespace System\Drivers\Database;
use PDO;
use PDOException;
use System\Core\AppException;
use System\Libraries\Logger;

class PostgresqlDriver extends Database {

    protected $pdo;

    /**
     * Khởi tạo kết nối PostgreSQL
     * 
     * @param array $config Mảng chứa thông tin cấu hình kết nối
     */
    public function __construct($config) {
        try {
            $dsn = 'pgsql:host=' . $config['db_host'] . ';port=' . $config['db_port'] . ';dbname=' . $config['db_database'];
            $this->pdo = new PDO($dsn, $config['db_username'], $config['db_password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (AppException $e) {
            $e->handle();
        } catch (PDOException $e) {
            Logger::error('Connect Postgresql failed: ' . $e->getMessage(), $e->getFile(), $e->getLine());
            http_response_code(500);
            echo 'Connect Postgresql failed: ' . $e->getMessage() . ' - '. $e->getFile() . ' at Line: '. $e->getLine();
        }
        //  catch (PDOException $e) {
        //     throw new \Exception('Kết nối PostgreSQL thất bại: ' . $e->getMessage());
        // }
    }

    // Thực thi truy vấn SQL tùy ý
    public function query($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        }catch (AppException $e) {
            $e->handle();
        } catch (PDOException $e) {
            Logger::error('Connect MysqlDriver failed: ' . $e->getMessage(), $e->getFile(), $e->getLine());
            http_response_code(500);
            echo '->query: ' . $e->getMessage(), $e->getFile(), $e->getLine();
        }
    }

    // Lấy ID của bản ghi vừa chèn
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    // Đếm số bản ghi trong bảng
    public function count($table, $where = '', $params = []) {
        $query = "SELECT COUNT(*) as count FROM {$table}";
        if ($where) {
            $query .= " WHERE {$where}";
        }
        $stmt = $this->query($query, $params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
    
    /**
     * Thực thi truy vấn SELECT lấy nhiều dòng
     */
    public function fetchAll($table, $where = '', $params = [], $orderBy = '', $page=1, $limit = null) {
        $sql = "SELECT * FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if (!is_null($limit)) {
            $offset = ($page-1)*$limit;
            $sql .= " LIMIT {$limit}";
            if (!is_null($offset) && $offset > 0) {
                $sql .= " OFFSET {$offset}";
            }
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thực thi truy vấn SELECT lấy nhiều dòng với phân trang, hỗ trợ tính toán offset từ số trang
     * Example: $users = $db->fetchPagination('users', 'age > ? AND status = ?', [30, 'active'], 'age DESC', 10, 20);
     * 
     * @param string $table Tên bảng
     * @param string $where Điều kiện WHERE dưới dạng chuỗi (tùy chọn)
     * @param array $params Mảng giá trị tương ứng với chuỗi WHERE (tùy chọn)
     * @param string $orderBy Câu lệnh ORDER BY (tùy chọn)
     * @param int $page So trang hien tai cua phan trang
     * @param int $limit Số lượng kết quả trả về cho mỗi trang (tùy chọn)
     * @return array Kết quả truy vấn và thông tin có trang tiếp theo hay không
     */
    public function fetchPagination($table, $where = '', $params = [], $orderBy = '', $page = 1, $limit = null) {
        $hasNextPage = false;
        $page = $page ?? 1;  // Mặc định là trang 1 nếu không truyền
        $limit = $limit ?? 10;  // Mặc định là 10 bản ghi nếu không truyền
        $offset = ($page-1)*$limit;

        // Lấy dư ra 1 bản ghi để kiểm tra có trang tiếp theo
        $limitExtra = $limit + 1;

        $sql = "SELECT * FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        $sql .= " LIMIT {$limitExtra} OFFSET {$offset}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Kiểm tra xem có trang tiếp theo hay không
        if (count($results) > $limit) {
            $hasNextPage = true;
            // Loại bỏ bản ghi dư ra
            array_pop($results);
        }
        return [
            'data' => $results,
            'is_next' => $hasNextPage,
            'page' => $page
        ];
    }

    /**
     * Thực thi truy vấn SELECT lấy 1 dòng
     */
    public function fetchRow($table, $where = '', $params = []) {
        $sql = "SELECT * FROM {$table} WHERE {$where} LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thực thi truy vấn INSERT
     */
    public function insert($table, $data) {
        $keys = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$table} ({$keys}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    /**
     * Thực thi truy vấn UPDATE
     */
    public function update($table, $data, $where = '', $params = []) {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$table} SET {$set}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_merge(array_values($data), $params));
    }

    /**
     * Thực thi truy vấn DELETE
     */
    public function delete($table, $where = '', $params = []) {
        $sql = "DELETE FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}