<?php

namespace ADB\WooCommerceStockTrack\Loggers;

use ADB\WooCommerceStockTrack\Loggers\AbstractLogger;
use ADB\WooCommerceStockTrack\Loggers\Contracts\StockChangeLoggerInterface;

class StockManagerLogger extends AbstractLogger  implements StockChangeLoggerInterface
{
    const SOURCE = 'Stock Manager';

    public function log($product, $user, $productType, $parentID, $postId, $oldStockQty, $orderId = null): void
    {
        $stringProduct = print_r($product, true);
        $trimmed_product = preg_replace('/\s*\R\s*/', ' ', trim($stringProduct));

        $matches = '';
        preg_match_all('/stock_quantity[\s\S]*?\[/', $trimmed_product, $matches);
        $old_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][1]);
        $new_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][2]);

        $this->database->insert(
            $user->get('nickname'),
            self::SOURCE,
            $postId,
            $old_stock_quantity,
            $new_stock_quantity,
            $productType,
            $parentID,
            null
        );
    }
}
