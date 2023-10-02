<?php

use ADB\WooCommerceStockTrack\Plugin;

/**
 * Plugin Name: WooCommerce Stock Track
 * Description: This plugin helps you monitor and manage changes to stock levels for your WooCommerce products.
 * Version: 1.0
 * Author: De Belser Arne
 * Author URI: https://www.arnedebelser.be/
 * Text Domain: woocommerce-stock-track
 *
 * Copyright (c) 2023 De Belser Arne
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

require_once __DIR__ . '/vendor/autoload.php';

if (!function_exists('write_log')) {
    function write_log($log)
    {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

$woocommerce_stock_track = new Plugin();
