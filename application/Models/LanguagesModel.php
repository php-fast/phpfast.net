<?php
namespace App\Models;

use System\Core\BaseModel;

class LanguagesModel extends BaseModel
{
    protected $table = 'fast_languages';

    // Các cột được phép thêm hoặc sửa
    protected $fillable = [ 'name', 'code', 'is_default', 'status', 'is_default'];

    // Các cột không được phép sửa
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Định nghĩa cấu trúc bảng với schema builder
     * 
     * @return array Cấu trúc bảng
     */
    public function _schema()
    {
        return [
            'id' => [
                'type' => 'int unsigned',
                'auto_increment' => true,
                'key' => 'primary',
                'null' => false
            ],
            'code' => [
                'type' => 'varchar(2)',
                'key' => 'unique',
                'null' => false
            ],
            'name' => [
                'type' => 'varchar(100)',
                'null' => false
            ],
            'is_default' => [
                'type' => 'tinyint(1)',
                'null' => false,
                'default' => 0
            ],
            'status' => [
                'type' => 'enum(\'active\', \'inactive\')',
                'null' => false,
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
     * Lấy danh sách ngôn ngữ
     */
    public function getLanguages()
    {
        return $this->list($this->table);
    }
    /**
     * Lấy danh sách ngôn ngữ active (not inactive)
     */
    public function getActiveLanguages()
    {
        return $this->list($this->table, 'status = ?', ['active']);
    }
    
    /**
     * Lấy ngon ngu mac dinh
     */
    public function getDefaultLanguage()
    {
        return $this->row($this->table, 'is_default = ?', [1]);
    }
    
    /**
     * Lấy ngôn ngữ theo ID
     */
    public function getLanguageById($id)
    {
        return $this->row($this->table, 'id = ?', [$id]);
    }

    /**
     * Lấy ngôn ngữ theo Code
     */
    public function getLanguageByCode($code)
    {
        return $this->row($this->table, 'code = ?', [$code]);
    }

    /**
     * Đặt tất cả ngôn ngữ về không phải là mặc định
     */
    public function unsetDefaultLanguage()
    {
        return $this->set($this->table, ['is_default' => 0], 'is_default = 1');
    }


    /**
     * Thêm ngôn ngữ
     */
    public function addLanguage($data) {
        try {
            // Xử lý dữ liệu đầu vào trước khi thêm vào database
            $data = $this->fill($data); 
            
            // Thực hiện thêm dữ liệu vào bảng, bắt lỗi nếu xảy ra
            $result = $this->add($this->table, $data);
            return [
                'success' => true,
                'id' => $result
            ];
        } catch (\PDOException $e) {
            // Trả về thông báo lỗi cho người dùng
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Sửa ngôn ngữ
     */
    public function setLanguage($id, $data) {
        try {
            // Xử lý dữ liệu đầu vào trước khi thêm vào database
            $data = $this->fill($data); 
            
            // Thực hiện sửa dữ liệu vào bảng, bắt lỗi nếu xảy ra
            $result = $this->set($this->table, $data, 'id = ?', [$id]);
            return [
                'success' => true,
                'id' => $result
            ];
        } catch (\PDOException $e) {
            // Trả về thông báo lỗi cho người dùng
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
