<?php

namespace ADB\WooCommerceStockTrack;

use ADB\WooCommerceStockTrack\ActionDeterminer;
use ADB\WooCommerceStockTrack\DatabaseInterface;
use ADB\WooCommerceStockTrack\Loggers\Factories\LoggerFactoryInterface;
use ADB\WooCommerceStockTrack\Traits\HasLogger;

class StockLogger
{
    use HasLogger;

    public function __construct(
        public DatabaseInterface $database,
        private LoggerFactoryInterface $loggerFactory,
        private ActionDeterminer $actionDeterminer
    ) {
        add_action('woocommerce_product_set_stock', [$this, 'handleStockChange'], 10, 1);
        add_action('woocommerce_variation_set_stock', [$this, 'handleStockChange'], 10, 1);
    }

    public function handleStockChange($product)
    {
        $user           = wp_get_current_user();
        $postId         = $product->get_id();
        $parentID       = $product->get_parent_id();
        $productType    = $product->is_type('variation') ? 'variable' : 'simple';

        $action = $this->actionDeterminer->determine();

        if ($action === null) {
            return;
        }

        $stockLogger = $this->loggerFactory->create($action);

        if ($stockLogger) {
            $stockLogger->log($product, $user, $productType, $parentID, $postId);
        }
    }

    // public function logStockChange($product)
    // {
    //     $backtrace                  = print_r(debug_backtrace(), true);
    //     $current_user               = wp_get_current_user();
    //     $post_id                    = $product->get_id();
    //     $parent_id                  = $product->get_parent_id();
    //     $product_type               = $product->is_type('variation') ? 'variable' : 'simple';

    //     $is_stock_manager           = str_contains($backtrace, 'stock_manager_save_one_product_stock_data');
    //     $is_product_update          = str_contains($backtrace, 'wp_ajax_woocommerce_save_variations');
    //     $is_order_stock_update      = str_contains($backtrace, 'woocommerce_order_status_processing');

    //     $stringProduct              = print_r($product, true);
    //     $trimmed_product            = preg_replace('/\s*\R\s*/', ' ', trim($stringProduct));

    //     if (isset($this->loggers[$action])) {
    //         $this->loggers[$action]->logStockChange($product, $user, $productType, $parentID, $orderID);
    //     }


    // if ($is_stock_manager) {
    //     $matches = '';
    //     preg_match_all('/stock_quantity[\s\S]*?\[/', $trimmed_product, $matches);
    //     $old_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][1]);
    //     $new_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][2]);

    //     $this->database->insert(
    //         $current_user->get('nickname'),
    //         'Stock Manager',
    //         $post_id,
    //         $old_stock_quantity,
    //         $new_stock_quantity,
    //         $product_type,
    //         $parent_id,
    //         null
    //     );

    //     return;
    // }

    // if ($is_product_update) {
    //     $matches = '';
    //     preg_match_all('/stock_quantity[\s\S]*?\[/', $trimmed_product, $matches);
    //     $old_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][1]);
    //     $new_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][2]);

    //     $this->database->insert(
    //         $current_user->get('nickname'),
    //         'Product Interface',
    //         $post_id,
    //         $old_stock_quantity,
    //         $new_stock_quantity,
    //         $product_type,
    //         $parent_id,
    //         null
    //     );

    //     return;
    // }

    // if ($is_order_stock_update) {
    //     $new_stock_quantity = $product->get_stock_quantity();

    //     $this->database->insert(
    //         'Order',
    //         'Order',
    //         $post_id,
    //         $this->oldStockQty,
    //         $new_stock_quantity,
    //         $product_type,
    //         $parent_id,
    //         null,
    //         // self::order_id,
    //     );

    //     return;
    // }
    // }

}
