<?php

namespace ADB\WooCommerceStockTrack\Loggers;

use ADB\WooCommerceStockTrack\DatabaseInterface;
use ADB\WooCommerceStockTrack\Loggers\Contracts\StockChangeLoggerInterface;

class AbstractLogger implements StockChangeLoggerInterface
{
    public function __construct(public DatabaseInterface $database)
    {
    }

    public function log($product, $user, $productType, $parentID, $postId, $orderId = null)
    {
    }
}
