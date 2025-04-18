<?php get_header(); ?>

<div class="container py-4">
    <div class="row">
        <?php
        // Para la página principal y los archivos, siempre mostramos la barra lateral
        // pero en el futuro podríamos añadir una opción en el panel de administración
        $sidebar_enabled = true;
        $content_class = $sidebar_enabled ? 'col-lg-8' : 'col-lg-12';
        ?>
        
        <div class="<?php echo esc_attr($content_class); ?>">
            <div class="site-content">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
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
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a>
                                    </h2>
                                    <div class="entry-meta text-muted small mb-2">
                                        <span class="posted-on">
                                            <i class="bi bi-calendar"></i> <?php echo get_the_date(); ?>
                                        </span>
                                        <span class="byline ms-3">
                                            <i class="bi bi-person"></i> <?php the_author_posts_link(); ?>
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
                    echo '<div class="alert alert-info">No se encontraron entradas.</div>';
                endif;
                ?>
            </div>
        </div>
        
        <?php if ($sidebar_enabled) : ?>
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?> 