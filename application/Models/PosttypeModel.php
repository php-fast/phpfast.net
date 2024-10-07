<?php
namespace App\Models;

use System\Core\BaseModel;

class PosttypeModel extends BaseModel
{
    protected $table = 'fast_posttype';

    protected $fillable = ['name', 'slug', 'fields'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Định nghĩa cấu trúc bảng của Post Types
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
                'null' => false,
            ],
            'name' => [
                'type' => 'varchar(100)',
                'null' => false,
            ],
            'slug' => [
                'type' => 'varchar(100)',
                'key' => 'unique',
                'null' => false,
            ],
            'fields' => [
                'type' => 'json',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ];
    }

    // Lấy tất cả Post Types
    public function getAllPostTypes()
    {
        return $this->list($this->table);
    }

    // Tạo mới Post Type và tạo bảng nội dung tương ứng
    public function createPostType($data)
    {
        // Lưu Post Type vào bảng posttype
        $inserted = $this->add($this->table, $data);

        if ($inserted) {
            // Lấy danh sách ngôn ngữ hiện tại
            $languages = $this->list('fast_languages', 'status = ?', ['active']);
            $slug = $data['slug'];
            $fields = json_decode($data['fields'], true);

            foreach ($languages as $lang) {
                $tableName = "posts_{$slug}_{$lang['code']}";
                $this->createPostTypeTable($tableName, $fields);
            }

            return true;
        }

        return false;
    }

    // Tạo bảng cho Post Type
    protected function createPostTypeTable($tableName, $fields = [])
    {
        // Các field mặc định của một bài viết trong Post Type
        $defaultFields = [
            'id' => 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'title' => 'VARCHAR(255) NOT NULL',
            'slug' => 'VARCHAR(255) NOT NULL',
            'content' => 'TEXT',
            'author_id' => 'INT UNSIGNED NOT NULL',
            'status' => "ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft'",
            'created_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ];

        // Chuyển fields tùy chỉnh thành định dạng SQL
        $customFieldsSql = [];
        foreach ($fields as $field => $type) {
            $customFieldsSql[] = "`$field` $type";
        }

        // Kết hợp các fields mặc định và tùy chỉnh
        $fieldsSql = array_merge($defaultFields, $customFieldsSql);

        // Xây dựng câu lệnh tạo bảng
        $createTableQuery = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(", ", $fieldsSql) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        // Thực thi truy vấn tạo bảng
        $this->query($createTableQuery);
    }

    // Cập nhật Post Type
    public function updatePostType($id, $data)
    {
        return $this->set($this->table, $data, 'id = ?', [$id]);
    }

    // Xóa Post Type
    public function deletePostType($id)
    {
        $postType = $this->row($this->table, 'id = ?', [$id]);
        if ($postType) {
            // Lấy danh sách ngôn ngữ hiện tại
            $languages = $this->list('fast_languages', 'status = ?', ['active']);
            $slug = $postType['slug'];

            foreach ($languages as $lang) {
                $tableName = "posts_{$slug}_{$lang['code']}";
                $this->db->dropTable($tableName);
            }

            return $this->del($this->table, 'id = ?', [$id]);
        }

        return false;
    }

    protected function dropPostTypeTable($tableName)
    {
        $dropTableQuery = "DROP TABLE IF EXISTS `$tableName`;";
        $this->query($dropTableQuery);
    }
}
