<?php
namespace App\Models;
use System\Core\BaseModel;

class TermModel extends BaseModel {

    protected $table = 'fast_terms';

    // Columns that are fillable (can be added or modified)
    protected $fillable = ['name', 'slug', 'description', 'type', 'posttype', 'parent_id', 'language_id', 'main_language'];

    // Columns that are guarded (cannot be modified)
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Define the table schema
     * 
     * @return array Table schema
     */
    public function _schema() {
        return [
            'id' => [
                'type' => 'int unsigned',
                'auto_increment' => true,
                'key' => 'primary',
                'null' => false
            ],
            'name' => [
                'type' => 'varchar(150)',
                'null' => false,
                'default' => ''
            ],
            'slug' => [
                'type' => 'varchar(150)',
                'null' => false,
                'key' => 'unique',
            ],
            'description' => [
                'type' => 'text',
                'null' => true
            ],
            'posttype' => [
                'type' => 'varchar(50)',
                'null' => true
            ],
            'type' => [
                'type' => 'varchar(50)',
                'null' => false,
                'default' => 'category'
            ],
            'parent_id' => [
                'type' => 'int unsigned',
                'null' => true
            ],
            'language_id' => [
                'type' => 'int unsigned',
                'null' => true
            ],
            'main_language' => [
                'type' => 'int unsigned',
                'null' => false,
                'default' => 0
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true
            ]
        ];
    }

    /**
     * Get all taxonomies
     */
    public function getTaxonomies($where = '', $params = [], $orderBy = 'id DESC', $limit = null, $offset = null) {
        return $this->list($this->table, $where, $params, $orderBy, $limit, $offset);
    }

    /**
     * Get a single term by ID
     */
    public function getTermById($id) {
        return $this->row($this->table, 'id = ?', [$id]);
    }
    /**
     * Get a single term by slug
     */

    public function getTermBySlug($slug) {
        return $this->row($this->table, 'slug = ?', [$slug]);
    }
    /**
     * Get taxonomies by type (e.g., category or tag)
     */
    public function getTaxonomiesByType($type, $orderBy = 'id DESC') {
        return $this->list($this->table, 'type = ?', [$type], $orderBy);
    }
    /**
     * Get taxonomies by type end posttype
     */
    public function getTaxonomiesByTypeAndPostType($type, $posttype) {
        return $this->list($this->table, 'type = ? AND posttype = ?', [$type, $posttype]);
    }
    /**
     * Add a new term
     */
    public function addTerm($data) {
        $data = $this->fill($data);
        return $this->add($this->table, $data);
    }

    /**
     * Update an existing term
     */
    public function setTerm($id, $data) {
        $data = $this->fill($data);
        return $this->set($this->table, $data, 'id = ?', [$id]);
    }

    /**
     * Delete a term
     */
    public function delTerm($id) {
        return $this->del($this->table, 'id = ?', [$id]);
    }

    /**
     * Get posts by term without using JOIN
     */
    public function getPostsByTerm($termId, $postTable = 'posts', $termRelationshipTable = 'post_term_relationships') {
        // Step 1: Get all post IDs related to the term
        $relationships = $this->list($termRelationshipTable, 'term_id = ?', [$termId]);
        $postIds = array_column($relationships, 'post_id');

        if (empty($postIds)) {
            return [];
        }

        // Step 2: Get posts by IDs
        $placeholders = implode(',', array_fill(0, count($postIds), '?'));
        return $this->list($postTable, "id IN ({$placeholders})", $postIds);
    }
}