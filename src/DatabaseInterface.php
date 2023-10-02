<?php

namespace ADB\WooCommerceStockTrack;

interface DatabaseInterface
{
    public function activate(): void;

    public function insert(
        $user,
        $source_from,
        $post_id,
        $old_stock_value,
        $new_stock_value,
        $product_type,
        $parent_id,
        $order_id
    ): void;
}
