<?php
namespace App\Models;

use System\Core\BaseModel;

class PosttypeModel extends BaseModel
{
    protected $table = 'fast_posttype';

    protected $fillable = ['name', 'slug', 'fields', 'status', 'languages'];

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
                'null' => false
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
            'languages' => [
                'type' => 'json',
                'null' => true,
            ],
            'fields' => [
                'type' => 'json',
                'null' => true,
            ],
            'status' => [
                'type' => "enum('active', 'inactive')",
                'null' => false,
                'default' => 'active',
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

    // get posttype by id
    public function getPostTypeByID($id) {
        return $this->row($this->table, 'id = ?', [$id]);
    }

    // Tạo mới Post Type và tạo bảng nội dung tương ứng
    public function createPostType($data)
    {
        // Lưu Post Type vào bảng posttype
        return $this->add($this->table, $data);
        
    }

    // Tạo bảng cho Post Type
    public function createPostTypeTable($tableName, $fields)
    {   
        
        // Các field mặc định của một bài viết trong Post Type
        $defaultFields = [
            'id' => 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'title' => 'VARCHAR(255) NOT NULL',
            'slug' => 'VARCHAR(255) NOT NULL',
            'status' => "ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft'",
            'created_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ];
        // Kiểm tra và chuyển đổi $fields thành mảng nếu nó không phải là mảng hoặc đối tượng
        $customFieldsSql = [];
        foreach ($fields as $field) {
            // Ánh xạ kiểu dữ liệu từ 'type' sang SQL
            switch ($field['type']) {
                case 'Text':
                case 'Email':
                case 'Password':
                case 'URL':
                    $type = 'VARCHAR(255)';
                    break;
                case 'Number':
                    $type = 'INT';
                    break;
                case 'Date':
                    $type = 'DATE';
                    break;
                case 'DateTime':
                    $type = 'DATETIME';
                    break;
                case 'Textarea':
                case 'WYSIWYG Editor':
                    $type = 'TEXT';
                    break;
                case 'Checkbox':
                    $type = 'TINYINT(1)';
                    break;
                case 'Radio':
                case 'Select':
                    $type = 'VARCHAR(255)';
                    break;
                case 'File':
                case 'Image':
                    $type = 'VARCHAR(255)';
                    break;
                case 'Images Gallery':
                    $type = 'TEXT'; // Lưu danh sách các đường dẫn ảnh dạng JSON
                    break;
                case 'Reference':
                    $type = 'INT UNSIGNED';
                    break;
                case 'Repeater':
                    $type = 'TEXT'; // Lưu dữ liệu động dạng JSON
                    break;
                default:
                    $type = 'TEXT'; // Mặc định nếu không biết kiểu dữ liệu
                    break;
            }

            // Tạo câu lệnh SQL cho từng cột dựa trên dữ liệu field
            $columnDefinition = "`{$field['field_name']}` $type";

            // Nếu field yêu cầu bắt buộc
            if (!empty($field['required'])) {
                $columnDefinition .= " NOT NULL";
            }

            // Nếu có giá trị mặc định
            if (!empty($field['default_value'])) {
                $defaultValue = is_numeric($field['default_value']) ? $field['default_value'] : "'{$field['default_value']}'";
                $columnDefinition .= " DEFAULT $defaultValue";
            }

            // Thêm định nghĩa cột vào mảng các field tùy chỉnh
            $customFieldsSql[] = $columnDefinition;
        }

        // Chuyển đổi các fields mặc định thành định dạng SQL
        $defaultFieldsSql = [];
        foreach ($defaultFields as $field => $type) {
            $defaultFieldsSql[] = "`$field` $type";
        }

        // Kết hợp các fields mặc định và tùy chỉnh
        $fieldsSql = array_merge($defaultFieldsSql, $customFieldsSql);

        // Xây dựng câu lệnh tạo bảng
        $createTableQuery = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(", ", $fieldsSql) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        // Thực thi truy vấn tạo bảng
        $creatTable = $this->query($createTableQuery);
        if($creatTable){
            return true;
        } 
        return false;
    }


    // Cập nhật Post Type trong bang posttype
    public function updatePostType($id, $data)
    {
        $data = $this->fill($data);
        return $this->set($this->table, $data, 'id = ?', [$id]);
    }

    // Xóa Post Type
    public function deletePostType($id)
    {
        return $this->del($this->table, 'id = ?', [$id]);
        return false;
    }

    public function dropPostTypeTable($tableName)
    {
        $dropTableQuery = "DROP TABLE IF EXISTS `$tableName`;";
        $this->query($dropTableQuery);
    }

    public function duplicateTable($new_table, $orginal_table)
    {
        $sql = "CREATE TABLE $new_table LIKE $orginal_table";
        $insert = "INSERT INTO $new_table SELECT * FROM $orginal_table";
        $this->query($sql);
        $this->query($insert);

    }
    public function changeTableName($oldName, $newName)
    {
        $sql = "RENAME TABLE $oldName TO $newName";
        $this->query($sql);
    }

    public function removeColumn($table, $column)
    {
        $sql = "ALTER TABLE $table DROP COLUMN $column";
        $this->query($sql);
    }
    public function addColumn($table, $column, $type)
    {   
        switch ($type) {
            case 'Text':
            case 'Email':
            case 'Password':
            case 'URL':
                $type = 'VARCHAR(255)';
                break;
            case 'Number':
                $type = 'INT';
                break;
            case 'Date':
                $type = 'DATE';
                break;
            case 'DateTime':
                $type = 'DATETIME';
                break;
            case 'Textarea':
            case 'WYSIWYG Editor':
                $type = 'TEXT';
                break;
            case 'Checkbox':
                $type = 'TINYINT(1)';
                break;
            case 'Radio':
            case 'Select':
                $type = 'VARCHAR(255)';
                break;
            case 'File':
            case 'Image':
                $type = 'VARCHAR(255)';
                break;
            case 'Images Gallery':
                $type = 'TEXT'; // Lưu danh sách các đường dẫn ảnh dạng JSON
                break;
            case 'Reference':
                $type = 'INT UNSIGNED';
                break;
            case 'Repeater':
                $type = 'TEXT'; // Lưu dữ liệu động dạng JSON
                break;
            default:
                $type = 'TEXT'; // Mặc định nếu không biết kiểu dữ liệu
                break;
        }
        $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $type";
        $this->query($sql);
    }
    public function updateColumn($table, $column, $type)
    {
        switch ($type) {
            case 'Text':
            case 'Email':
            case 'Password':
            case 'URL':
                $type = 'VARCHAR(255)';
                break;
            case 'Number':
                $type = 'INT';
                break;
            case 'Date':
                $type = 'DATE';
                break;
            case 'DateTime':
                $type = 'DATETIME';
                break;
            case 'Textarea':
            case 'WYSIWYG Editor':
                $type = 'TEXT';
                break;
            case 'Checkbox':
                $type = 'TINYINT(1)';
                break;
            case 'Radio':
            case 'Select':
                $type = 'VARCHAR(255)';
                break;
            case 'File':
            case 'Image':
                $type = 'VARCHAR(255)';
                break;
            case 'Images Gallery':
                $type = 'TEXT'; // Lưu danh sách các đường dẫn ảnh dạng JSON
                break;
            case 'Reference':
                $type = 'INT UNSIGNED';
                break;
            case 'Repeater':
                $type = 'TEXT'; // Lưu dữ liệu động dạng JSON
                break;
            default:
                $type = 'TEXT'; // Mặc định nếu không biết kiểu dữ liệu
                break;
        }
        $sql = "ALTER TABLE $table MODIFY COLUMN $column $type";
        $this->query($sql);
    }
}
