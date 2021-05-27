<?php

defined('ABSPATH') or die('Something wrong');

if ( ! class_exists( 'Skrottilbud_Distance' ) ) :

    class Skrottilbud_Distance {

                /**
         * Constructor.
         */
        function __construct() {
            $this->whitelist = array('administrator');
            add_action('woocommerce_single_product_summary', array( $this, 'product_distance' ) );
            add_action('woocommerce_before_shop_loop_item_title', array( $this, 'show_cities' ), 1 );
        }

        // Calcualte distance from seller and customer. Add distance to price offer if submitted offer
        function product_distance() {
            $current_user = wp_get_current_user();
            $user_role = (array) $current_user->roles;
            if ( in_array($user_role[0], $this->whitelist) ) {
                $user_id = get_current_user_id();
                $product_id = get_the_ID();
                $price_offers = get_post_meta( $product_id, '_' . 'wcj_price_offers', true);
                $product_title = wc_get_product( $product_id )->get_title();
            
                $origin = str_replace(' ', '+', get_user_meta($user_id, 'billing_address_1', true));
                $formatted_destination = get_post_meta($product_id, '_' . 'post_address', true) . " " . "Danmark";
                $destination = str_replace(' ', '+', $formatted_destination);
            
                // Calculate distance using Distance matrix API
                $api = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?mode=driving&units=metric&origins=".$origin."&destinations=".$destination."&key=".API_KEY."");
                $data = json_decode($api);
            
                // Check for error
                if ($data->status == 'INVALID_REQUEST') {
                    echo 'Something went wrong';
                } elseif ($data->rows[0]->elements[0]->status == 'NOT_FOUND') {
                    echo 'Invalid input';
                } elseif ($data->rows[0]->elements[0]->status == 'ZERO_RESULTS') {
                    echo 'No results found';
                } else {
                    $distance = $data->rows[0]->elements[0]->distance->text;
                    $updated_price_offers = array();
                    echo 'Distance: ' . $distance;
                    // If submitted offer, append distance
                    if ( isset( $_POST['wcj-offer-price-submit'] ) ) {
                        foreach ($price_offers as $price_offer) {
                            if ($price_offer['product_title'] == $product_title) {
                                $price_offer['distance'] = $distance;
                                $updated_price_offers[] = $price_offer;
                            } else {
                                $updated_price_offers[] = $price_offer;
                            }
                        }
                        update_post_meta($product_id, '_' . 'wcj_price_offers', $updated_price_offers);
                    }
                }
            }
        }

        // Show city above each product
        function show_cities() {
            $product_id = get_the_ID();
            echo get_post_meta($product_id, '_' . 'post_address', true) . " " . "Danmark";
        }
    
    }

endif;
return new Skrottilbud_Distance();