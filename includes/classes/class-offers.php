<?php

defined('ABSPATH') or die('Something wrong');

if (! class_exists('Offers')) :
    class Offers {

        function __construct() {
            wp_register_style( 'table-styles', plugins_url('../styles/custom_style.css',__FILE__ ) );
            wp_enqueue_style( 'table-styles' );

            add_filter ( 'woocommerce_account_menu_items', array($this, 'log_history_link'), 40 );
            add_action( 'init', array($this, 'add_endpoint') );
            add_action( 'woocommerce_account_offered-prices_endpoint', array($this, 'my_account_endpoint_content') );
        }

        function log_history_link( $menu_links ){
            $whitelist_roles = array('administrator');
            $user = wp_get_current_user();
            $user_role = (array) $user->roles;
        
            in_array($user_role[0], $whitelist_roles)
                ? $menu_links_prefix = array( 'offered-prices' => 'Offered prices' )
                : $menu_links_prefix = array();
            $menu_links = array_slice( $menu_links, 0, 1, true )
                + $menu_links_prefix
                + array_slice( $menu_links, 1, NULL, true );
        
            return $menu_links;
        }

        function add_endpoint() {
            flush_rewrite_rules();
            $whitelist_roles = array('administrator');
            $user = wp_get_current_user();
            $user_role = (array) $user->roles;
            
            // WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
        
            if (in_array($user_role[0], $whitelist_roles)) {
                add_rewrite_endpoint( 'offered-prices', EP_ROOT | EP_PAGES );
            }
        }

        function my_account_endpoint_content() {
            global $wpdb;
            $data = $wpdb->get_results("SELECT post_id FROM wp_postmeta WHERE meta_key='_wcj_price_offers'");
            $current_user = wp_get_current_user();
        
            if (!empty($data)) {
                $data = array_reverse($data);
                echo '<div class="offer_card_section">';
                foreach ($data as $product) {
                    $product_meta = wc_get_product($product->post_id);
                    $product_name = $product_meta->get_title();
                    $price_offers = get_post_meta($product->post_id, '_' . 'wcj_price_offers', true);
        
                    if (!empty($price_offers)) {
                        $price_offers_filtered = array_filter($price_offers, function($price_offer) use ($current_user) {
                            return ($price_offer['customer_email'] == $current_user->user_email);
                        });
                    
                    if (in_array($product_name, array_column($price_offers_filtered, 'product_title'))) {
                        $image_url = wp_get_attachment_image_src(get_post_thumbnail_id( $product->post_id ), 'single-post-thumbnail')[0];
                        ?>
                            <div class="offer_card_container">
                                <div class="offer_card_img">
                                    <img style="height: 100%; width: 100%;" src="<?php echo $image_url ?>"/>
                                </div>
                                <div class="offer_card_date">
                                    <i><?php echo date('d-m-Y, H:i:s', $price_offers_filtered[0]['offer_timestamp']) ?></i>
                                </div>
                                <div class="offer_card_title">
                                    <h4><?php echo $product_name ?></h4>
                                </div>
                                <div class="offer_card_lower">
                                    <div class="offer_card_lower_element">
                                        <b><?php echo $price_offers_filtered[0]['offered_price']; ?>,- DKK</b>
                                    </div>
                                    <div class="offer_card_element_seperator"></div>
                                    <div class="offer_card_lower_element">
                                        <b><?php
                                            echo ($price_offers_filtered[0]['status'] == 'Accepted' || $price_offers_filtered[0]['status'] == 'Rejected')
                                            ? $price_offers_filtered[0]['status']
                                            : 'Processing';
                                        ?></b>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    }
                }
                echo '</div>';
            } else {
                echo '<h3>No offers...</h3>';
            }
        }
    }

endif;
return new Offers();