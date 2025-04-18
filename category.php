<?php
/**
 * The template for displaying Category Archive pages
 *
 * @package NewspaperWeb
 */

get_header();

// Obtener las opciones del tema
$options = get_option('newspaperweb_options');
$link_color = isset($options['link_color']) ? $options['link_color'] : '#007bff';
$link_hover_color = isset($options['link_hover_color']) ? $options['link_hover_color'] : '#0056b3';
$headings_font_size = isset($options['headings_font_size']) ? $options['headings_font_size'] : '24px';
$headings_font = isset($options['headings_font']) ? $options['headings_font'] : 'Arial, sans-serif';
$headings_weight = isset($options['headings_weight']) ? $options['headings_weight'] : '700';
?>

<style>
    :root {
        --link-color: <?php echo esc_attr($link_color); ?>;
        --link-hover-color: <?php echo esc_attr($link_hover_color); ?>;
    }
    .category-title, .card-title {
        font-size: <?php echo esc_attr($headings_font_size); ?>;
        font-family: <?php echo esc_attr($headings_font); ?>;
        font-weight: <?php echo esc_attr($headings_weight); ?>;
        color: var(--link-color);
    }
    .card-title a {
        color: var(--link-color);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .card-title a:hover {
        color: var(--link-hover-color);
    }
    .cat-links a, .tags-links a {
        color: var(--link-color);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .cat-links a:hover, .tags-links a:hover {
        color: var(--link-hover-color);
        text-decoration: underline;
    }
    .btn-primary {
        background-color: var(--link-color);
        border-color: var(--link-color);
    }
    .btn-primary:hover {
        background-color: var(--link-hover-color);
        border-color: var(--link-hover-color);
    }
</style>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="site-content">
                <header class="page-header mb-4">
                    <h1 class="category-title">
                        <?php
                        printf(
                            /* translators: %s: Category name. */
                            esc_html__('Categoría: %s', 'newspaperweb'),
                            '<span>' . single_cat_title('', false) . '</span>'
                        );
                        ?>
                    </h1>
                    <?php
                    // Mostrar la descripción de la categoría si existe
                    $category_description = category_description();
                    if (!empty($category_description)) {
                        echo '<div class="category-description lead mt-3">' . $category_description . '</div>';
                    }
                    ?>
                </header>

                <?php
                if (have_posts()) :
                    while (have_posts()) :
                        the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('card mb-4'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="card-img-top">
                                    <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <header class="entry-header">
                                    <h2 class="card-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <div class="entry-meta text-muted small mb-2">
                                        <span class="posted-on">
                                            <i class="bi bi-calendar"></i> <?php echo get_the_date(); ?>
                                        </span>
                                    </div>
                                </header>

                                <div class="entry-content card-text">
                                    <?php the_excerpt(); ?>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                                        <?php echo __('Leer más', 'newspaperweb'); ?> <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <?php if (has_category() || has_tag()) : ?>
                                <div class="card-footer text-muted small">
                                    <?php if (has_category()) : ?>
                                        <span class="cat-links">
                                            <i class="bi bi-folder"></i> <?php the_category(', '); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (has_tag()) : ?>
                                        <span class="tags-links ms-3">
                                            <i class="bi bi-tags"></i> <?php the_tags('', ', ', ''); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </article>
                        <?php
                    endwhile;

                    // Paginación
                    echo '<div class="pagination-wrap mb-4">';
                    echo '<nav aria-label="Paginación">';
                    echo paginate_links(array(
                        'prev_text' => '<i class="bi bi-chevron-left"></i> Anterior',
                        'next_text' => 'Siguiente <i class="bi bi-chevron-right"></i>',
                        'type'      => 'list',
                        'class'     => 'pagination',
                    ));
                    echo '</nav>';
                    echo '</div>';

                else :
                    echo '<div class="alert alert-info">' . __('No se encontraron entradas en esta categoría.', 'newspaperweb') . '</div>';
                endif;
                ?>
            </div>
        </div>
        
        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php
get_footer(); 