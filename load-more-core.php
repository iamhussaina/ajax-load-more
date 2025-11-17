<?php
/**
 * Core file for the AJAX Load More Posts Module.
 *
 * This file initializes the module by including the necessary components
 * and defining constants for file paths and URLs.
 *
 * @package     HussainasAjaxLoadMore
 * @version     1.0.0
 * @author      Hussain Ahmed Shrabon
 * @license     MIT
 * @link        https://github.com/iamhussaina
 * @textdomain  hussainas
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// --- Define Module Constants ---

/**
 * Define the directory path for this module.
 * This constant points to the 'ajax-load-more-module' directory.
 */
if ( ! defined( 'HUSSAINAS_MODULE_DIR_PATH' ) ) {
	define( 'HUSSAINAS_MODULE_DIR_PATH', trailingslashit( dirname( __FILE__ ) ) );
}

/**
 * Define the public URL for this module.
 *
 * This assumes the 'ajax-load-more-module' directory is placed directly
 * inside the active theme's root directory.
 * e.g., /wp-content/themes/your-theme/ajax-load-more-module/
 */
if ( ! defined( 'HUSSAINAS_MODULE_DIR_URL' ) ) {
	define( 'HUSSAINAS_MODULE_DIR_URL', trailingslashit( get_stylesheet_directory_uri() . '/' . basename( dirname( __FILE__ ) ) ) );
}

/**
 * Define the text domain for localization.
 */
if ( ! defined( 'HUSSAINAS_TEXT_DOMAIN' ) ) {
	define( 'HUSSAINAS_TEXT_DOMAIN', 'hussainas' );
}

// --- Include Module Components ---

// Include the script enqueueing logic.
require_once HUSSAINAS_MODULE_DIR_PATH . 'inc/enqueue-scripts.php';

// Include the AJAX handler functions.
require_once HUSSAINAS_MODULE_DIR_PATH . 'inc/ajax-functions.php';
