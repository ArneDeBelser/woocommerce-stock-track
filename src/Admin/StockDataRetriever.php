<?php

namespace ADB\WooCommerceStockTrack\Admin;

class StockDataRetriever
{
    public function fetchStockData($parent_id)
    {
        global $wpdb;

        $sql = $wpdb->prepare("SELECT * FROM wp_woocommerce_stock_track WHERE parent_id = %s", $parent_id);

        return $wpdb->get_results($sql, ARRAY_A);
    }
}
