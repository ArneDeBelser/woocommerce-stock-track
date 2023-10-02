<?php

namespace ADB\WooCommerceStockTrack;

class StockLogger
{
    public $database;

    private $oldStockQty = 0;

    public function __construct($database)
    {
        $this->database = $database;

        add_action('woocommerce_variation_before_set_stock', [$this, 'setOldStock'], 10, 1);
        add_action('woocommerce_variation_set_stock', [$this, 'logStockChange'], 10, 1);
    }

    public function setOldStock($product): void
    {
        $this->oldStockQty = $product->get_stock_quantity();
    }

    public function logStockChange($product): void
    {
        $backtrace                  = print_r(debug_backtrace(), true);
        $current_user               = wp_get_current_user();
        $post_id                    = $product->get_id();
        $parent_id                  = $product->get_parent_id();
        $product_type               = $product->is_type('variation') ? 'variable' : 'simple';

        $is_stock_manager           = str_contains($backtrace, 'stock_manager_save_one_product_stock_data');
        $is_product_update          = str_contains($backtrace, 'wp_ajax_woocommerce_save_variations');
        $is_order_stock_update      = str_contains($backtrace, 'woocommerce_order_status_processing');

        $stringProduct              = print_r($product, true);
        $trimmed_product            = preg_replace('/\s*\R\s*/', ' ', trim($stringProduct));

        if ($is_stock_manager) {
            $matches = '';
            preg_match_all('/stock_quantity[\s\S]*?\[/', $trimmed_product, $matches);
            $old_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][1]);
            $new_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][2]);

            $this->database->insert(
                $current_user->get('nickname'),
                'Stock Manager',
                $post_id,
                $old_stock_quantity,
                $new_stock_quantity,
                $product_type,
                $parent_id,
                null
            );

            return;
        }

        if ($is_product_update) {
            $matches = '';
            preg_match_all('/stock_quantity[\s\S]*?\[/', $trimmed_product, $matches);
            $old_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][1]);
            $new_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][2]);

            $this->database->insert(
                $current_user->get('nickname'),
                'Product Interface',
                $post_id,
                $old_stock_quantity,
                $new_stock_quantity,
                $product_type,
                $parent_id,
                null
            );

            return;
        }

        if ($is_order_stock_update) {
            $new_stock_quantity = $product->get_stock_quantity();

            $this->database->insert(
                'Order',
                'Order',
                $post_id,
                $this->oldStockQty,
                $new_stock_quantity,
                $product_type,
                $parent_id,
                null,
                // self::order_id,
            );

            return;
        }
    }
}
