<?php

defined('ABSPATH') or die('Something wrong');

if (! class_exists('Script_Fixer')) :
    class Script_Fixer {

        function __construct() {
            add_filter( 'script_loader_tag', array($this, 'namespace_async_scripts'), 10, 2 );
        }

        /**
        * Add an aysnc attribute to an enqueued script
        * 
        * @param string $tag Tag for the enqueued script.
        * @param string $handle The script's registered handle.
        * @return string Script tag for the enqueued script
        */
        function namespace_async_scripts( $tag, $handle ) {
            // Just return the tag normally if this isn't one we want to async
            if ( 'main-google-library' !== $handle ) {
                return $tag;
            }
                return str_replace( ' src', ' async src', $tag );
            }
    }

endif;
return new Script_Fixer();