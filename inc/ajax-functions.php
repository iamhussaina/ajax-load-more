<?php
/**
 * Handles the core AJAX functionality for loading posts.
 *
 * @package     HussainasAjaxLoadMore
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The AJAX handler function to query and return more posts.
 *
 * This function is hooked to both 'wp_ajax_' and 'wp_ajax_nopriv_'
 * to work for both logged-in and logged-out users.
 *
 * @since 1.0.0
 */
function hussainas_load_more_posts_handler() {

	// 1. Security Check: Verify the AJAX nonce.
	// 'nonce' is the key we are sending from our JS file.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hussainas_ajax_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Invalid security token.' ), 403 );
		return;
	}

	// 2. Sanitize and retrieve the page number.
	$page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;

	// 3. Define Query Arguments.
	// IMPORTANT: These args should ideally match the main query on the page
	// to ensure consistency. The theme developer can pass additional
	// query parameters from JS (e.g., category, post_type) and
	// retrieve them here from $_POST if needed.
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => get_option( 'posts_per_page' ), // Use default WP setting
		'paged'          => $page,
	);
	
	// DEVELOPER NOTE: To make this query specific (e.g., for a category),
	// you would add parameters like this:
	// if ( isset( $_POST['category'] ) && ! empty( $_POST['category'] ) ) {
	//     $args['cat'] = absint( $_POST['category'] );
	// }

	// 4. Run the Query.
	$query = new WP_Query( $args );

	// 5. Process the Query.
	if ( $query->have_posts() ) {

		// Start output buffering to capture the HTML.
		ob_start();

		// Loop through posts.
		while ( $query->have_posts() ) :
			$query->the_post();

			/**
			 * Use the theme's existing template part.
			 * This is the most professional and flexible method, as it
			 * reuses the theme's post layout (e.g., content.php)
			 * without duplicating HTML in this function.
			 */
			get_template_part( 'template-parts/content', get_post_format() );

		endwhile;

		// Reset post data.
		wp_reset_postdata();

		// Get the buffered HTML.
		$html = ob_get_clean();

		// Send the HTML back as a success response.
		wp_send_json_success( array( 'html' => $html ) );

	} else {
		// No more posts found. Send a specific "no posts" signal.
		// The JS will interpret this to hide the button.
		wp_send_json_error( array( 'message' => 'No more posts found.' ) );
	}

	// wp_die() is automatically called by wp_send_json_success/error.
}
// Hook for logged-in users.
add_action( 'wp_ajax_hussainas_load_more_posts', 'hussainas_load_more_posts_handler' );
// Hook for logged-out users.
add_action( 'wp_ajax_nopriv_hussainas_load_more_posts', 'hussainas_load_more_posts_handler' );
