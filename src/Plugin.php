<?php

namespace ADB\WooCommerceStockTrack;

use ADB\WooCommerceStockTrack\ActionDeterminer;
use ADB\WooCommerceStockTrack\Admin\StockDataRetriever;
use ADB\WooCommerceStockTrack\Admin\StockVariationDisplay;
use ADB\WooCommerceStockTrack\Database;
use ADB\WooCommerceStockTrack\Loggers\Factories\LoggerFactory;

class Plugin
{
    public $modules =  [];

    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'initModules']);
    }

    public function initModules()
    {
        global $wpdb;

        $this->modules = [
            $database = new Database($wpdb),
            $loggerFactory = new LoggerFactory($database),
            $actionDeterminer = new ActionDeterminer(),
            new StockLogger($database, $loggerFactory, $actionDeterminer),
            new StockVariationDisplay(new StockDataRetriever()),
        ];
    }
}
