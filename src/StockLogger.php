<?php

namespace ADB\WooCommerceStockTrack;

use ADB\WooCommerceStockTrack\ActionDeterminer;
use ADB\WooCommerceStockTrack\DatabaseInterface;
use ADB\WooCommerceStockTrack\Loggers\Factories\LoggerFactoryInterface;
use ADB\WooCommerceStockTrack\Loggers\OrderLogger;
use ADB\WooCommerceStockTrack\Traits\HasLogger;

class StockLogger
{
    use HasLogger;

    private $oldStockQty = 0;

    public function __construct(
        public DatabaseInterface $database,
        private LoggerFactoryInterface $loggerFactory,
        private ActionDeterminer $actionDeterminer
    ) {
        add_action('woocommerce_variation_before_set_stock',    [$this, 'setOldStock'], 10, 1);
        add_action('woocommerce_product_set_stock',             [$this, 'handleStockChange'], 10, 1);
        add_action('woocommerce_variation_set_stock',           [$this, 'handleStockChange'], 10, 1);
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
            $stockLogger->log($product, $user, $productType, $parentID, $postId, $this->oldStockQty);
        }
    }

    public function setOldStock($product): void
    {
        $this->oldStockQty = $product->get_stock_quantity();
    }
}
