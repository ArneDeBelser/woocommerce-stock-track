<?php

namespace ADB\WooCommerceStockTrack;

use ADB\WooCommerceStockTrack\DatabaseInterface;

class Database implements DatabaseInterface
{
    private $wpdb;

    private $table_name;

    public function __construct($wpdb)
    {
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'woocommerce_stock_track';

        add_action('admin_init', [$this, 'activate']);
    }

    public function activate(): void
    {
        if (!function_exists('maybe_create_table')) {
            require_once ABSPATH . 'wp-admin/install-helper.php';
        }

        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE $this->table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          created timestamp NOT NULL default CURRENT_TIMESTAMP,
          user varchar(255) DEFAULT '' NOT NULL,
          source_from varchar(255) DEFAULT '' NOT NULL,
          old_stock_value INT NOT NULL,
          new_stock_value INT NOT NULL,
          type_product varchar(255) DEFAULT '' NOT NULL,
          post_id INT NOT NULL,
          parent_id INT NOT NULL,
          order_id INT NULL,
          PRIMARY KEY id (id),
          KEY post_id (post_id),
          KEY parent_id (parent_id)
        ) $charset_collate;";

        maybe_create_table($this->table_name, $sql);
    }

    public function insert(
        $user,
        $source_from,
        $post_id,
        $old_stock_value,
        $new_stock_value,
        $product_type,
        $parent_id,
        $order_id
    ): void {
        $this->wpdb->insert($this->table_name, [
            'created' => date("Y-m-d H:i:s"),
            'user' => $user,
            'source_from' => $source_from,
            'old_stock_value' => $old_stock_value,
            'new_stock_value' => $new_stock_value,
            'type_product' => $product_type,
            'post_id' => $post_id,
            'parent_id' => $parent_id,
            'order_id' => $order_id,
        ]);
    }
}
