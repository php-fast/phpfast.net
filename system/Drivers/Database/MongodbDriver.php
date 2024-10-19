<?php
namespace System\Drivers\Database;
use MongoDB\Client;
use MongoDB\Driver\Exception\Exception as MongoDBException;
use System\Core\AppException;

class MongodbDriver extends Database {

    protected $client;
    protected $db;
    protected $lastInsertedId;

    /**
     * Khởi tạo kết nối MongoDB
     * 
     * @param array $config Mảng chứa thông tin cấu hình kết nối
     */
    public function __construct($config) {
        try {
            $uri = $config['mongo_uri'];
            $options = $config['mongo_options'] ?? [];
            $driverOptions = $config['mongo_driver_options'] ?? [];
            $this->client = new Client($uri, $options, $driverOptions);
            $this->db = $this->client->selectDatabase($config['mongo_database']);
        } catch (MongoDBException $e) {
            throw new \System\Core\AppException("Connect MongodbDriver failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn tùy ý
     * 
     * @param array $command Mảng lệnh MongoDB cần thực thi
     * @return mixed Kết quả của truy vấn
     */
    public function query($command, $params = []) {
        try {
            $result = $this->db->command($command)->toArray();
            return $result;
        } catch (MongoDBException $e) {
            throw new \System\Core\AppException("MongodbDriver->query(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Lấy ID của bản ghi vừa chèn
     * 
     * @return mixed ID của bản ghi vừa chèn
     */
    public function lastInsertId() {
        return $this->lastInsertedId ?? null;
    }

    /**
     * Đếm số bản ghi trong collection
     * 
     * @param string $collection Tên collection cần đếm số bản ghi
     * @param string|array $where Điều kiện WHERE để đếm số bản ghi (tùy chọn)
     * @param array $params Mảng giá trị tương ứng với các tham số trong điều kiện WHERE (tùy chọn)
     * @return int Số bản ghi trong collection
     */
    public function count($collection, $where = '', $params = []) {
        try {
            $filter = $this->buildFilter($where, $params);
            return $this->db->$collection->countDocuments($filter);
        } catch (MongoDBException $e) {
            throw new \System\Core\AppException("MongodbDriver->count(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn SELECT lấy nhiều dòng
     * 
     * @param string $collection Tên collection cần truy vấn
     * @param string|array $where Điều kiện WHERE (tùy chọn)
     * @param array $params Mảng giá trị tương ứng với các tham số trong điều kiện WHERE (tùy chọn)
     * @param string $orderBy Câu lệnh ORDER BY (tùy chọn)
     * @param int $page Vị trí trang hiện tại (tùy chọn)
     * @param int $limit Số lượng kết quả cần giới hạn (tùy chọn)
     * @return array Mảng chứa kết quả truy vấn
     */
    public function fetchAll($collection, $where = '', $params = [], $orderBy = '', $page = 1, $limit = null) {
        try {
            $filter = $this->buildFilter($where, $params);
            $options = [];
            if ($orderBy) {
                $options['sort'] = $this->buildSort($orderBy);
            }
            if (!is_null($limit)) {
                $page = max((int)$page, 1);
                $limit = (int)$limit;
                $skip = ($page - 1) * $limit;
                $options['limit'] = $limit;
                $options['skip'] = $skip;
            }
            $cursor = $this->db->$collection->find($filter, $options);
            return iterator_to_array($cursor);
        } catch (MongoDBException $e) {
            throw new \System\Core\AppException("MongodbDriver->fetchAll(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn SELECT lấy nhiều dòng với phân trang
     * 
     * @param string $collection Tên collection
     * @param string|array $where Điều kiện WHERE (tùy chọn)
     * @param array $params Mảng giá trị tương ứng với điều kiện WHERE (tùy chọn)
     * @param string $orderBy Câu lệnh ORDER BY (tùy chọn)
     * @param int $page Vị trí trang hiện tại (tùy chọn)
     * @param int $limit Số lượng kết quả trả về cho mỗi trang (tùy chọn)
     * @return array Kết quả truy vấn và thông tin có trang tiếp theo hay không
     */
    public function fetchPagination($collection, $where = '', $params = [], $orderBy = '', $page = 1, $limit = null) {
        try {
            $hasNextPage = false;
            $page = max((int)$page, 1);
            $limit = (int)$limit ?: 10;
            $skip = ($page - 1) * $limit;

            $filter = $this->buildFilter($where, $params);
            $options = [
                'limit' => $limit + 1,
                'skip' => $skip,
            ];
            if ($orderBy) {
                $options['sort'] = $this->buildSort($orderBy);
            }

            $cursor = $this->db->$collection->find($filter, $options);
            $results = iterator_to_array($cursor);

            if (count($results) > $limit) {
                $hasNextPage = true;
                array_pop($results);
            }

            return [
                'data' => $results,
                'is_next' => $hasNextPage,
                'page' => $page
            ];
        } catch (MongoDBException $e) {
            throw new \System\Core\AppException("MongodbDriver->fetchPagination(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn SELECT lấy 1 dòng
     * 
     * @param string $collection Tên collection cần truy vấn
     * @param string|array $where Điều kiện WHERE
     * @param array $params Mảng giá trị tương ứng với các tham số trong điều kiện WHERE
     * @param string $orderBy Câu lệnh ORDER BY (tùy chọn)
     * @param int $page Vị trí trang hiện tại (tùy chọn)
     * @return array|null Mảng chứa kết quả truy vấn hoặc null nếu không có kết quả
     */
    public function fetchRow($collection, $where = '', $params = [], $orderBy = '', $page = 1) {
        try {
            $filter = $this->buildFilter($where, $params);
            $options = [];
            if ($orderBy) {
                $options['sort'] = $this->buildSort($orderBy);
            }
            $skip = ($page - 1);
            if ($skip > 0) {
                $options['skip'] = $skip;
            }
            $options['limit'] = 1;

            $cursor = $this->db->$collection->find($filter, $options);
            $result = iterator_to_array($cursor);

            return $result[0] ?? null;
        } catch (MongoDBException $e) {
            throw new \System\Core\AppException("MongodbDriver->fetchRow(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn INSERT
     * 
     * @param string $collection Tên collection cần chèn dữ liệu
     * @param array $data Mảng dữ liệu cần chèn
     * @return bool Trả về true nếu chèn dữ liệu thành công, ngược lại là false
     */
    public function insert($collection, $data) {
        try {
            $result = $this->db->$collection->insertOne($data);
            if ($result->isAcknowledged()) {
                $this->lastInsertedId = $result->getInsertedId();
                return true;
            }
            return false;
        } catch (MongoDBException $e) {
            throw new \System\Core\AppException("MongodbDriver->insert(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn UPDATE
     * 
     * @param string $collection Tên collection cần cập nhật
     * @param array $data Mảng dữ liệu cần cập nhật
     * @param string|array $where Điều kiện WHERE để cập nhật dữ liệu
     * @param array $params Mảng giá trị tương ứng với các tham số trong điều kiện WHERE
     * @return bool Trả về true nếu cập nhật thành công, ngược lại là false
     */
    public function update($collection, $data, $where = '', $params = []) {
        try {
            $filter = $this->buildFilter($where, $params);
            $update = ['$set' => $data];
            $result = $this->db->$collection->updateMany($filter, $update);
            return $result->isAcknowledged();
        } catch (MongoDBException $e) {
            throw new \System\Core\AppException("MongodbDriver->update(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Thực thi truy vấn DELETE
     * 
     * @param string $collection Tên collection cần xóa dữ liệu
     * @param string|array $where Điều kiện WHERE để xóa dữ liệu
     * @param array $params Mảng giá trị tương ứng với các tham số trong điều kiện WHERE
     * @return bool Trả về true nếu xóa thành công, ngược lại là false
     */
    public function delete($collection, $where = '', $params = []) {
        try {
            $filter = $this->buildFilter($where, $params);
            $result = $this->db->$collection->deleteMany($filter);
            return $result->isAcknowledged();
        } catch (MongoDBException $e) {
            throw new \System\Core\AppException("MongodbDriver->delete(): " . $e->getMessage(), 500);
        }
    }

    /**
     * Xây dựng bộ lọc từ chuỗi $where và mảng $params
     * 
     * @param string|array $where Chuỗi điều kiện hoặc mảng điều kiện
     * @param array $params Tham số cho điều kiện
     * @return array Bộ lọc cho MongoDB
     */
    private function buildFilter($where, $params) {
        if (is_array($where)) {
            // Nếu $where là mảng, giả sử nó đã là bộ lọc MongoDB hợp lệ
            return $where;
        } elseif (is_string($where) && !empty($where)) {
            // Nếu $where là chuỗi, chúng ta cần phân tích và chuyển đổi thành bộ lọc MongoDB
            // Đây là một nhiệm vụ phức tạp, chúng ta sẽ xây dựng một parser đơn giản

            $filter = [];
            $tokens = $this->tokenizeWhereClause($where);
            $filter = $this->parseTokens($tokens, $params);

            return $filter;
        } else {
            // Nếu $where rỗng hoặc không xác định, trả về bộ lọc rỗng
            return [];
        }
    }

    /**
     * Tokenize the WHERE clause into an array of tokens
     * 
     * @param string $where Chuỗi điều kiện WHERE
     * @return array Mảng token
     */
    private function tokenizeWhereClause($where) {
        // Định nghĩa các pattern cho các token
        $pattern = '/\s*(AND|OR|\(|\)|!=|<=|>=|<>|=|<|>|\bIN\b|\bNOT IN\b|\bLIKE\b|\bBETWEEN\b|\?|\w+|\S)\s*/i';
        preg_match_all($pattern, $where, $matches);
        return $matches[0];
    }

    /**
     * Parse tokens into MongoDB filter
     * 
     * @param array $tokens Mảng token từ WHERE clause
     * @param array $params Mảng tham số
     * @return array Bộ lọc MongoDB
     */
    private function parseTokens($tokens, $params) {
        $filter = [];
        $operators = [];
        $operands = [];

        $paramIndex = 0;

        $stack = [];

        while (count($tokens) > 0) {
            $token = array_shift($tokens);
            $tokenUpper = strtoupper($token);

            if (in_array($tokenUpper, ['AND', 'OR'])) {
                // Logical operators
                $operators[] = $tokenUpper;
            } elseif ($token === '(') {
                // Handle sub-expressions
                $subTokens = [];
                $depth = 1;
                while ($depth > 0 && count($tokens) > 0) {
                    $subToken = array_shift($tokens);
                    if ($subToken === '(') {
                        $depth++;
                    } elseif ($subToken === ')') {
                        $depth--;
                    }
                    if ($depth > 0) {
                        $subTokens[] = $subToken;
                    }
                }
                $subFilter = $this->parseTokens($subTokens, $params);
                $operands[] = $subFilter;
            } elseif (preg_match('/^\w+$/', $token)) {
                // Field name
                $field = $token;
                // Get the operator
                $operatorToken = array_shift($tokens);
                $operatorUpper = strtoupper($operatorToken);

                if (in_array($operatorUpper, ['=', '!=', '<>', '>', '<', '>=', '<=', 'IN', 'NOT IN', 'LIKE', 'BETWEEN'])) {
                    // Comparison operators
                    $value = null;
                    if ($operatorUpper === 'BETWEEN') {
                        // BETWEEN needs two parameters
                        $value1 = $params[$paramIndex++] ?? null;
                        $andToken = array_shift($tokens); // Should be 'AND'
                        $value2 = $params[$paramIndex++] ?? null;
                        if ($value1 !== null && $value2 !== null) {
                            $value = ['$gte' => $value1, '$lte' => $value2];
                        }
                    } elseif ($operatorUpper === 'IN' || $operatorUpper === 'NOT IN') {
                        // IN and NOT IN expect a list of parameters (array)
                        $paramValue = $params[$paramIndex++] ?? null;
                        if (is_array($paramValue)) {
                            if ($operatorUpper === 'IN') {
                                $value = ['$in' => $paramValue];
                            } else {
                                $value = ['$nin' => $paramValue];
                            }
                        }
                    } elseif ($operatorUpper === 'LIKE') {
                        // LIKE operator
                        $paramValue = $params[$paramIndex++] ?? null;
                        if ($paramValue !== null) {
                            // Convert SQL LIKE pattern to MongoDB regex
                            $regexPattern = str_replace(['%', '_'], ['.*', '.'], $paramValue);
                            $value = new \MongoDB\BSON\Regex('^' . $regexPattern . '$', 'i');
                        }
                    } else {
                        // Other comparison operators
                        $paramValue = $params[$paramIndex++] ?? null;
                        if ($paramValue !== null) {
                            switch ($operatorUpper) {
                                case '=':
                                    $value = $paramValue;
                                    break;
                                case '!=':
                                case '<>':
                                    $value = ['$ne' => $paramValue];
                                    break;
                                case '>':
                                    $value = ['$gt' => $paramValue];
                                    break;
                                case '<':
                                    $value = ['$lt' => $paramValue];
                                    break;
                                case '>=':
                                    $value = ['$gte' => $paramValue];
                                    break;
                                case '<=':
                                    $value = ['$lte' => $paramValue];
                                    break;
                            }
                        }
                    }
                    if ($value !== null) {
                        $operands[] = [$field => $value];
                    }
                } else {
                    // Invalid operator, skip
                    continue;
                }
            }
        }

        // Build the final filter using the operands and operators
        if (empty($operators)) {
            // Only one condition
            return $operands[0] ?? [];
        } else {
            // Multiple conditions
            $filter = [];
            $currentOperator = null;
            $currentOperands = [];

            for ($i = 0; $i < count($operands); $i++) {
                $currentOperands[] = $operands[$i];
                if (isset($operators[$i])) {
                    $operator = $operators[$i];
                    if ($currentOperator === null) {
                        $currentOperator = $operator;
                    } elseif ($currentOperator !== $operator) {
                        // Different operator, need to nest
                        $filter = $this->combineOperands($filter, $currentOperands, $currentOperator);
                        $currentOperator = $operator;
                        $currentOperands = [];
                    }
                }
            }

            // Combine any remaining operands
            if (!empty($currentOperands)) {
                $filter = $this->combineOperands($filter, $currentOperands, $currentOperator);
            }

            return $filter;
        }
    }

    /**
     * Combine operands using the specified logical operator
     * 
     * @param array $filter Bộ lọc hiện tại
     * @param array $operands Mảng các điều kiện
     * @param string $operator Toán tử logic ('AND' hoặc 'OR')
     * @return array Bộ lọc đã được kết hợp
     */
    private function combineOperands($filter, $operands, $operator) {
        if ($operator === 'AND') {
            if (!isset($filter['$and'])) {
                $filter['$and'] = [];
            }
            $filter['$and'] = array_merge($filter['$and'], $operands);
        } elseif ($operator === 'OR') {
            if (!isset($filter['$or'])) {
                $filter['$or'] = [];
            }
            $filter['$or'] = array_merge($filter['$or'], $operands);
        }
        return $filter;
    }

    /**
     * Xây dựng mảng sắp xếp từ chuỗi $orderBy
     * 
     * @param string $orderBy Chuỗi ORDER BY
     * @return array Mảng sắp xếp cho MongoDB
     */
    private function buildSort($orderBy) {
        $sort = [];
        $fields = explode(',', $orderBy);
        foreach ($fields as $field) {
            $field = trim($field);
            if (stripos($field, 'DESC') !== false) {
                $fieldName = trim(str_ireplace('DESC', '', $field));
                $sort[$fieldName] = -1;
            } else if (stripos($field, 'ASC') !== false) {
                $fieldName = trim(str_ireplace('ASC', '', $field));
                $sort[$fieldName] = 1;
            } else {
                $sort[$field] = 1;
            }
        }
        return $sort;
    }
}
