<?php

namespace ADB\WooCommerceStockTrack\Loggers;

use ADB\WooCommerceStockTrack\Loggers\AbstractLogger;
use ADB\WooCommerceStockTrack\Loggers\Contracts\StockChangeLoggerInterface;

class OrderLogger extends AbstractLogger implements StockChangeLoggerInterface
{
    const SOURCE = 'Order';

    private $oldStockQty = 0;

    public function log($product, $user, $productType, $parentID, $postId, $orderId = null)
    {
        add_action('woocommerce_variation_before_set_stock', [$this, 'setOldStock'], 10, 1);

        $new_stock_quantity = $product->get_stock_quantity();

        $this->database->insert(
            $user->get('nickname'),
            self::SOURCE,
            $postId,
            $this->oldStockQty,
            $new_stock_quantity,
            $productType,
            $parentID,
            null
        );
    }

    public function setOldStock($product): void
    {
        $this->oldStockQty = $product->get_stock_quantity();
    }
}
