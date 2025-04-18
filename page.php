<?php
/**
 * The template for displaying all pages
 */

get_header();

// Obtener la plantilla seleccionada
$template = get_post_meta(get_the_ID(), '_wp_page_template', true);

// Si la página usa una de nuestras plantillas personalizadas
if (in_array($template, ['template-parts/home/home-1.php', 'template-parts/home/home-2.php'])) {
    // Cargar la plantilla correspondiente
    switch ($template) {
        case 'template-parts/home/home-1.php':
            get_template_part('template-parts/home/home-1');
            break;
        case 'template-parts/home/home-2.php':
            get_template_part('template-parts/home/home-2');
            break;
    }
} else {
    // Plantilla por defecto para páginas normales
    ?>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-8">
                <?php
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
                            <header class="entry-header mb-4">
                                <h1 class="card-title"><?php the_title(); ?></h1>
                            </header>

                            <div class="entry-content card-text">
                                <?php 
                                the_content();
                                
                                wp_link_pages(array(
                                    'before' => '<div class="page-links">' . __('Páginas:', 'newspaperweb'),
                                    'after'  => '</div>',
                                ));
                                ?>
                            </div>
                        </div>
                        
                        <?php if (comments_open() || get_comments_number()) : ?>
                            <div class="card-footer">
                                <?php comments_template(); ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="col-md-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
    <?php
}

get_footer(); 