<?php

namespace ADB\WooCommerceStockTrack;

use ADB\WooCommerceStockTrack\Loggers\OrderLogger;
use ADB\WooCommerceStockTrack\Loggers\ProductInterfaceLogger;
use ADB\WooCommerceStockTrack\Loggers\StockManagerLogger;
use ReflectionClass;
use ReflectionMethod;

class ActionDeterminer
{
    public function determine()
    {
        switch (true) {
            case did_action('wp_ajax_woocommerce_save_variations') > 0:
                return ProductInterfaceLogger::SOURCE;
            case did_action('woocommerce_order_status_processing') > 0:
                return OrderLogger::SOURCE;
                // case $this->hasPerformedAction('Stock_Manager', 'woocommerce_product_set_stock'):
                // case $this->hasPerformedAction('Stock_Manager', 'woocommerce_variation_set_stock'):
                //     return StockManagerLogger::SOURCE;
            default:
                return null;
        }
    }
}
