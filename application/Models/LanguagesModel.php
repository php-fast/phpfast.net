<?php
namespace App\Models;

use System\Core\BaseModel;

class LanguagesModel extends BaseModel
{
    protected $table = 'fast_languages';

    // Các cột được phép thêm hoặc sửa
    protected $fillable = [ 'name', 'code', 'is_default', 'status'];

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
}
