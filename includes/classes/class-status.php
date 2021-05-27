<?php

defined('ABSPATH') or die('Something wrong');

if (! class_exists('Product_Status')) :

    class Product_Status {

        function __construct() {
            add_action('woocommerce_before_shop_loop_item_title', array($this, 'grid_offer_check'));
            add_filter( 'manage_edit-product_columns', array($this, 'status_column'), 20 );
            add_action( 'manage_posts_custom_column', array($this, 'populate_status'));
        }

        // Status bar above each product
        function grid_offer_check() {
            $product_id = get_the_ID();
            $current_user = wp_get_current_user();
            $price_offers = get_post_meta($product_id, '_' . 'wcj_price_offers', true);
            if (!empty($price_offers)) {
                $emails_in_price_offers = array_column($price_offers, 'customer_email');
                if (in_array($current_user->user_email, $emails_in_price_offers)) {
                    echo '<div style="border: 1px solid green;border-radius: 100%;"></div>';
                } else {
                    echo '<div style="border: 1px solid red;border-radius: 100%;"></div>';
                }
            } else {
                echo '<div style="border: 1px solid red;border-radius: 100%;"></div>';
            }
        }

        // Add new column to columns array
        function status_column( $columns_array ) {
            return $columns_array
            + array( 'status' => 'Status' );
        }

        // Content of new Status column
        function populate_status( $column_name ) {
            if( $column_name  == 'status' ) {
                $product_id = get_the_ID();
                $price_offers = get_post_meta($product_id, '_' . 'wcj_price_offers', true);
                if (!empty($price_offers)) {
                    $all_status = array_column($price_offers, 'status');
                    $accepted_email = array_values(array_diff(array_column($price_offers, 'current_email'), array('')))[0];
                    if (in_array('Waiting', $all_status)) {
                        echo '<div style="display:inline-block;width:10px;height:10px;border-radius:100%;background-color:yellow;"></div> Waiting';
                    } elseif (in_array('Accepted', $all_status)) {
                        echo '<div style="display:inline-block;width:10px;height:10px;border-radius:100%;background-color:green;"></div> ' . $accepted_email;
                    } else {
                        echo '<div style="display:inline-block;width:10px;height:10px;border-radius:100%;background-color:red;"></div> No offer';
                    }
                } else {
                    echo '<div style="display:inline-block;width:10px;height:10px;border-radius:100%;background-color:red;"></div> No offer';
                }
            }
        }
    }

endif;
return new Product_Status();