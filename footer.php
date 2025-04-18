<?php
/**
 * The template for displaying the footer
 *
 * @package NewspaperWeb
 */

// Obtener las opciones del tema
$options = get_option('newspaperweb_options');
$footer_bg_color = isset($options['footer_bg_color']) ? $options['footer_bg_color'] : '#343a40';
$footer_text_color = isset($options['footer_text_color']) ? $options['footer_text_color'] : '#ffffff';
?>

<style>
    :root {
        --footer-bg-color: <?php echo esc_attr($footer_bg_color); ?>;
        --footer-text-color: <?php echo esc_attr($footer_text_color); ?>;
    }
</style>

</div><!-- cierre del contenedor principal que envuelve todo el contenido de la página -->

<footer id="colophon" class="site-footer py-4">
    <div class="container">
        <div class="row">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="col-md-6">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>

            <?php if (is_active_sidebar('footer-2')) : ?>
                <div class="col-md-6">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html> 