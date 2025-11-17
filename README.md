# AJAX Load More Module for WordPress

A professional, procedural-based PHP module for adding "Load More" functionality to a WordPress theme without a plugin. This module uses WordPress's built-in AJAX functionality and `WP_Query` to fetch and append posts seamlessly.

## 🌟 Features

* **Plugin-Free:** Designed to be included directly within a theme.
* **Performance:** Loads content via AJAX without page reloads.
* **Secure:** Utilizes WordPress nonces (`wp_create_nonce`, `check_ajax_referer`) for secure requests.
* **Flexible:** Leverages your theme's existing `template-parts/content.php` file for consistent styling.
* **Professional Standard:** Follows WordPress coding standards, is well-commented, and is organized for maintainability.

---

## 🛠️ Installation

1.  **Download:** Download the `ajax-load-more` directory.
2.  **Copy to Theme:** Place the entire `ajax-load-more` folder into the root of your active WordPress theme (e.g., `/wp-content/themes/your-theme/ajax-load-more/`).
3.  **Include in Theme:** Open your theme's `functions.php` file and add the following line at the end:

    ```php
    // Include the AJAX Load More module.
    require_once get_template_directory() . '/ajax-load-more/load-more-core.php';
    ```

---

## 🚀 How to Use

To make the "Load More" button appear, you must modify your theme's template files (like `index.php`, `archive.php`, or `category.php`).

You need to add two things:
1.  A container wrapper with the ID `#hussainas-posts-container`.
2.  The "Load More" button with the ID `#hussainas-load-more-btn` and special `data-` attributes.

### Example Template (`index.php` / `archive.php`)

Find your theme's main post loop. It will look something like this. You need to wrap the loop and add the button *after* it.

**BEFORE:**

```php
<?php if ( have_posts() ) : ?>

    <?php
    // Start the Loop.
    while ( have_posts() ) :
        the_post();
        get_template_part( 'template-parts/content', get_post_format() );
    endwhile;

    // Previous/next page navigation.
    the_posts_pagination( ... );
    ?>

<?php else : ?>
    <?php get_template_part( 'template-parts/content', 'none' ); ?>
<?php endif; ?>
```

**AFTER (With Module Implementation):**

```php
<?php if ( have_posts() ) : ?>

    <div id="hussainas-posts-container">
        <?php
        // Start the Loop.
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', get_post_format() );
        endwhile;
        ?>
    </div><?php
    // 2. Add the "Load More" button
    // We use the main query object ($wp_query) to get page counts.
    global $wp_query;

    // Only show the button if there are more posts to load
    if ( $wp_query->max_num_pages > 1 ) :
    ?>
        <div class="hussainas-load-more-wrapper" style="text-align: center; margin: 20px 0;">
            <button
                id="hussainas-load-more-btn"
                class="button load-more-button"
                data-page="1"
                data-total-pages="<?php echo esc_attr( $wp_query->max_num_pages ); ?>"
            >
                <?php esc_html_e( 'Load More Posts', 'hussainas' ); ?>
            </button>
        </div>
    <?php endif; ?>
    
    <?php
    // IMPORTANT: Remove or comment out old pagination
    // the_posts_pagination( ... );
    ?>

<?php else : ?>
    <?php get_template_part( 'template-parts/content', 'none' ); ?>
<?php endif; ?>
```

---

## ⚙️ Customization

### Customizing the Query

By default, the AJAX query in `inc/ajax-functions.php` fetches standard posts. If you are on a custom archive (e.g., a category page) and want to load more posts *from that specific category*, you must pass the category data to the JavaScript.

**Step 1: Update the Button (in `archive.php`)**

Add a `data-category` attribute to the button:

```php
<button
    id="hussainas-load-more-btn"
    ...
    data-page="1"
    data-total-pages="<?php echo esc_attr( $wp_query->max_num_pages ); ?>"
    data-category="<?php echo esc_attr( get_queried_object_id() ); ?>"
>
```

**Step 2: Update `assets/js/main.js`**

In the `data` object, retrieve and send the category:

```javascript
// Prepare AJAX data.
var data = {
    action: 'hussainas_load_more_posts',
    nonce: hussainas_ajax_object.nonce,
    page: nextPage,
    category: button.data('category') // <-- Add this line
};
```

**Step 3: Update `inc/ajax-functions.php`**

In the `hussainas_load_more_posts_handler` function, retrieve the category and add it to the `$args` array:

```php
// ... inside hussainas_load_more_posts_handler() ...

// 3. Define Query Arguments.
$args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => get_option( 'posts_per_page' ),
    'paged'          => $page,
);

// Check for and add the category parameter if it exists
if ( isset( $_POST['category'] ) && ! empty( $_POST['category'] ) ) {
    $args['cat'] = absint( $_POST['category'] );
}

// 4. Run the Query.
$query = new WP_Query( $args );

// ... rest of the function ...
```
