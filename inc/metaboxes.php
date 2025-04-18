<?php
/**
 * Funciones para los meta boxes personalizados
 *
 * @package NewspaperWeb
 */

// Salir si se accede directamente
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Clase para manejar los meta boxes del tema
 */
class NewspaperWeb_Meta_Boxes {

    /**
     * Constructor
     */
    public function __construct() {
        // Añadir meta box para la configuración de la barra lateral
        add_action('add_meta_boxes', array($this, 'add_sidebar_meta_box'));
        
        // Añadir meta box para la configuración del título
        add_action('add_meta_boxes', array($this, 'add_title_meta_box'));
        
        // Guardar los datos del meta box
        add_action('save_post', array($this, 'save_sidebar_meta_box_data'));
        add_action('save_post', array($this, 'save_title_meta_box_data'));
    }

    /**
     * Añadir meta box para la configuración de la barra lateral
     */
    public function add_sidebar_meta_box() {
        add_meta_box(
            'newspaperweb_sidebar_settings',
            __('Configuración de la Barra Lateral', 'newspaperweb'),
            array($this, 'sidebar_meta_box_callback'),
            array('page', 'post'), // Añadir a páginas y posts
            'side', // Posición en la columna lateral
            'default' // Prioridad
        );
    }

    /**
     * Añadir meta box para la configuración del título
     */
    public function add_title_meta_box() {
        add_meta_box(
            'newspaperweb_title_settings',
            __('Configuración del Título', 'newspaperweb'),
            array($this, 'title_meta_box_callback'),
            array('page', 'post'), // Añadir a páginas y posts
            'side', // Posición en la columna lateral
            'default' // Prioridad
        );
    }

    /**
     * Callback para mostrar el contenido del meta box de la barra lateral
     *
     * @param WP_Post $post El objeto post actual.
     */
    public function sidebar_meta_box_callback($post) {
        // Añadir un nonce para verificación
        wp_nonce_field('newspaperweb_sidebar_settings', 'newspaperweb_sidebar_settings_nonce');

        // Obtener el valor guardado actualmente
        $sidebar_enabled = get_post_meta($post->ID, '_newspaperweb_sidebar_enabled', true);
        
        // Si es nuevo post, establecer valor por defecto
        if ('' === $sidebar_enabled) {
            $sidebar_enabled = 'yes'; // Por defecto, mostrar barra lateral
        }
        ?>
        <p>
            <label>
                <input type="radio" name="newspaperweb_sidebar_enabled" value="yes" <?php checked($sidebar_enabled, 'yes'); ?>>
                <?php _e('Mostrar barra lateral', 'newspaperweb'); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="radio" name="newspaperweb_sidebar_enabled" value="no" <?php checked($sidebar_enabled, 'no'); ?>>
                <?php _e('Ocultar barra lateral (ancho completo)', 'newspaperweb'); ?>
            </label>
        </p>
        <p class="description">
            <?php _e('Selecciona si quieres mostrar u ocultar la barra lateral en esta página.', 'newspaperweb'); ?>
        </p>
        <?php
    }

    /**
     * Callback para mostrar el contenido del meta box del título
     *
     * @param WP_Post $post El objeto post actual.
     */
    public function title_meta_box_callback($post) {
        // Añadir un nonce para verificación
        wp_nonce_field('newspaperweb_title_settings', 'newspaperweb_title_settings_nonce');

        // Obtener el valor guardado actualmente
        $show_title = get_post_meta($post->ID, '_newspaperweb_show_title', true);
        
        // Si es nuevo post, establecer valor por defecto
        if ('' === $show_title) {
            $show_title = 'yes'; // Por defecto, mostrar título
        }
        ?>
        <p>
            <label>
                <input type="radio" name="newspaperweb_show_title" value="yes" <?php checked($show_title, 'yes'); ?>>
                <?php _e('Mostrar título', 'newspaperweb'); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="radio" name="newspaperweb_show_title" value="no" <?php checked($show_title, 'no'); ?>>
                <?php _e('Ocultar título', 'newspaperweb'); ?>
            </label>
        </p>
        <p class="description">
            <?php _e('Selecciona si quieres mostrar u ocultar el título en esta página.', 'newspaperweb'); ?>
        </p>
        <?php
    }

    /**
     * Guardar los datos del meta box de la barra lateral
     *
     * @param int $post_id El ID del post que se está guardando.
     */
    public function save_sidebar_meta_box_data($post_id) {
        // Verificar si debemos guardar los datos
        if (!isset($_POST['newspaperweb_sidebar_settings_nonce'])) {
            return;
        }

        // Verificar si el nonce es válido
        if (!wp_verify_nonce($_POST['newspaperweb_sidebar_settings_nonce'], 'newspaperweb_sidebar_settings')) {
            return;
        }

        // Si es un autoguardado, no hacer nada
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verificar permisos
        if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        // Guardar datos
        if (isset($_POST['newspaperweb_sidebar_enabled'])) {
            update_post_meta($post_id, '_newspaperweb_sidebar_enabled', sanitize_text_field($_POST['newspaperweb_sidebar_enabled']));
        }
    }

    /**
     * Guardar los datos del meta box del título
     *
     * @param int $post_id El ID del post que se está guardando.
     */
    public function save_title_meta_box_data($post_id) {
        // Verificar si debemos guardar los datos
        if (!isset($_POST['newspaperweb_title_settings_nonce'])) {
            return;
        }

        // Verificar si el nonce es válido
        if (!wp_verify_nonce($_POST['newspaperweb_title_settings_nonce'], 'newspaperweb_title_settings')) {
            return;
        }

        // Si es un autoguardado, no hacer nada
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verificar permisos
        if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        // Guardar datos
        if (isset($_POST['newspaperweb_show_title'])) {
            update_post_meta($post_id, '_newspaperweb_show_title', sanitize_text_field($_POST['newspaperweb_show_title']));
        }
    }
}

// Inicializar la clase
new NewspaperWeb_Meta_Boxes(); 