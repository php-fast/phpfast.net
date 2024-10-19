<?php
namespace System\Drivers\Database;
use PDO;
use PDOException;
use System\Core\AppException;

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
        } catch (PDOException $e) {
            throw new \System\Core\AppException("Connect PostgresqlDriver failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn SQL tùy ý
     * 
     * @param string $query Câu lệnh SQL cần thực thi
     * @param array $params Mảng giá trị tương ứng với các tham số trong câu lệnh SQL
     * @return mixed Kết quả của truy vấn (sử dụng cho SELECT, INSERT, UPDATE, DELETE)
     */
    public function query($query, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            if (preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\s/i', $query)) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->query(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Lấy ID của bản ghi vừa chèn
     * 
     * @return string ID của bản ghi vừa chèn
     */
    public function lastInsertId() {
        try {
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->lastInsertId(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Đếm số bản ghi trong bảng
     * 
     * @param string $table Tên bảng cần đếm số bản ghi
     * @param string $where Điều kiện WHERE để đếm số bản ghi (tùy chọn)
     * @param array $params Mảng giá trị tương ứng với các tham số trong chuỗi WHERE (tùy chọn)
     * @return int Số bản ghi trong bảng
     */
    public function count($table, $where = '', $params = []) {
        $table = '"' . str_replace('"', '""', $table) . '"';
        $query = "SELECT COUNT(*) as count FROM {$table}";
        if ($where) {
            $query .= " WHERE {$where}";
        }
        try {
            $result = $this->query($query, $params);
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->count(): " . $e->getMessage(), 500);
        }
        if (!empty($result)){
            return $result[0]['count'];
        }
        return 0;
    }

    /**
     * Chuẩn bị một câu truy vấn SQL (PDO::prepare)
     * 
     * @param string $query Câu truy vấn SQL
     * @return PDOStatement Đối tượng PDOStatement
     */
    public function prepare($query) {
        try {
            return $this->pdo->prepare($query);
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->prepare(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi câu truy vấn với các tham số được truyền vào (PDO::execute)
     * 
     * @param PDOStatement $stmt Đối tượng PDOStatement đã được chuẩn bị
     * @param array $params Các tham số truyền vào truy vấn
     * @return bool Kết quả thực thi
     */
    public function execute($stmt, $params = []) {
        try {
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->execute(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn SELECT lấy nhiều dòng
     * 
     * @param string $table Tên bảng cần truy vấn
     * @param string $where Điều kiện WHERE dưới dạng chuỗi (tùy chọn)
     * @param array $params Mảng giá trị tương ứng với các tham số trong chuỗi WHERE (tùy chọn)
     * @param string $orderBy Câu lệnh ORDER BY (tùy chọn)
     * @param int $page Vị trí trang hiện tại (tùy chọn)
     * @param int $limit Số lượng kết quả cần giới hạn (tùy chọn)
     * @return array Mảng chứa kết quả truy vấn
     */
    public function fetchAll($table, $where = '', $params = [], $orderBy = '', $page = 1, $limit = null) {
        $table = '"' . str_replace('"', '""', $table) . '"';
        $sql = "SELECT * FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if (!is_null($limit)) {
            $page = max((int)$page, 1);
            $limit = (int)$limit;
            $offset = ($page - 1) * $limit;
            $sql .= " LIMIT {$limit}";
            if ($offset > 0) {
                $sql .= " OFFSET {$offset}";
            }
        }
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->fetchAll(): " . $e->getMessage(), 500);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thực thi truy vấn SELECT lấy nhiều dòng với phân trang
     * 
     * @param string $table Tên bảng
     * @param string $where Điều kiện WHERE dưới dạng chuỗi (tùy chọn)
     * @param array $params Mảng giá trị tương ứng với chuỗi WHERE (tùy chọn)
     * @param string $orderBy Câu lệnh ORDER BY (tùy chọn)
     * @param int $page Vị trí trang hiện tại (tùy chọn)
     * @param int $limit Số lượng kết quả trả về cho mỗi trang (tùy chọn)
     * @return array Kết quả truy vấn và thông tin có trang tiếp theo hay không
     */
    public function fetchPagination($table, $where = '', $params = [], $orderBy = '', $page = 1, $limit = null) {
        try {
            $table = '"' . str_replace('"', '""', $table) . '"';
            $hasNextPage = false;
            $page = max((int)$page, 1);
            $limit = (int)$limit ?: 10;
            $offset = ($page - 1) * $limit;
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

            if (count($results) > $limit) {
                $hasNextPage = true;
                array_pop($results);
            }
            return [
                'data' => $results,
                'is_next' => $hasNextPage,
                'page' => $page
            ];
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->fetchPagination(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn SELECT lấy 1 dòng
     * 
     * @param string $table Tên bảng cần truy vấn
     * @param string $where Điều kiện WHERE dưới dạng chuỗi
     * @param array $params Mảng giá trị tương ứng với các tham số trong chuỗi WHERE
     * @param string $orderBy Câu lệnh ORDER BY (tùy chọn)
     * @param int $page Vị trí trang hiện tại (tùy chọn)
     * @return array|null Mảng chứa kết quả truy vấn hoặc null nếu không có kết quả
     */
    public function fetchRow($table, $where = '', $params = [], $orderBy = '', $page = 1) {
        $table = '"' . str_replace('"', '""', $table) . '"';
        $sql = "SELECT * FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        // Tính toán OFFSET dựa trên $page (mặc định là 1)
        $page = max((int)$page, 1);
        $offset = ($page - 1);

        // Luôn lấy 1 dòng
        $sql .= " LIMIT 1";
        if ($offset > 0) {
            $sql .= " OFFSET {$offset}";
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->fetchRow(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn INSERT
     * 
     * @param string $table Tên bảng cần chèn dữ liệu
     * @param array $data Mảng dữ liệu cần chèn (dưới dạng 'cột' => 'giá trị')
     * @return bool Trả về true nếu chèn dữ liệu thành công, ngược lại là false
     */
    public function insert($table, $data) {
        $table = '"' . str_replace('"', '""', $table) . '"';
        $columns = array_keys($data);
        $columns_escaped = array_map(function($col) {
            return '"' . str_replace('"', '""', $col) . '"';
        }, $columns);
        $keys = implode(',', $columns_escaped);
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$table} ({$keys}) VALUES ({$placeholders})";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(array_values($data));
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->insert(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn UPDATE
     * 
     * @param string $table Tên bảng cần cập nhật
     * @param array $data Mảng dữ liệu cần cập nhật (dưới dạng 'cột' => 'giá trị')
     * @param string $where Điều kiện WHERE để cập nhật dữ liệu
     * @param array $params Mảng giá trị tương ứng với các tham số trong chuỗi WHERE
     * @return bool Trả về true nếu cập nhật thành công, ngược lại là false
     */
    public function update($table, $data, $where = '', $params = []) {
        try {
            $table = '"' . str_replace('"', '""', $table) . '"';
            $columns = array_keys($data);
            $set = implode(', ', array_map(function($col) {
                return '"' . str_replace('"', '""', $col) . '" = ?';
            }, $columns));
            $sql = "UPDATE {$table} SET {$set}";
            if ($where) {
                $sql .= " WHERE {$where}";
            }
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(array_merge(array_values($data), $params));
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->update(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn DELETE
     * 
     * @param string $table Tên bảng cần xóa dữ liệu
     * @param string $where Điều kiện WHERE để xóa dữ liệu
     * @param array $params Mảng giá trị tương ứng với các tham số trong chuỗi WHERE
     * @return bool Trả về true nếu xóa thành công, ngược lại là false
     */
    public function delete($table, $where = '', $params = []) {
        try {
            $table = '"' . str_replace('"', '""', $table) . '"';
            $sql = "DELETE FROM {$table}";
            if ($where) {
                $sql .= " WHERE {$where}";
            }
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new \System\Core\AppException("PostgresqlDriver->delete(): " . $e->getMessage(), 500);
        }
    }
}
