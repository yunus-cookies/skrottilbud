<?php

defined('ABSPATH') or die('Something wrong');

if ( ! class_exists( 'Skrottilbud_Form_Data' ) ) :

    class Skrottilbud_Form_Data {
    
        /**
         * Constructor.
         */
        function __construct() {
            add_action( 'add_meta_boxes', array( $this, 'add_product_form_data_meta_box' ) );
            add_action( 'save_post_product', array( $this, 'update_product_form_data_meta_box' ) );
        }

        // Add a meta box in product page
        function add_product_form_data_meta_box() {
            add_meta_box(
                'product-form-data',
                esc_html__( 'Add form data', 'skrottilbud-plugin' ),
                array($this, 'add_product_form_data_meta_box_callback'),
                'product',
                'side',
                'low');
        }

        // Content of meta box
        function add_product_form_data_meta_box_callback() {
            $product_id = get_the_ID();
            $current_address = get_post_meta($product_id, '_post_address', true);
            $max_distance = get_post_meta( $product_id, '_maxd', true );
            echo '<h3>' . esc_html__('Address', 'skrottilbud-plugin') . ':</h3>' . '<input type="text" name="post_address" value="'.$current_address.'">';
            echo '<h3>' . esc_html__('Max distance', 'skrottilbud-plugin') . ':</h3>' . $max_distance;
        }

        // On update product, save typed/current post address
        function update_product_form_data_meta_box() {
            $product_id = get_the_ID();
            if (isset($_POST['post_address'])) { 
			    update_post_meta( $product_id, '_post_address', $_POST['post_address']);
            }
        }
    }
    
endif;
return new Skrottilbud_Form_Data();