<?php get_header(); ?>

<div class="container py-4">
    <div class="row">
        <?php
        // Comprobar si la barra lateral está activada para este post
        $sidebar_enabled = newspaperweb_is_sidebar_enabled();
        $content_class = $sidebar_enabled ? 'col-lg-8' : 'col-lg-12';
        ?>
        
        <div class="<?php echo esc_attr($content_class); ?>">
            <div class="site-content">
                <?php
                while (have_posts()) : the_post();
                    // Comprobar si se debe mostrar el título
                    $show_title = newspaperweb_show_title();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('card mb-4'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-img-top">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body card-body-single">
                            <?php if ($show_title) : ?>
                                <header class="entry-header mb-4">
                                    <h1 class="card-title"><?php the_title(); ?></h1>
                                </header>
                            <?php endif; ?>
                            
                            <div class="entry-meta text-muted small mb-3">
                                <span class="posted-on">
                                    <i class="bi bi-calendar"></i> <?php echo get_the_date(); ?>
                                </span>
                            </div>

                            <div class="entry-content card-text">
                                <?php 
                                the_content();
                                
                                // Si hay paginación dentro del contenido
                                wp_link_pages(array(
                                    'before' => '<div class="page-links">' . __('Páginas:', 'newspaperweb'),
                                    'after'  => '</div>',
                                ));
                                ?>
                            </div>
                        </div>
                        
                        <?php if (has_category() || has_tag()) : ?>
                            <div class="card-footer text-muted small">
                                <?php if (has_category()) : ?>
                                    <div class="cat-links mb-2">
                                        <i class="bi bi-folder"></i> <?php _e('Categorías:', 'newspaperweb'); ?> <?php the_category(', '); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (has_tag()) : ?>
                                    <div class="tags-links">
                                        <i class="bi bi-tags"></i> <?php _e('Etiquetas:', 'newspaperweb'); ?> <?php the_tags('', ', ', ''); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                    
                    <div class="post-navigation mb-4">
                        <div class="row">
                            <div class="col-6">
                                <?php previous_post_link('<div class="nav-previous">%link</div>', '<i class="bi bi-arrow-left"></i> %title'); ?>
                            </div>
                            <div class="col-6 text-end">
                                <?php next_post_link('<div class="nav-next">%link</div>', '%title <i class="bi bi-arrow-right"></i>'); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
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