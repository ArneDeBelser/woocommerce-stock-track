<?php

namespace ADB\WooCommerceStockTrack\Loggers;

use ADB\WooCommerceStockTrack\Loggers\AbstractLogger;
use ADB\WooCommerceStockTrack\Loggers\Contracts\StockChangeLoggerInterface;

class ProductInterfaceLogger extends AbstractLogger implements StockChangeLoggerInterface
{
    const SOURCE = 'Product Interface';

    public function log($product, $user, $productType, $parentID, $postId, $orderId = null)
    {
        $stringProduct = print_r($product, true);
        $trimmed_product = preg_replace('/\s*\R\s*/', ' ', trim($stringProduct));

        $matches = '';
        preg_match_all('/stock_quantity[\s\S]*?\[/', $trimmed_product, $matches);
        $old_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][0]);
        $new_stock_quantity = (int) preg_replace("/[^0-9]/", "", $matches[0][1]);

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
