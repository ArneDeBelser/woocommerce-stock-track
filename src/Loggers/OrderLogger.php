<?php

namespace ADB\WooCommerceStockTrack\Loggers;

use ADB\WooCommerceStockTrack\Loggers\AbstractLogger;
use ADB\WooCommerceStockTrack\Loggers\Contracts\StockChangeLoggerInterface;

class OrderLogger extends AbstractLogger implements StockChangeLoggerInterface
{
    const SOURCE = 'Order';

    public function log($product, $user, $productType, $parentID, $postId, $oldStockQty, $orderId = null): void
    {
        $new_stock_quantity = $product->get_stock_quantity();

        $this->database->insert(
            $user->get('nickname'),
            self::SOURCE,
            $postId,
            $oldStockQty,
            $new_stock_quantity,
            $productType,
            $parentID,
            null
        );
    }
}
