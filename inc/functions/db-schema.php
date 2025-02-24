<?php
namespace NetpeakTools;
class SchemaHandler {
    private $wpdb;
    private $table_name;

    /**
     * Constructor.
     *create_schema_table
     * @since 1.0.5
     *
     * @access public
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $this->wpdb->prefix . 'netpeak_schema_ld_json';

        $this->createTable();
    }

    private function createTable() {
        if ($this->wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") != $this->table_name) {
            $charset_collate = $this->wpdb->get_charset_collate();
            $sql = "CREATE TABLE {$this->table_name} (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                schema_category varchar(255) NOT NULL,
                schema_type varchar(255) NOT NULL,
                schema_data text NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public function saveSchema($category, $data) {
        $schema_data = maybe_serialize($data);

        $existing = $this->wpdb->get_var($this->wpdb->prepare(
            "SELECT id FROM {$this->table_name} WHERE schema_category = %s",
            $category
        ));

        if ($existing) {
            $this->wpdb->update(
                $this->table_name,
                ['schema_type' => $data['schema_type'], 'schema_data' => $schema_data],
                ['id' => $existing]
            );
        } else {
            $this->wpdb->insert(
                $this->table_name,
                ['schema_category' => $category, 'schema_type' => $data['schema_type'], 'schema_data' => $schema_data]
            );
        }
    }

    public function getSchema($category, $type = '') {
        $row = $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE schema_category = %s AND schema_type = %s",
            $category,
            $type
        ), ARRAY_A);

        return $row ? maybe_unserialize($row['schema_data']) : [];
    }

}
