<?php
/**
 * Plugin Name: Skrottilbud.dk
 * Description: Added cooperative functionality for Booster Plus, async google script, google API and added product meta.
 **/

if ( ! class_exists( 'Skrottilbud' ) ) :

    final class Skrottilbud {
        /**
         * @var Skrottilbud The single instance of the class
         */
        protected static $_instance = null;
        
        /**
         * Main Skrottilbud Instance.
         * Ensures only one instance of Skrottilbud is loaded or can be loaded.
         * @return Skrottilbud - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        function __construct() {
            require_once( 'includes/core/skrottilbud-loader.php' );
        }
    }

endif;

if ( ! function_exists( 'ST' ) ) {
	/**
	 * Returns the main instance of Skrottilbud to prevent the need to use globals.
	 * @return  Skrottilbud
	 */
	function ST() {
		return Skrottilbud::instance();
	}
}

ST();
