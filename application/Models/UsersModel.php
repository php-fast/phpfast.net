<?php
namespace App\Models;
use System\Core\BaseModel;

class UsersModel extends BaseModel {

    protected $table = 'fast_users';

    // Các cột được phép thêm hoặc sửa
    protected $fillable = ['username', 'email', 'password', 'fullname', 'avatar', 'role', 'permissions', 'optional', 'status'];

    // Các cột không được phép sửa
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Định nghĩa cấu trúc bảng với schema builder
     * 
     * @return array Cấu trúc bảngP
     */
    public function _schema() {
        return [
            'id' => [
                'type' => 'int unsigned',
                'auto_increment' => true,
                'key' => 'primary',
                'null' => false
            ],
            'username' => [
                'type' => 'varchar(40)',
                'key' => 'unique',
                'null' => false
            ],
            'email' => [
                'type' => 'varchar(150)',
                'key' => 'unique',
                'null' => false
            ],
            'password' => [
                'type' => 'varchar(255)',
                'null' => false
            ],
            'fullname' => [
                'type' => 'varchar(150)',
                'null' => true,
                'default' => ''
            ],
            'avatar' => [
                'type' => 'varchar(255)',
                'null' => true,
                'default' => ''
            ],
            'phone' => [
                'type' => 'varchar(10)',
                'null' => false,
                'default' => ''
            ],
            'role' => [
                'type' => 'enum(\'admin\', \'moderator\', \'author\', \'member\')',
                'null' => false
            ],
            'permissions' => [
                'type' => 'json',
                'null' => true
            ],
            'optional' => [
                'type' => 'json',
                'null' => true
            ],
            'telegram' => [
                'type' => 'varchar(100)',
                'null' => true,
                'default' => ''
            ],
            'whatsapp' => [
                'type' => 'varchar(10)',
                'null' => true,
                'default' => ''
            ],
            'skype' => [
                'type' => 'varchar(100)',
                'null' => true,
                'default' => ''
            ],
            'status' => [
                'type' => 'enum(\'active\', \'inactive\', \'banned\')',
                'null' => true,
                'default' => 'active'
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP'
            ]
        ];
    }

    /**
     * Lấy tất cả người dùng
     * 
     * @param string|null $where Điều kiện truy vấn (tùy chọn)
     * @param array $params Mảng giá trị tương ứng với chuỗi điều kiện
     * @param string|null $orderBy Sắp xếp theo cột (tùy chọn)
     * @param int|null $limit Giới hạn số lượng kết quả (tùy chọn)
     * @param int|null $offset Bắt đầu từ bản ghi thứ mấy (tùy chọn)
     * @return array Danh sách người dùng
     */
    public function getUsers($where = '', $params = [], $orderBy = 'id DESC', $limit = null, $offset = null) {
        return $this->list($this->table, $where, $params, $orderBy, $limit, $offset);
    }

    /**
     * Lấy danh sách người dùng với phân trang
     * 
     * @param int $page Trang hiện tại
     * @param int $limit Số lượng kết quả trên mỗi trang
     * @return array Danh sách người dùng và thông tin phân trang
     */
    public function getUsersPaging($limit = 10, $page = 1) {
        return $this->listpaging($this->table, 'age > ?', [18], 'age DESC', $limit, ($page - 1) * $limit);
    }

    /**
     * Lấy thông tin người dùng theo ID
     * 
     * @param int $id ID người dùng
     * @return array|false Thông tin người dùng hoặc false nếu không tìm thấy
     */
    public function getUserById($id)
    {
        return $this->row($this->table, 'id = ?', [$id]);
    }

    public function getUserByUsername($username)
    {
        return $this->row($this->table, 'username = ?', [$username]);
    }
    public function getUserByEmail($email)
    {
        return $this->row($this->table, 'email = ?', [$email]);
    }

    /**
     * Thêm người dùng mới
     * 
     * @param array $data Dữ liệu người dùng cần thêm
     * @return bool Thành công hoặc thất bại
     */
    public function addUser($data) {
        $data = $this->fill($data); // Lọc dữ liệu được phép thêm
        return $this->add($this->table, $data);
    }

    /**
     * Cập nhật thông tin người dùng
     * 
     * @param int $id ID người dùng cần cập nhật
     * @param array $data Dữ liệu cần cập nhật
     * @return int Số dòng bị ảnh hưởng
     */
    public function updateUser($id, $data) {
        $data = $this->fill($data); // Lọc dữ liệu được phép sửa
        return $this->set($this->table, $data, 'id = ?', [$id]);
    }

    /**
     * Xóa người dùng
     * 
     * @param int $id ID người dùng cần xóa
     * @return int Số dòng bị ảnh hưởng
     */
    public function deleteUser($id) {
        return $this->del($this->table, 'id = ?', [$id]);
    }

}
