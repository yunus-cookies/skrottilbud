<?php

defined('ABSPATH') or die('Something wrong');

if (! class_exists('Product_Transition')):

    class Product_Transition {

        function __construct() {
            add_action( 'transition_post_status', array($this, 'add_this_to_new_products'), 10, 3 );
        }

        function add_this_to_new_products( $new_status, $old_status, $post ) {

            global $post;
            if ( $post->post_type !== 'product' ) return;
            if ( 'publish' !== $new_status or 'publish' === $old_status ) return;
            
            $email_content ='Dit produkt er offentliggjort, og er nu synlig.';
            $email_subject = 'Produkt synlig - skrottilbud.dk';
            
            $product_id = get_the_ID();
            $author_id = get_post_field( 'post_author', $product_id );
            $author_email = get_the_author_meta( 'user_email', $author_id );
        
            wc_mail( $author_email, $email_subject, $email_content );
        }

    }

endif;
return new Product_Transition();