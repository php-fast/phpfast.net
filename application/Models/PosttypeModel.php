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
        $defaultFields = [
            'id' => 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'title' => 'VARCHAR(255) NOT NULL',
            'slug' => 'VARCHAR(255) NOT NULL',
            'status' => "ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft'",
            'created_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ];
    
        $customFieldsSql = [];
        
        foreach ($fields as $field) {
            $fieldSql = $this->generateSqlFromType($field['type'], $field);
            $customFieldsSql[] = $fieldSql;
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
        $createTable = $this->query($createTableQuery);
        if ($createTable) {
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
        $type = $this->conver_type($type);
        $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $type";
        $this->query($sql);
    }
    public function updateColumn($table, $column, $type)
    {
        $type = $this->conver_type($type);
        $sql = "ALTER TABLE $table MODIFY COLUMN $column $type";
        $this->query($sql);
    }
    public function showStructTable($table) {
        $sql = "DESCRIBE $table;";
        try {
            $stmt = $this->query($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
    private function conver_type($type) {
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
                $type = 'TEXT';
                break;
            case 'Reference':
                $type = 'INT UNSIGNED';
                break;
            case 'Repeater':
                $type = 'TEXT'; 
                break;
            default:
                $type = 'TEXT';
                break;
        }
        return $type;
    }

    private function sqlVarchar($field) {
        return sprintf(
            "`%s` VARCHAR(255) %s%s",
            $field['field_name'] ?? 'undefined_column', 
            !empty($field['required']) ? 'NOT NULL' : 'NULL', 
            isset($field['default_value']) ? " DEFAULT '" . $field['default_value'] . "'" : ''
        );
    }
    
    private function sqlInt($field) {
        return sprintf(
            "`%s` INT %s%s",
            $field['field_name'] ?? 'undefined_column', 
            !empty($field['required']) ? 'NOT NULL' : 'NULL', 
            isset($field['default_value']) ? " DEFAULT " . (int)$field['default_value'] : ''
        );
    }
    
    private function sqlDate($field) {
        return sprintf(
            "`%s` DATE %s%s",
            $field['field_name'] ?? 'undefined_column', 
            !empty($field['required']) ? 'NOT NULL' : 'NULL', 
            isset($field['default_value']) ? " DEFAULT '" . $field['default_value'] . "'" : ''
        );
    }
    
    private function sqlDateTime($field) {
        return sprintf(
            "`%s` DATETIME %s%s",
            $field['field_name'] ?? 'undefined_column', 
            !empty($field['required']) ? 'NOT NULL' : 'NULL', 
            isset($field['default_value']) ? " DEFAULT '" . $field['default_value'] . "'" : ''
        );
    }
    
    private function sqlText($field) {
        return sprintf(
            "`%s` TEXT %s",
            $field['field_name'] ?? 'undefined_column', 
            !empty($field['required']) ? 'NOT NULL' : 'NULL'
        );
    }
    
    private function sqlTinyInt($field) {
        return sprintf(
            "`%s` TINYINT(1) %s%s",
            $field['field_name'] ?? 'undefined_column', 
            !empty($field['required']) ? 'NOT NULL' : 'NULL', 
            isset($field['default_value']) ? " DEFAULT " . (int)$field['default_value'] : ''
        );
    }
    
    private function sqlReference($field) {
        return sprintf(
            "`%s` INT UNSIGNED %s%s",
            $field['field_name'] ?? 'undefined_column', 
            !empty($field['required']) ? 'NOT NULL' : 'NULL', 
            isset($field['default_value']) ? " DEFAULT " . (int)$field['default_value'] : ''
        );
    }
    
    private function sqlDefault($field) {
        return sprintf(
            "`%s` TEXT %s",
            $field['field_name'] ?? 'undefined_column', 
            !empty($field['required']) ? 'NOT NULL' : 'NULL'
        );
    }
    
    private function generateSqlFromType($type, $field) {
        switch ($type) {
            case 'Text':
            case 'Email':
            case 'Password':
            case 'URL':
                return $this->sqlVarchar($field);
            case 'Number':
                return $this->sqlInt($field);
            case 'Date':
                return $this->sqlDate($field);
            case 'DateTime':
                return $this->sqlDateTime($field);
            case 'Textarea':
            case 'WYSIWYG Editor':
                return $this->sqlText($field);
            case 'Checkbox':
                return $this->sqlTinyInt($field);
            case 'Radio':
            case 'Select':
            case 'File':
            case 'Image':
                return $this->sqlVarchar($field);
            case 'Images Gallery':
                return $this->sqlText($field); 
            case 'Reference':
                return $this->sqlReference($field);
            case 'Repeater':
                return $this->sqlText($field);
            default:
                return $this->sqlDefault($field);
        }
    }

}