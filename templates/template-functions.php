<?php
/**
 * Template functions for the Newspaper Web theme
 *
 * This file contains template-specific functions to be used in theme templates
 *
 * @package NewspaperWeb
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if the title should be displayed for a post or page
 * 
 * @param int $post_id Optional post ID. Default is current post.
 * @return bool True if the title should be displayed, false otherwise
 */
function newspaperweb_show_title($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (!$post_id) {
        return true; // Por defecto, mostrar título
    }
    
    $show_title = get_post_meta($post_id, '_newspaperweb_show_title', true);
    
    // Si no hay valor guardado, asumir que sí debe mostrarse
    if ('' === $show_title) {
        return true;
    }
    
    return 'yes' === $show_title;
}

/**
 * Display post categories with bootstrap styling
 * 
 * @param int $post_id Optional post ID. Default is current post.
 * @return void
 */
function newspaperweb_the_category($post_id = 0) {
    $categories = get_the_category($post_id);
    if (!empty($categories)) {
        echo '<div class="post-categories mb-2">';
        foreach ($categories as $category) {
            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="badge bg-secondary text-decoration-none me-1">';
            echo esc_html($category->name);
            echo '</a>';
        }
        echo '</div>';
    }
}

/**
 * Display post tags with bootstrap styling
 * 
 * @param int $post_id Optional post ID. Default is current post.
 * @return void
 */
function newspaperweb_the_tags($post_id = 0) {
    $tags = get_the_tags($post_id);
    if (!empty($tags)) {
        echo '<div class="post-tags mb-2">';
        foreach ($tags as $tag) {
            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="badge bg-light text-dark text-decoration-none me-1">';
            echo esc_html($tag->name);
            echo '</a>';
        }
        echo '</div>';
    }
}

/**
 * Display post meta (date, author, comments)
 * 
 * @param int $post_id Optional post ID. Default is current post.
 * @return void
 */
function newspaperweb_post_meta($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    echo '<div class="entry-meta text-muted small mb-3">';
    
    // Post date
    echo '<span class="posted-on me-3">';
    echo '<i class="bi bi-calendar"></i> ';
    echo get_the_date('', $post_id);
    echo '</span>';
    
    // Author
    echo '<span class="byline me-3">';
    echo '<i class="bi bi-person"></i> ';
    the_author_posts_link();
    echo '</span>';
    
    // Comment count
    if (comments_open($post_id)) {
        echo '<span class="comments-link">';
        echo '<i class="bi bi-chat"></i> ';
        comments_popup_link(
            __('No comments', 'newspaperweb'),
            __('1 comment', 'newspaperweb'),
            __('% comments', 'newspaperweb')
        );
        echo '</span>';
    }
    
    echo '</div>';
}

/**
 * Display featured image with responsive bootstrap classes
 * 
 * @param string $size The image size to use
 * @param int $post_id Optional post ID. Default is current post.
 * @return void
 */
function newspaperweb_the_post_thumbnail($size = 'large', $post_id = 0) {
    if (!has_post_thumbnail($post_id)) {
        return;
    }
    
    echo '<div class="featured-image mb-4">';
    the_post_thumbnail($size, array(
        'class' => 'img-fluid rounded',
        'alt'   => get_the_title($post_id)
    ));
    echo '</div>';
}

/**
 * Display a bootstrap styled pagination
 * 
 * @return void
 */
function newspaperweb_pagination() {
    echo '<div class="pagination-wrap my-4">';
    echo '<nav aria-label="' . esc_attr__('Posts Navigation', 'newspaperweb') . '">';
    
    the_posts_pagination(array(
        'mid_size'           => 2,
        'prev_text'          => '<i class="bi bi-chevron-left"></i> ' . __('Previous', 'newspaperweb'),
        'next_text'          => __('Next', 'newspaperweb') . ' <i class="bi bi-chevron-right"></i>',
        'screen_reader_text' => __('Posts Navigation', 'newspaperweb'),
        'class'              => 'pagination',
    ));
    
    echo '</nav>';
    echo '</div>';
}

/**
 * Display a bootstrap styled post navigation
 * 
 * @return void
 */
function newspaperweb_post_navigation() {
    echo '<div class="post-navigation mb-4">';
    echo '<hr class="my-4">';
    echo '<div class="row">';
    
    echo '<div class="col-6">';
    previous_post_link('<div class="nav-previous">%link</div>', '<i class="bi bi-arrow-left"></i> %title');
    echo '</div>';
    
    echo '<div class="col-6 text-end">';
    next_post_link('<div class="nav-next">%link</div>', '%title <i class="bi bi-arrow-right"></i>');
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display related posts based on categories
 * 
 * @param int $post_id Optional post ID. Default is current post.
 * @param int $number_of_posts Number of related posts to display
 * @return void
 */
function newspaperweb_related_posts($post_id = 0, $number_of_posts = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Get current post categories
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return;
    }
    
    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    // Query related posts
    $args = array(
        'category__in'       => $category_ids,
        'post__not_in'       => array($post_id),
        'posts_per_page'     => $number_of_posts,
        'ignore_sticky_posts'=> 1,
    );
    
    $related_query = new WP_Query($args);
    
    if ($related_query->have_posts()) {
        echo '<div class="related-posts my-4">';
        echo '<h3 class="section-title h4 mb-3">' . __('Related Posts', 'newspaperweb') . '</h3>';
        echo '<div class="row">';
        
        while ($related_query->have_posts()) {
            $related_query->the_post();
            
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card h-100">';
            
            if (has_post_thumbnail()) {
                echo '<a href="' . esc_url(get_permalink()) . '">';
                the_post_thumbnail('medium', array('class' => 'card-img-top'));
                echo '</a>';
            }
            
            echo '<div class="card-body">';
            echo '<h5 class="card-title"><a href="' . esc_url(get_permalink()) . '" class="text-decoration-none">' . get_the_title() . '</a></h5>';
            echo '<p class="card-text small text-muted">' . get_the_date() . '</p>';
            echo '</div>';
            
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    // Restore original post data
    wp_reset_postdata();
}

/**
 * Display a bootstrap styled breadcrumb
 * 
 * @return void
 */
function newspaperweb_breadcrumbs() {
    // Don't display on the homepage
    if (is_front_page()) {
        return;
    }
    
    echo '<nav aria-label="breadcrumb" class="mb-4">';
    echo '<ol class="breadcrumb">';
    
    // Home link
    echo '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . __('Home', 'newspaperweb') . '</a></li>';
    
    if (is_category() || is_single()) {
        // Category
        if (is_single()) {
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></li>';
                echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
            }
        } else {
            echo '<li class="breadcrumb-item active" aria-current="page">' . single_cat_title('', false) . '</li>';
        }
    } elseif (is_page()) {
        // Page
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . get_the_title($ancestor) . '</a></li>';
            }
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_tag()) {
        // Tag
        echo '<li class="breadcrumb-item active" aria-current="page">' . single_tag_title('', false) . '</li>';
    } elseif (is_author()) {
        // Author
        echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_author() . '</li>';
    } elseif (is_search()) {
        // Search
        echo '<li class="breadcrumb-item active" aria-current="page">' . __('Search Results', 'newspaperweb') . '</li>';
    } elseif (is_404()) {
        // 404
        echo '<li class="breadcrumb-item active" aria-current="page">' . __('404 Not Found', 'newspaperweb') . '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
} 