<?php
/**
 * Loader for skrottilbud plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Classes
require_once( dirname(__FILE__) . '/../classes/class-form-data.php' );
require_once( dirname(__FILE__) . '/../classes/class-distance.php' );
require_once( dirname(__FILE__) . '/../classes/class-product-transition.php' );
require_once( dirname(__FILE__) . '/../classes/class-status.php' );
require_once( dirname(__FILE__) . '/../classes/class-offers.php' );
require_once( dirname(__FILE__) . '/../classes/class-add-product-endpoint.php' );
require_once( dirname(__FILE__) . '/../classes/class-script-fixer.php' );
