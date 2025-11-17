<?php
/**
 * Handles enqueueing scripts and styles for the module.
 *
 * @package     HussainasAjaxLoadMore
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueues the necessary JavaScript for the AJAX load more functionality.
 *
 * This function also localizes the script, passing important data like the
 * AJAX URL, a security nonce, and translatable text to the client-side script.
 *
 * @since 1.0.0
 */
function hussainas_enqueue_assets() {

	// We only want to load this script on pages where posts are listed.
	// This condition can be refined by the theme developer as needed.
	if ( is_home() || is_front_page() || is_archive() || is_search() ) {

		// Register the main JavaScript file.
		wp_register_script(
			'hussainas-load-more-main', // Handle
			HUSSAINAS_MODULE_DIR_URL . 'assets/js/main.js', // Source
			array( 'jquery' ), // Dependencies
			'1.0.0', // Version
			true // Load in the footer
		);

		// Localize the script with data required for AJAX requests.
		wp_localize_script(
			'hussainas-load-more-main', // Handle to attach data to
			'hussainas_ajax_object', // JavaScript object name
			array(
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'hussainas_ajax_nonce' ),
				'loading_text' => esc_html__( 'Loading...', HUSSAINAS_TEXT_DOMAIN ),
				'error_message' => esc_html__( 'Something went wrong. Please try again.', HUSSAINAS_TEXT_DOMAIN ),
			)
		);

		// Enqueue the script.
		wp_enqueue_script( 'hussainas-load-more-main' );
	}
}
add_action( 'wp_enqueue_scripts', 'hussainas_enqueue_assets' );
