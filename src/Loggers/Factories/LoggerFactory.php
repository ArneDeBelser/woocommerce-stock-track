<?php

namespace ADB\WooCommerceStockTrack\Loggers\Factories;

use ADB\WooCommerceStockTrack\DatabaseInterface;
use ADB\WooCommerceStockTrack\Loggers\Factories\LoggerFactoryInterface;
use ADB\WooCommerceStockTrack\Loggers\OrderLogger;
use ADB\WooCommerceStockTrack\Loggers\ProductInterfaceLogger;
use ADB\WooCommerceStockTrack\Loggers\StockManagerLogger;

class LoggerFactory implements LoggerFactoryInterface
{
    public function __construct(public DatabaseInterface $database)
    {
    }

    public function create($action)
    {
        switch ($action) {
            case 'Stock Manager':
                return new StockManagerLogger($this->database);
            case 'Product Interface':
                return new ProductInterfaceLogger($this->database);
            case 'Order':
                return new OrderLogger($this->database);
            default:
                return null;
        }
    }
}
