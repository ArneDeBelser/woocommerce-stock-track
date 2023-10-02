<?php

namespace ADB\WooCommerceStockTrack\Loggers\Contracts;

interface StockChangeLoggerInterface
{
    public function log($product, $user, $productType, $parentID, $postId, $oldStockQty, $orderId = null): void;
}
