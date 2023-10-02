<?php

namespace ADB\WooCommerceStockTrack\Admin;

use ADB\WooCommerceStockTrack\Admin\StockDataRetriever;

class StockVariationDisplay
{
    private $dataRetriever;

    public function __construct(StockDataRetriever $dataRetriever)
    {
        $this->dataRetriever = $dataRetriever;

        add_action('woocommerce_product_after_variable_attributes', [$this, 'outputStockLogTableOnVariableLine'], 10, 3);
    }

    public function outputStockLogTableOnVariableLine($loop, $variation_data, $variation): void
    {
        $parent_id = wp_get_post_parent_id($variation);
        $post_id = $variation->to_array()['ID'];
        $stock_data = $this->dataRetriever->fetchStockData($parent_id);

        if (empty($stock_data)) {
            echo '<p>' . __('No current stock changes', 'woocommerce-stock-track') . '</p>';
            return;
        }

        echo '<table class="widefat striped">';
        echo '<thead>
				<tr>
					<th>' . __('Executed', 'woocommerce-stock-track') . '</th>
					<th>' . __('User/Order', 'woocommerce-stock-track') . '</th>
					<th>' . __('Source', 'woocommerce-stock-track') . '</th>
					<th>' . __('Old Stock Value', 'woocommerce-stock-track') . '</th>
					<th>' . __('New Stock Value', 'woocommerce-stock-track') . '</th>
				</tr>
			</thead>';

        foreach ($stock_data as $data) {
            if ($data['post_id'] == $post_id) {
                echo '<tr>';
                echo '<td>' . esc_html($data["created"]) . '</td>';
                echo '<td>' . esc_html($data["user"]) . '</td>';
                echo '<td>' . esc_html($data["source_from"]) . '</a></td>';
                echo '<td>' . esc_html($data["old_stock_value"]) . '</td>';
                echo '<td>' . esc_html($data["new_stock_value"]) . '</td>';
                echo '</tr>';
            }
        }

        echo '</table>';
    }
}
