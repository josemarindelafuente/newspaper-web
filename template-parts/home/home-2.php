<?php
/**
 * Template Name: Home Design 2
 * Description: Diseño alternativo de página de inicio con posts destacados y grid
 */

get_header();

// Obtener las opciones del tema
$options = get_option('newspaperweb_options');
$link_color = isset($options['link_color']) ? $options['link_color'] : '#007bff';
$link_hover_color = isset($options['link_hover_color']) ? $options['link_hover_color'] : '#0056b3';
$posts_per_page = isset($options['home2_posts_per_page']) ? $options['home2_posts_per_page'] : 20;
$headings_font_size = isset($options['headings_font_size']) ? $options['headings_font_size'] : '24px';

// Configurar la consulta de posts
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'paged' => $paged
);
$query = new WP_Query($args);
?>

<style>
    :root {
        --link-color: <?php echo esc_attr($link_color); ?>;
        --link-hover-color: <?php echo esc_attr($link_hover_color); ?>;
    }
    .card-title, .article-title {
        font-size: <?php echo esc_attr($headings_font_size); ?>;
    }
</style>

<div class="container py-4 home-template-2">
    <div class="row">
        <!-- Columna principal para posts -->
        <div class="col-md-8">
            <!-- Post destacado -->
            <?php
            $featured_args = array(
                'post_type' => 'post',
                'posts_per_page' => 1,
                'meta_key' => '_is_featured',
                'meta_value' => '1'
            );
            $featured_query = new WP_Query($featured_args);

            if ($featured_query->have_posts()) :
                while ($featured_query->have_posts()) : $featured_query->the_post();
                    ?>
                    <div class="featured-post mb-4">
                        <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large', array('class' => 'card-img-top')); ?>
                                </a>
                            <?php endif; ?>
                            <div class="card-body">
                                <h2 class="card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <div class="card-text">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </article>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>

            <!-- Grid de posts -->
            <div class="row">
                <?php
                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        ?>
                        <div class="col-md-6 mb-4 grid-post">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h3 class="card-title h5">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <div class="card-text">
                                        <?php the_excerpt(); ?>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php
                    endwhile;
                    ?>
                    <div class="col-12">
                        <?php
                        the_posts_pagination(array(
                            'mid_size' => 2,
                            'prev_text' => __('Anterior', 'newspaperweb'),
                            'next_text' => __('Siguiente', 'newspaperweb'),
                        ));
                        ?>
                    </div>
                    <?php
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>

        <!-- Columna de widgets -->
        <div class="col-md-4">
            <div class="sidebar">
                <?php if (is_active_sidebar('home-sidebar')) : ?>
                    <?php dynamic_sidebar('home-sidebar'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer(); 