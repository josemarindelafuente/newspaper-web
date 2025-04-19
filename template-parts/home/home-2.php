<?php
/**
 * Template Name: Home Design 2 (Dark Mode)
 * Description: Diseño de página de inicio con 2 columnas para posts y una para widgets en modo oscuro
 */

get_header();

// Obtener las opciones del tema
$options = get_option('newspaperweb_options');
$link_color = isset($options['link_color']) ? $options['link_color'] : '#007bff';
$link_hover_color = isset($options['link_hover_color']) ? $options['link_hover_color'] : '#0056b3';
$posts_per_page = isset($options['home2_posts_per_page']) ? $options['home2_posts_per_page'] : 20;
$headings_font_size = isset($options['headings_font_size']) ? $options['headings_font_size'] : '24px';

// Obtener las categorías seleccionadas
$column1_categories = isset($options['home1_column1_categories']) ? $options['home1_column1_categories'] : array();
$column2_categories = isset($options['home1_column2_categories']) ? $options['home1_column2_categories'] : array();
?>

<style>
    :root {
        --link-color: <?php echo esc_attr($link_color); ?>;
        --link-hover-color: <?php echo esc_attr($link_hover_color); ?>;
        --background-color: #1a1a1a;
    }

    .header-ads-container {
        background-color: #1a1a1a;
    }





        
    .article-title {
        font-size: <?php echo esc_attr($headings_font_size); ?>;
    }
    .home-template-2 {
        background-color: #1a1a1a;
        color: #ffffff;
    }
    .home-article {
        background-color: #2d2d2d;
        border-bottom: 1px solid #3d3d3d;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .home-article:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    .article-title a {
        color: #ffffff;
        text-decoration: none;
    }
    .article-title a:hover {
        color: var(--link-hover-color);
    }
    .article-meta {
        color: #b3b3b3;
        font-size: 0.9em;
        margin-top: 10px;
    }
    .article-thumbnail img {
        border-radius: 4px;
        margin-top: 15px;
    }
    .sidebar .widget {
        background-color:rgb(71, 71, 71);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        color: #ffffff;
    }

    .sidebar ul li a {
        color: #ffffff;
    }

    
    .sidebar .widget-title {
        color: #ffffff;
        border-bottom: 2px solid #3d3d3d;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    .pagination .page-numbers {
        background-color: #2d2d2d;
        color: #ffffff;
        border: 1px solid #3d3d3d;
    }
    .pagination .page-numbers.current {
        background-color: var(--link-color);
        color: #ffffff;
    }
    .pagination .page-numbers:hover {
        background-color: var(--link-hover-color);
        color: #ffffff;
    }
</style>

<div class="container py-4 home-template-2">
    <div class="row">
        <!-- Primera columna -->
        <div class="col-md-4">
            <div class="row">
                <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => $posts_per_page,
                    'paged' => $paged,
                    'cat' => $column1_categories
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        ?>
                        <div class="col-12 mb-4">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('home-article'); ?>>
                                <header class="article-header">
                                    <h2 class="article-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <div class="article-meta">
                                        <span class="article-date"><?php echo get_the_date(); ?></span>                                        
                                    </div>
                                </header>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="article-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
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
                else :
                    ?>
                    <div class="col-12">
                        <p><?php _e('No se encontraron entradas.', 'newspaperweb'); ?></p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </div>

        <!-- Segunda columna -->
        <div class="col-md-4">
            <div class="row">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => $posts_per_page,
                    'paged' => $paged,
                    'cat' => $column2_categories
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        ?>
                        <div class="col-12 mb-4">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('home-article'); ?>>
                                <header class="article-header">
                                    <h2 class="article-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <div class="article-meta">
                                        <span class="article-date"><?php echo get_the_date(); ?></span>                                        
                                    </div>
                                </header>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="article-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
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
                else :
                    ?>
                    <div class="col-12">
                        <p><?php _e('No se encontraron entradas.', 'newspaperweb'); ?></p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </div>

        <!-- Tercera columna - Widgets -->
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