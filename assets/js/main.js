/**
 * Handles client-side AJAX logic for loading more posts.
 *
 * This script waits for a click on the load more button,
 * sends an AJAX request, and appends the returned HTML.
 *
 * It relies on 'hussainas_ajax_object' localized from PHP.
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        
        // Find the "Load More" button.
        var loadMoreBtn = $('#hussainas-load-more-btn');

        // If the button doesn't exist, do nothing.
        if (!loadMoreBtn.length) {
            return;
        }

        // Add click event handler.
        loadMoreBtn.on('click', function (e) {
            e.preventDefault();

            var button = $(this);
            var container = $('#hussainas-posts-container'); // The container for posts.
            
            // Retrieve current page and total pages from data attributes.
            var currentPage = parseInt(button.data('page'), 10);
            var totalPages = parseInt(button.data('total-pages'), 10);
            var nextPage = currentPage + 1;

            // Store original button text.
            var originalText = button.text();

            // Show loading state and disable the button.
            button.text(hussainas_ajax_object.loading_text).prop('disabled', true);

            // Prepare AJAX data.
            var data = {
                action: 'hussainas_load_more_posts',
                nonce: hussainas_ajax_object.nonce,
                page: nextPage,
                // Add any other query parameters here if needed
                // e.g., category: button.data('category')
            };

            // Send the AJAX request.
            $.post(hussainas_ajax_object.ajax_url, data)
                .done(function (response) {
                    if (response.success) {
                        // Success: Append new posts.
                        container.append(response.data.html);

                        // Update the button's page attribute.
                        button.data('page', nextPage);

                        // Check if we have reached the last page.
                        if (nextPage >= totalPages) {
                            // All posts loaded. Hide the button.
                            button.fadeOut(300, function() {
                                $(this).remove();
                            });
                        } else {
                            // Reset button state.
                            button.text(originalText).prop('disabled', false);
                        }
                    } else {
                        // Error or no more posts.
                        // Hide the button as there is nothing more to load.
                        button.fadeOut(300, function() {
                            $(this).remove();
                        });
                        console.log(response.data.message || 'No more posts found.');
                    }
                })
                .fail(function (xhr, status, error) {
                    // Handle AJAX failure (e.g., network error).
                    console.error('AJAX request failed: ' + error);
                    button.text(originalText).prop('disabled', false);
                    // Optionally display an error message to the user.
                    container.after('<p class="hussainas-ajax-error">' + hussainas_ajax_object.error_message + '</p>');
                });
        });
    });

})(jQuery);
