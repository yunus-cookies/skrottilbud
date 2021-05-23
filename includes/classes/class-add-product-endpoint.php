<?php

defined('ABSPATH') or die('Something wrong');

if (! class_exists('Add_Product_Endpoint')) :
    class Add_Product_Endpoint {

        function __construct() {
            add_filter ( 'woocommerce_account_menu_items', array($this, 'log_history_link'), 20 );
            add_action( 'init', array($this, 'add_endpoint') );
            add_action( 'woocommerce_account_add-products_endpoint', array($this, 'my_account_endpoint_content') );
        }

        function log_history_link( $menu_links ){
            $whitelist_roles = array('administrator');
            $user = wp_get_current_user();
            $user_role = (array) $user->roles;
        
            in_array($user_role[0], $whitelist_roles)
                ? $menu_links_prefix = array( 'add-products' => 'Add products' )
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
                add_rewrite_endpoint( 'add-products', EP_ROOT | EP_PAGES );
            }
        }

        function my_account_endpoint_content() {
            echo do_shortcode('[wcj_product_add_new]');
        }
    }

endif;
return new Add_Product_Endpoint();