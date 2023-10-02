<?php

namespace ADB\WooCommerceStockTrack;

use ADB\WooCommerceStockTrack\Admin\StockDataRetriever;
use ADB\WooCommerceStockTrack\Admin\StockVariationDisplay;
use ADB\WooCommerceStockTrack\Database;

class Plugin
{
    private array $modules = [];

    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'initModules']);
    }

    public function initModules()
    {
        global $wpdb;

        $this->modules = [
            $database = new Database($wpdb),
            new StockLogger($database),
            new StockVariationDisplay(new StockDataRetriever()),
        ];
    }
}
