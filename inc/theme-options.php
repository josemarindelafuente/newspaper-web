<?php
/**
 * Funciones para el panel de opciones del tema
 *
 * @package NewspaperWeb
 */

// Salir si se accede directamente
if (!defined('ABSPATH')) {
    exit;
}

// Incluir funciones de sanitización de WordPress si no están disponibles
if (!function_exists('sanitize_hex_color')) {
    require_once ABSPATH . WPINC . '/class-wp-customize-manager.php';
}

if (!function_exists('esc_url_raw')) {
    require_once ABSPATH . WPINC . '/formatting.php';
}

if (!function_exists('absint')) {
    require_once ABSPATH . WPINC . '/formatting.php';
}

if (!function_exists('sanitize_text_field')) {
    require_once ABSPATH . WPINC . '/formatting.php';
}

/**
 * Sanitiza las opciones del tema antes de guardarlas
 * @param array $input Array de opciones a sanitizar
 * @return array Array de opciones sanitizadas
 */
function newspaperweb_sanitize_options($input) {
    // Obtener las opciones actuales
    $current_options = get_option('newspaperweb_options', array());
    $valid_input = $current_options; // Inicializar con las opciones actuales
    
    // Sanitizar colores
    if (isset($input['link_color'])) {
        $valid_input['link_color'] = sanitize_hex_color($input['link_color']);
    }
    if (isset($input['link_hover_color'])) {
        $valid_input['link_hover_color'] = sanitize_hex_color($input['link_hover_color']);
    }
    if (isset($input['footer_bg_color'])) {
        $valid_input['footer_bg_color'] = sanitize_hex_color($input['footer_bg_color']);
    }
    if (isset($input['footer_text_color'])) {
        $valid_input['footer_text_color'] = sanitize_hex_color($input['footer_text_color']);
    }
    
    // Sanitizar URLs
    if (isset($input['facebook_url'])) {
        $valid_input['facebook_url'] = esc_url_raw($input['facebook_url']);
    }
    if (isset($input['twitter_url'])) {
        $valid_input['twitter_url'] = esc_url_raw($input['twitter_url']);
    }
    if (isset($input['instagram_url'])) {
        $valid_input['instagram_url'] = esc_url_raw($input['instagram_url']);
    }
    if (isset($input['youtube_url'])) {
        $valid_input['youtube_url'] = esc_url_raw($input['youtube_url']);
    }
    if (isset($input['linkedin_url'])) {
        $valid_input['linkedin_url'] = esc_url_raw($input['linkedin_url']);
    }
    
    // Sanitizar opciones de posts
    if (isset($input['home1_posts_per_page'])) {
        $valid_input['home1_posts_per_page'] = absint($input['home1_posts_per_page']);
        if ($valid_input['home1_posts_per_page'] < 1) {
            $valid_input['home1_posts_per_page'] = 20;
        }
        if ($valid_input['home1_posts_per_page'] > 100) {
            $valid_input['home1_posts_per_page'] = 100;
        }
    }
    if (isset($input['home2_posts_per_page'])) {
        $valid_input['home2_posts_per_page'] = absint($input['home2_posts_per_page']);
        if ($valid_input['home2_posts_per_page'] < 1) {
            $valid_input['home2_posts_per_page'] = 20;
        }
        if ($valid_input['home2_posts_per_page'] > 100) {
            $valid_input['home2_posts_per_page'] = 100;
        }
    }
    
    // Sanitizar categorías para home-1
    if (isset($input['home1_column1_categories'])) {
        $valid_input['home1_column1_categories'] = array_map('absint', $input['home1_column1_categories']);
    }
    if (isset($input['home1_column2_categories'])) {
        $valid_input['home1_column2_categories'] = array_map('absint', $input['home1_column2_categories']);
    }
    
    // Sanitizar opciones de tipografía
    if (isset($input['body_font'])) {
        $valid_input['body_font'] = sanitize_text_field($input['body_font']);
    }
    if (isset($input['body_font_size'])) {
        $valid_input['body_font_size'] = sanitize_text_field($input['body_font_size']);
    }
    if (isset($input['headings_font'])) {
        $valid_input['headings_font'] = sanitize_text_field($input['headings_font']);
    }
    if (isset($input['headings_font_size'])) {
        $valid_input['headings_font_size'] = sanitize_text_field($input['headings_font_size']);
    }
    if (isset($input['headings_weight'])) {
        $valid_input['headings_weight'] = sanitize_text_field($input['headings_weight']);
    }
    if (isset($input['menu_font'])) {
        $valid_input['menu_font'] = sanitize_text_field($input['menu_font']);
    }
    if (isset($input['menu_font_size'])) {
        $valid_input['menu_font_size'] = sanitize_text_field($input['menu_font_size']);
    }
    if (isset($input['menu_text_transform'])) {
        $valid_input['menu_text_transform'] = sanitize_text_field($input['menu_text_transform']);
    }
    if (isset($input['menu_font_weight'])) {
        $valid_input['menu_font_weight'] = sanitize_text_field($input['menu_font_weight']);
    }
    
    // Sanitizar opciones de layout
    if (isset($input['menu_layout'])) {
        $valid_input['menu_layout'] = sanitize_text_field($input['menu_layout']);
    }
    
    // Sanitizar opciones del menú
    if (isset($input['primary_menu'])) {
        $valid_input['primary_menu'] = absint($input['primary_menu']);
    }
    if (isset($input['navbar_color'])) {
        $valid_input['navbar_color'] = sanitize_hex_color($input['navbar_color']);
    }
    if (isset($input['navbar_scheme'])) {
        $valid_input['navbar_scheme'] = sanitize_text_field($input['navbar_scheme']);
    }
    
    // Sanitizar opciones de logo y favicon
    if (isset($input['site_logo'])) {
        $valid_input['site_logo'] = esc_url_raw($input['site_logo']);
    }
    if (isset($input['site_favicon'])) {
        $valid_input['site_favicon'] = esc_url_raw($input['site_favicon']);
    }
    
    // Sanitizar código de Google Analytics
    if (isset($input['google_analytics_code'])) {
        $valid_input['google_analytics_code'] = wp_kses($input['google_analytics_code'], array(
            'script' => array(
                'async' => array(),
                'src' => array(),
                'type' => array()
            )
        ));
    }
    
    return $valid_input;
}

/**
 * Clase de las opciones del tema
 */
class NewspaperWeb_Theme_Options {

    /**
     * Pestañas de opciones
     *
     * @var array
     */
    private $tabs = array();

    /**
     * Pestaña activa
     *
     * @var string
     */
    private $active_tab = '';

    /**
     * Constructor
     */
    public function __construct() {
        // Configurar pestañas
        $this->setup_tabs();
        
        // Añadir el menú de administración
        add_action('admin_menu', array($this, 'add_theme_options_page'));
        
        // Registrar las opciones
        add_action('admin_init', array($this, 'register_settings'));
        
        // Cargar scripts para el panel de opciones
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Añadir estilos personalizados basados en las opciones
        add_action('wp_head', array($this, 'output_custom_styles'));
    }

    /**
     * Configurar las pestañas de opciones
     */
    private function setup_tabs() {
        $this->tabs = array(
            'general' => __('General', 'newspaperweb'),
            'typography' => __('Tipografía', 'newspaperweb'),
            'colors' => __('Colores', 'newspaperweb'),
            'layout' => __('Diseño', 'newspaperweb'),
            'social' => __('Redes Sociales', 'newspaperweb'),
            'posts' => __('Configuración de Posts', 'newspaperweb'),
            'analytics' => __('Google Analytics', 'newspaperweb')
        );
        
        // Establecer la pestaña activa desde la URL o usar 'general' por defecto
        $this->active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
        
        // Si la pestaña no es válida, usar 'general'
        if (!array_key_exists($this->active_tab, $this->tabs)) {
            $this->active_tab = 'general';
        }
    }

    /**
     * Añadir página de opciones del tema
     */
    public function add_theme_options_page() {
        add_theme_page(
            __('Opciones de Newspaper Web', 'newspaperweb'),
            __('Opciones del tema', 'newspaperweb'),
            'manage_options',
            'newspaperweb-options',
            array($this, 'theme_options_page_content')
        );
    }

    /**
     * Registrar configuraciones
     */
    public function register_settings() {
        // Registrar grupo de opciones
        register_setting(
            'newspaperweb_options', 
            'newspaperweb_options',
            array(
                'sanitize_callback' => 'newspaperweb_sanitize_options',
                'default' => array(
                    'link_color' => '#007bff',
                    'link_hover_color' => '#0056b3',
                    'home1_posts_per_page' => 20,
                    'home2_posts_per_page' => 20,
                    'body_font' => 'Arial, sans-serif',
                    'body_font_size' => '16px',
                    'headings_font' => 'Arial, sans-serif',
                    'headings_font_size' => '24px',
                    'headings_weight' => '700',
                    'menu_font' => 'Arial, sans-serif',
                    'menu_font_size' => '14px',
                    'menu_text_transform' => 'none',
                    'menu_font_weight' => '400',
                    'menu_layout' => 'classic',
                    'google_analytics_code' => ''
                )
            )
        );

        // Registrar secciones y campos según la pestaña activa
        switch ($this->active_tab) {
            case 'general':
                $this->register_general_settings();
                break;
                
            case 'typography':
                $this->register_typography_settings();
                break;
                
            case 'colors':
                $this->register_color_settings();
                break;
                
            case 'layout':
                $this->register_layout_settings();
                break;
                
            case 'social':
                $this->register_social_settings();
                break;
                
            case 'posts':
                $this->register_posts_settings();
                break;
                
            case 'analytics':
                $this->register_analytics_settings();
                break;
        }
    }

    /**
     * Registrar configuraciones generales
     */
    private function register_general_settings() {
        // Sección para el logo del sitio
        add_settings_section(
            'newspaperweb_logo_section',
            __('Logo del sitio', 'newspaperweb'),
            array($this, 'logo_section_callback'),
            'newspaperweb-options'
        );

        // Campo para subir el logo
        add_settings_field(
            'site_logo',
            __('Logo para la barra de navegación', 'newspaperweb'),
            array($this, 'site_logo_callback'),
            'newspaperweb-options',
            'newspaperweb_logo_section'
        );

        // Sección de menús
        add_settings_section(
            'newspaperweb_menus_section',
            __('Configuración de menús', 'newspaperweb'),
            array($this, 'menus_section_callback'),
            'newspaperweb-options'
        );

        // Campo para seleccionar el menú principal
        add_settings_field(
            'primary_menu',
            __('Menú Principal', 'newspaperweb'),
            array($this, 'primary_menu_callback'),
            'newspaperweb-options',
            'newspaperweb_menus_section'
        );

        // Campo para seleccionar el diseño del menú
        add_settings_field(
            'menu_layout',
            __('Diseño del Menú', 'newspaperweb'),
            array($this, 'menu_layout_callback'),
            'newspaperweb-options',
            'newspaperweb_menus_section'
        );
        
        // Favicon
        add_settings_field(
            'site_favicon',
            __('Favicon', 'newspaperweb'),
            array($this, 'site_favicon_callback'),
            'newspaperweb-options',
            'newspaperweb_logo_section'
        );
    }

    /**
     * Registrar configuraciones de tipografía
     */
    private function register_typography_settings() {
        // Sección para las fuentes generales
        add_settings_section(
            'newspaperweb_general_typography_section',
            __('Tipografía General', 'newspaperweb'),
            array($this, 'general_typography_section_callback'),
            'newspaperweb-options'
        );

        // Campo para la fuente del cuerpo
        add_settings_field(
            'body_font',
            __('Fuente del cuerpo', 'newspaperweb'),
            array($this, 'body_font_callback'),
            'newspaperweb-options',
            'newspaperweb_general_typography_section'
        );

        // Campo para el tamaño de fuente del cuerpo
        add_settings_field(
            'body_font_size',
            __('Tamaño de fuente del cuerpo', 'newspaperweb'),
            array($this, 'body_font_size_callback'),
            'newspaperweb-options',
            'newspaperweb_general_typography_section'
        );
        
        // Sección para las fuentes de títulos
        add_settings_section(
            'newspaperweb_headings_typography_section',
            __('Tipografía de Títulos', 'newspaperweb'),
            array($this, 'headings_typography_section_callback'),
            'newspaperweb-options'
        );

        // Campo para la fuente de títulos
        add_settings_field(
            'headings_font',
            __('Fuente de títulos', 'newspaperweb'),
            array($this, 'headings_font_callback'),
            'newspaperweb-options',
            'newspaperweb_headings_typography_section'
        );

        // Campo para el tamaño de fuente de títulos
        add_settings_field(
            'headings_font_size',
            __('Tamaño de fuente de títulos', 'newspaperweb'),
            array($this, 'headings_font_size_callback'),
            'newspaperweb-options',
            'newspaperweb_headings_typography_section'
        );

        // Campo para el peso de los títulos
        add_settings_field(
            'headings_weight',
            __('Peso de los títulos', 'newspaperweb'),
            array($this, 'headings_weight_callback'),
            'newspaperweb-options',
            'newspaperweb_headings_typography_section'
        );
        
        // Sección para fuentes del menú
        add_settings_section(
            'newspaperweb_menu_typography_section',
            __('Tipografía del Menú', 'newspaperweb'),
            array($this, 'menu_typography_section_callback'),
            'newspaperweb-options'
        );

        // Campo para la fuente del menú principal
        add_settings_field(
            'menu_font',
            __('Fuente del menú', 'newspaperweb'),
            array($this, 'menu_font_callback'),
            'newspaperweb-options',
            'newspaperweb_menu_typography_section'
        );

        // Campo para el tamaño de fuente del menú
        add_settings_field(
            'menu_font_size',
            __('Tamaño de fuente del menú', 'newspaperweb'),
            array($this, 'menu_font_size_callback'),
            'newspaperweb-options',
            'newspaperweb_menu_typography_section'
        );
        
        // Transformación de texto del menú
        add_settings_field(
            'menu_text_transform',
            __('Transformación de texto del menú', 'newspaperweb'),
            array($this, 'menu_text_transform_callback'),
            'newspaperweb-options',
            'newspaperweb_menu_typography_section'
        );

        // Agregar campo para el peso de la fuente del menú
        add_settings_field(
            'menu_font_weight',
            __('Peso de la fuente del menú', 'newspaperweb'),
            array($this, 'menu_font_weight_callback'),
            'newspaperweb-options',
            'newspaperweb_menu_typography_section'
        );
    }

    /**
     * Registrar configuraciones de colores
     */
    private function register_color_settings() {
        // Sección para los colores del tema
        add_settings_section(
            'newspaperweb_colors_section',
            __('Colores del tema', 'newspaperweb'),
            array($this, 'colors_section_callback'),
            'newspaperweb-options'
        );

        // Campo para seleccionar el color del navbar
        add_settings_field(
            'navbar_color',
            __('Color de la barra de navegación', 'newspaperweb'),
            array($this, 'navbar_color_callback'),
            'newspaperweb-options',
            'newspaperweb_colors_section'
        );

        // Campo para seleccionar el esquema de color del navbar (claro u oscuro)
        add_settings_field(
            'navbar_scheme',
            __('Esquema de color de la barra de navegación', 'newspaperweb'),
            array($this, 'navbar_scheme_callback'),
            'newspaperweb-options',
            'newspaperweb_colors_section'
        );
        
        // Color primario
        add_settings_field(
            'primary_color',
            __('Color primario', 'newspaperweb'),
            array($this, 'primary_color_callback'),
            'newspaperweb-options',
            'newspaperweb_colors_section'
        );
        
        // Color secundario
        add_settings_field(
            'secondary_color',
            __('Color secundario', 'newspaperweb'),
            array($this, 'secondary_color_callback'),
            'newspaperweb-options',
            'newspaperweb_colors_section'
        );
        
        // Color de enlaces
        add_settings_field(
            'link_color',
            __('Color de enlaces', 'newspaperweb'),
            array($this, 'link_color_callback'),
            'newspaperweb-options',
            'newspaperweb_colors_section'
        );
        
        // Color de enlaces al pasar el cursor
        add_settings_field(
            'link_hover_color',
            __('Color de enlaces al pasar el cursor', 'newspaperweb'),
            array($this, 'link_hover_color_callback'),
            'newspaperweb-options',
            'newspaperweb_colors_section'
        );
        
        // Color de fondo del pie de página
        add_settings_field(
            'footer_bg_color',
            __('Color de fondo del pie de página', 'newspaperweb'),
            array($this, 'footer_bg_color_callback'),
            'newspaperweb-options',
            'newspaperweb_colors_section'
        );
        
        // Color de texto del pie de página
        add_settings_field(
            'footer_text_color',
            __('Color de texto del pie de página', 'newspaperweb'),
            array($this, 'footer_text_color_callback'),
            'newspaperweb-options',
            'newspaperweb_colors_section'
        );
    }

    /**
     * Registrar configuraciones de diseño
     */
    private function register_layout_settings() {
        // Sección para el diseño del contenido
        add_settings_section(
            'newspaperweb_layout_section',
            __('Diseño del contenido', 'newspaperweb'),
            array($this, 'layout_section_callback'),
            'newspaperweb-options'
        );

        // Campo para la posición de la barra lateral
        add_settings_field(
            'sidebar_position',
            __('Posición de la barra lateral', 'newspaperweb'),
            array($this, 'sidebar_position_callback'),
            'newspaperweb-options',
            'newspaperweb_layout_section'
        );
        
        // Diseño predeterminado para entradas
        add_settings_field(
            'post_layout',
            __('Diseño predeterminado para entradas', 'newspaperweb'),
            array($this, 'post_layout_callback'),
            'newspaperweb-options',
            'newspaperweb_layout_section'
        );
        
        // Ancho del contenedor
        add_settings_field(
            'container_width',
            __('Ancho del contenedor', 'newspaperweb'),
            array($this, 'container_width_callback'),
            'newspaperweb-options',
            'newspaperweb_layout_section'
        );
        
        // Espaciado interno (padding)
        add_settings_field(
            'content_padding',
            __('Espaciado interno del contenido', 'newspaperweb'),
            array($this, 'content_padding_callback'),
            'newspaperweb-options',
            'newspaperweb_layout_section'
        );
    }

    /**
     * Registrar configuraciones de redes sociales
     */
    private function register_social_settings() {
        // Sección para redes sociales
        add_settings_section(
            'newspaperweb_social_section',
            __('Redes Sociales', 'newspaperweb'),
            array($this, 'social_section_callback'),
            'newspaperweb-options'
        );

        // Campo para Facebook
        add_settings_field(
            'facebook_url',
            __('URL de Facebook', 'newspaperweb'),
            array($this, 'facebook_url_callback'),
            'newspaperweb-options',
            'newspaperweb_social_section'
        );
        
        // Campo para Twitter/X
        add_settings_field(
            'twitter_url',
            __('URL de Twitter/X', 'newspaperweb'),
            array($this, 'twitter_url_callback'),
            'newspaperweb-options',
            'newspaperweb_social_section'
        );
        
        // Campo para Instagram
        add_settings_field(
            'instagram_url',
            __('URL de Instagram', 'newspaperweb'),
            array($this, 'instagram_url_callback'),
            'newspaperweb-options',
            'newspaperweb_social_section'
        );
        
        // Campo para YouTube
        add_settings_field(
            'youtube_url',
            __('URL de YouTube', 'newspaperweb'),
            array($this, 'youtube_url_callback'),
            'newspaperweb-options',
            'newspaperweb_social_section'
        );
        
        // Campo para LinkedIn
        add_settings_field(
            'linkedin_url',
            __('URL de LinkedIn', 'newspaperweb'),
            array($this, 'linkedin_url_callback'),
            'newspaperweb-options',
            'newspaperweb_social_section'
        );
    }

    /**
     * Registrar configuraciones de posts
     */
    private function register_posts_settings() {
        add_settings_section(
            'newspaperweb_posts_section',
            __('Configuración de Posts', 'newspaperweb'),
            array($this, 'posts_section_callback'),
            'newspaperweb-options'
        );

        // Número de posts por página para home-1
        add_settings_field(
            'home1_posts_per_page',
            __('Número de posts por página (Home 1)', 'newspaperweb'),
            array($this, 'home1_posts_per_page_callback'),
            'newspaperweb-options',
            'newspaperweb_posts_section'
        );

        // Categorías para la primera columna de home-1
        add_settings_field(
            'home1_column1_categories',
            __('Categorías para la primera columna (Home 1)', 'newspaperweb'),
            array($this, 'home1_column1_categories_callback'),
            'newspaperweb-options',
            'newspaperweb_posts_section'
        );

        // Categorías para la segunda columna de home-1
        add_settings_field(
            'home1_column2_categories',
            __('Categorías para la segunda columna (Home 1)', 'newspaperweb'),
            array($this, 'home1_column2_categories_callback'),
            'newspaperweb-options',
            'newspaperweb_posts_section'
        );

        // Número de posts por página para home-2
        add_settings_field(
            'home2_posts_per_page',
            __('Número de posts por página (Home 2)', 'newspaperweb'),
            array($this, 'home2_posts_per_page_callback'),
            'newspaperweb-options',
            'newspaperweb_posts_section'
        );
    }

    /**
     * Registrar configuraciones de Google Analytics
     */
    private function register_analytics_settings() {
        // Sección para Google Analytics
        add_settings_section(
            'newspaperweb_analytics_section',
            __('Configuración de Google Analytics', 'newspaperweb'),
            array($this, 'analytics_section_callback'),
            'newspaperweb-options'
        );

        // Campo para el código de Google Analytics
        add_settings_field(
            'google_analytics_code',
            __('Código de Google Analytics', 'newspaperweb'),
            array($this, 'google_analytics_code_callback'),
            'newspaperweb-options',
            'newspaperweb_analytics_section'
        );
    }

    /**
     * Callback para la sección de Google Analytics
     */
    public function analytics_section_callback() {
        echo '<p>' . __('Pega aquí el código de seguimiento de Google Analytics. Este código se insertará automáticamente en el encabezado de tu sitio.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el campo de código de Google Analytics
     */
    public function google_analytics_code_callback() {
        $options = get_option('newspaperweb_options');
        $code = isset($options['google_analytics_code']) ? $options['google_analytics_code'] : '';
        ?>
        <textarea id="google_analytics_code" name="newspaperweb_options[google_analytics_code]" rows="10" cols="50" class="large-text code"><?php echo esc_textarea($code); ?></textarea>
        <p class="description"><?php _e('Pega el código de seguimiento completo de Google Analytics (incluyendo las etiquetas &lt;script&gt;).', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Cargar scripts para el panel de administración
     * 
     * @param string $hook La página actual del admin
     */
    public function enqueue_admin_scripts($hook) {
        // Cargar scripts solo en la página de opciones del tema
        if ('appearance_page_newspaperweb-options' != $hook) {
            return;
        }
        
        // Registrar y encolar el script de media uploader
        wp_enqueue_media();
        
        // Encolar scripts de admin de WordPress
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-tabs');
        
        // Cargar el color picker de WordPress
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Cargar Select2 para mejorar los selectores
        wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
        wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0-rc.0', true);
        
        // Script personalizado para manejar el selector de medios
        wp_enqueue_script(
            'newspaperweb-admin-script',
            get_template_directory_uri() . '/js/admin.js',
            array('jquery', 'wp-color-picker', 'select2', 'jquery-ui-tabs'),
            '1.0.0',
            true
        );
        
        // Localizar script con traducciones
        wp_localize_script(
            'newspaperweb-admin-script',
            'newspaperweb_admin',
            array(
                'logo_title' => __('Seleccionar o subir logo', 'newspaperweb'),
                'logo_alt' => __('Logo del sitio', 'newspaperweb'),
                'remove_logo' => __('Eliminar Logo', 'newspaperweb'),
                'favicon_title' => __('Seleccionar o subir favicon', 'newspaperweb'),
                'favicon_alt' => __('Favicon del sitio', 'newspaperweb'),
                'remove_favicon' => __('Eliminar Favicon', 'newspaperweb')
            )
        );
        
        // Estilos personalizados para la página de opciones
        wp_add_inline_style('wp-admin', '
            .newspaperweb-options-tabs {
                margin-top: 20px;
            }
            .nav-tab-wrapper {
                margin-bottom: 20px;
            }
            .font-preview {
                margin-top: 10px;
                padding: 10px;
                border: 1px solid #ddd;
                background-color: #f9f9f9;
                border-radius: 3px;
            }
            .logo-preview, .favicon-preview {
                margin: 10px 0;
                padding: 10px;
                border: 1px solid #ddd;
                display: inline-block;
                background-color: #f9f9f9;
            }
            #remove_logo_button, #remove_favicon_button {
                margin-left: 10px;
            }
            .color-picker {
                width: 80px;
            }
        ');
    }

    /**
     * Contenido de la página de opciones
     */
    public function theme_options_page_content() {
        // Verificar permisos
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="newspaperweb-options-tabs">
                <h2 class="nav-tab-wrapper">
                    <?php
                    // Mostrar las pestañas
                    foreach ($this->tabs as $tab_id => $tab_name) {
                        $active_class = ($this->active_tab === $tab_id) ? 'nav-tab-active' : '';
                        printf(
                            '<a href="?page=newspaperweb-options&tab=%s" class="nav-tab %s">%s</a>',
                            esc_attr($tab_id),
                            esc_attr($active_class),
                            esc_html($tab_name)
                        );
                    }
                    ?>
                </h2>
                
                <div class="tab-content">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields('newspaperweb_options');
                        do_settings_sections('newspaperweb-options');
                        submit_button();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Callback para la sección del logo
     */
    public function logo_section_callback() {
        echo '<p>' . __('Configura el logo y favicon de tu sitio.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para la sección de colores
     */
    public function colors_section_callback() {
        echo '<p>' . __('Configura los colores de tu sitio.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el campo del color del navbar
     */
    public function navbar_color_callback() {
        $options = get_option('newspaperweb_options');
        $navbar_color = isset($options['navbar_color']) ? $options['navbar_color'] : '#ffffff'; // Blanco por defecto
        ?>
        <input type="text" id="newspaperweb_navbar_color" name="newspaperweb_options[navbar_color]" value="<?php echo esc_attr($navbar_color); ?>" class="color-picker" />
        <p class="description">
            <?php _e('Selecciona el color de fondo de la barra de navegación.', 'newspaperweb'); ?>
        </p>
        <?php
    }

    /**
     * Callback para el campo del esquema de color del navbar
     */
    public function navbar_scheme_callback() {
        $options = get_option('newspaperweb_options');
        $navbar_scheme = isset($options['navbar_scheme']) ? $options['navbar_scheme'] : 'light'; // Claro por defecto
        ?>
        <select id="newspaperweb_navbar_scheme" name="newspaperweb_options[navbar_scheme]">
            <option value="light" <?php selected($navbar_scheme, 'light'); ?>><?php _e('Claro (para fondos claros)', 'newspaperweb'); ?></option>
            <option value="dark" <?php selected($navbar_scheme, 'dark'); ?>><?php _e('Oscuro (para fondos oscuros)', 'newspaperweb'); ?></option>
        </select>
        <p class="description">
            <?php _e('Selecciona el esquema de color para los textos y botones de la barra de navegación.', 'newspaperweb'); ?>
        </p>
        <?php
    }

    /**
     * Callback para el color primario
     */
    public function primary_color_callback() {
        $options = get_option('newspaperweb_options');
        $primary_color = isset($options['primary_color']) ? $options['primary_color'] : '#0d6efd'; // Azul Bootstrap por defecto
        ?>
        <input type="text" id="primary_color" name="newspaperweb_options[primary_color]" value="<?php echo esc_attr($primary_color); ?>" class="color-picker" />
        <p class="description">
            <?php _e('Color principal del tema, usado para botones primarios y elementos destacados.', 'newspaperweb'); ?>
        </p>
        <?php
    }
    
    /**
     * Callback para el color secundario
     */
    public function secondary_color_callback() {
        $options = get_option('newspaperweb_options');
        $secondary_color = isset($options['secondary_color']) ? $options['secondary_color'] : '#6c757d'; // Gris Bootstrap por defecto
        ?>
        <input type="text" id="secondary_color" name="newspaperweb_options[secondary_color]" value="<?php echo esc_attr($secondary_color); ?>" class="color-picker" />
        <p class="description">
            <?php _e('Color secundario del tema, usado para botones secundarios y elementos de apoyo.', 'newspaperweb'); ?>
        </p>
        <?php
    }
    
    /**
     * Callback para el color de enlaces
     */
    public function link_color_callback() {
        $options = get_option('newspaperweb_options');
        $link_color = isset($options['link_color']) ? $options['link_color'] : '#0d6efd'; // Azul Bootstrap por defecto
        ?>
        <input type="text" id="link_color" name="newspaperweb_options[link_color]" value="<?php echo esc_attr($link_color); ?>" class="color-picker" />
        <p class="description">
            <?php _e('Color de los enlaces en todo el sitio.', 'newspaperweb'); ?>
        </p>
        <?php
    }
    
    /**
     * Callback para el color de enlaces al pasar el cursor
     */
    public function link_hover_color_callback() {
        $options = get_option('newspaperweb_options');
        $link_hover_color = isset($options['link_hover_color']) ? $options['link_hover_color'] : '#0a58ca'; // Azul oscuro Bootstrap por defecto
        ?>
        <input type="text" id="link_hover_color" name="newspaperweb_options[link_hover_color]" value="<?php echo esc_attr($link_hover_color); ?>" class="color-picker" />
        <p class="description">
            <?php _e('Color de los enlaces al pasar el cursor sobre ellos.', 'newspaperweb'); ?>
        </p>
        <?php
    }
    
    /**
     * Callback para el color de fondo del pie de página
     */
    public function footer_bg_color_callback() {
        $options = get_option('newspaperweb_options');
        $footer_bg_color = isset($options['footer_bg_color']) ? $options['footer_bg_color'] : '#212529'; // Negro Bootstrap por defecto
        ?>
        <input type="text" id="footer_bg_color" name="newspaperweb_options[footer_bg_color]" value="<?php echo esc_attr($footer_bg_color); ?>" class="color-picker" />
        <p class="description">
            <?php _e('Color de fondo del pie de página.', 'newspaperweb'); ?>
        </p>
        <?php
    }
    
    /**
     * Callback para el color de texto del pie de página
     */
    public function footer_text_color_callback() {
        $options = get_option('newspaperweb_options');
        $footer_text_color = isset($options['footer_text_color']) ? $options['footer_text_color'] : '#f8f9fa'; // Blanco Bootstrap por defecto
        ?>
        <input type="text" id="footer_text_color" name="newspaperweb_options[footer_text_color]" value="<?php echo esc_attr($footer_text_color); ?>" class="color-picker" />
        <p class="description">
            <?php _e('Color del texto en el pie de página.', 'newspaperweb'); ?>
        </p>
        <?php
    }

    /**
     * Callback para la sección de diseño
     */
    public function layout_section_callback() {
        echo '<p>' . __('Configura el diseño y estructura del sitio.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para la posición de la barra lateral
     */
    public function sidebar_position_callback() {
        $options = get_option('newspaperweb_options');
        $position = isset($options['sidebar_position']) ? $options['sidebar_position'] : 'right';
        ?>
        <select id="sidebar_position" name="newspaperweb_options[sidebar_position]">
            <option value="right" <?php selected($position, 'right'); ?>><?php _e('Derecha', 'newspaperweb'); ?></option>
            <option value="left" <?php selected($position, 'left'); ?>><?php _e('Izquierda', 'newspaperweb'); ?></option>
        </select>
        <p class="description"><?php _e('Selecciona la posición predeterminada de la barra lateral.', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Callback para el diseño predeterminado de entradas
     */
    public function post_layout_callback() {
        $options = get_option('newspaperweb_options');
        $layout = isset($options['post_layout']) ? $options['post_layout'] : 'standard';
        ?>
        <select id="post_layout" name="newspaperweb_options[post_layout]">
            <option value="standard" <?php selected($layout, 'standard'); ?>><?php _e('Estándar', 'newspaperweb'); ?></option>
            <option value="grid" <?php selected($layout, 'grid'); ?>><?php _e('Cuadrícula', 'newspaperweb'); ?></option>
            <option value="list" <?php selected($layout, 'list'); ?>><?php _e('Lista', 'newspaperweb'); ?></option>
        </select>
        <p class="description"><?php _e('Selecciona el diseño predeterminado para mostrar entradas en la página principal y archivos.', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Callback para el ancho del contenedor
     */
    public function container_width_callback() {
        $options = get_option('newspaperweb_options');
        $width = isset($options['container_width']) ? $options['container_width'] : 1200;
        ?>
        <input type="number" id="container_width" name="newspaperweb_options[container_width]" value="<?php echo esc_attr($width); ?>" min="960" max="1920" step="10" />
        <span class="description"><?php _e('px', 'newspaperweb'); ?></span>
        <p class="description"><?php _e('Ancho máximo del contenedor en píxeles (entre 960px y 1920px).', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Callback para el espaciado interno del contenido
     */
    public function content_padding_callback() {
        $options = get_option('newspaperweb_options');
        $padding = isset($options['content_padding']) ? $options['content_padding'] : '20px';
        ?>
        <input type="text" id="content_padding" name="newspaperweb_options[content_padding]" value="<?php echo esc_attr($padding); ?>" />
        <p class="description"><?php _e('Espaciado interno del contenido (ej: 20px, 1.5rem, etc).', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Callback para la sección de redes sociales
     */
    public function social_section_callback() {
        echo '<p>' . __('Configura los enlaces a tus perfiles de redes sociales.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para Facebook
     */
    public function facebook_url_callback() {
        $options = get_option('newspaperweb_options');
        $url = isset($options['facebook_url']) ? $options['facebook_url'] : '';
        ?>
        <input type="url" id="facebook_url" name="newspaperweb_options[facebook_url]" value="<?php echo esc_attr($url); ?>" class="regular-text" />
        <p class="description"><?php _e('URL de tu página de Facebook.', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Callback para Twitter/X
     */
    public function twitter_url_callback() {
        $options = get_option('newspaperweb_options');
        $url = isset($options['twitter_url']) ? $options['twitter_url'] : '';
        ?>
        <input type="url" id="twitter_url" name="newspaperweb_options[twitter_url]" value="<?php echo esc_attr($url); ?>" class="regular-text" />
        <p class="description"><?php _e('URL de tu perfil de Twitter/X.', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Callback para Instagram
     */
    public function instagram_url_callback() {
        $options = get_option('newspaperweb_options');
        $url = isset($options['instagram_url']) ? $options['instagram_url'] : '';
        ?>
        <input type="url" id="instagram_url" name="newspaperweb_options[instagram_url]" value="<?php echo esc_attr($url); ?>" class="regular-text" />
        <p class="description"><?php _e('URL de tu perfil de Instagram.', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Callback para YouTube
     */
    public function youtube_url_callback() {
        $options = get_option('newspaperweb_options');
        $url = isset($options['youtube_url']) ? $options['youtube_url'] : '';
        ?>
        <input type="url" id="youtube_url" name="newspaperweb_options[youtube_url]" value="<?php echo esc_attr($url); ?>" class="regular-text" />
        <p class="description"><?php _e('URL de tu canal de YouTube.', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Callback para LinkedIn
     */
    public function linkedin_url_callback() {
        $options = get_option('newspaperweb_options');
        $url = isset($options['linkedin_url']) ? $options['linkedin_url'] : '';
        ?>
        <input type="url" id="linkedin_url" name="newspaperweb_options[linkedin_url]" value="<?php echo esc_attr($url); ?>" class="regular-text" />
        <p class="description"><?php _e('URL de tu perfil o página de LinkedIn.', 'newspaperweb'); ?></p>
        <?php
    }

    /**
     * Callback para el campo del logo
     */
    public function site_logo_callback() {
        $options = get_option('newspaperweb_options');
        $logo_url = isset($options['site_logo']) ? $options['site_logo'] : '';
        ?>
        <div class="newspaperweb-logo-uploader">
            <input type="hidden" id="newspaperweb_site_logo" name="newspaperweb_options[site_logo]" value="<?php echo esc_attr($logo_url); ?>" />
            
            <div class="logo-preview-wrapper">
                <?php if (!empty($logo_url)) : ?>
                    <div class="logo-preview">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php _e('Logo del sitio', 'newspaperweb'); ?>" style="max-width: 200px; height: auto;" />
                    </div>
                <?php endif; ?>
            </div>
            
            <input type="button" id="upload_logo_button" class="button" value="<?php _e('Subir Logo', 'newspaperweb'); ?>" />
            
            <?php if (!empty($logo_url)) : ?>
                <input type="button" id="remove_logo_button" class="button" value="<?php _e('Eliminar Logo', 'newspaperweb'); ?>" />
            <?php endif; ?>
            
            <p class="description">
                <?php _e('Tamaño recomendado: 200x50 píxeles. El logo reemplazará al nombre y descripción del sitio.', 'newspaperweb'); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Callback para el campo del favicon
     */
    public function site_favicon_callback() {
        $options = get_option('newspaperweb_options');
        $favicon_url = isset($options['site_favicon']) ? $options['site_favicon'] : '';
        ?>
        <div class="newspaperweb-favicon-uploader">
            <input type="hidden" id="newspaperweb_site_favicon" name="newspaperweb_options[site_favicon]" value="<?php echo esc_attr($favicon_url); ?>" />
            
            <div class="favicon-preview-wrapper">
                <?php if (!empty($favicon_url)) : ?>
                    <div class="favicon-preview">
                        <img src="<?php echo esc_url($favicon_url); ?>" alt="<?php _e('Favicon del sitio', 'newspaperweb'); ?>" style="max-width: 32px; height: auto;" />
                    </div>
                <?php endif; ?>
            </div>
            
            <input type="button" id="upload_favicon_button" class="button" value="<?php _e('Subir Favicon', 'newspaperweb'); ?>" />
            
            <?php if (!empty($favicon_url)) : ?>
                <input type="button" id="remove_favicon_button" class="button" value="<?php _e('Eliminar Favicon', 'newspaperweb'); ?>" />
            <?php endif; ?>
            
            <p class="description">
                <?php _e('Tamaño recomendado: 32x32 píxeles. El favicon se mostrará en las pestañas del navegador.', 'newspaperweb'); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Callback para la sección de menús
     */
    public function menus_section_callback() {
        echo '<p>' . __('Selecciona qué menú se mostrará en cada ubicación.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el campo de selección del menú principal
     */
    public function primary_menu_callback() {
        $options = get_option('newspaperweb_options');
        $selected_menu = isset($options['primary_menu']) ? $options['primary_menu'] : '';
        
        // Obtener todos los menús registrados
        $menus = wp_get_nav_menus();
        
        if (empty($menus)) {
            echo '<p>' . __('No hay menús disponibles. Por favor, crea un menú primero.', 'newspaperweb') . '</p>';
            return;
        }
        
        echo '<select id="primary_menu" name="newspaperweb_options[primary_menu]">';
        echo '<option value="">' . __('- Seleccionar un menú -', 'newspaperweb') . '</option>';
        
        foreach ($menus as $menu) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($menu->term_id),
                selected($selected_menu, $menu->term_id, false),
                esc_html($menu->name)
            );
        }
        
        echo '</select>';
        echo '<p class="description">' . __('Este menú se mostrará en la ubicación "Menú Principal".', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el campo de selección del diseño del menú
     */
    public function menu_layout_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['menu_layout']) ? $options['menu_layout'] : 'classic';
        
        $layouts = array(
            'classic' => __('Clásico (Horizontal a la derecha)', 'newspaperweb'),
            'centered' => __('Centrado (Logo en medio)', 'newspaperweb'),
            'vertical' => __('Vertical (A la izquierda)', 'newspaperweb')
        );
        
        echo '<select id="menu_layout" name="newspaperweb_options[menu_layout]">';
        foreach ($layouts as $layout => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($layout),
                selected($selected, $layout, false),
                esc_html($label)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona el diseño del menú principal.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para la sección de tipografía general
     */
    public function general_typography_section_callback() {
        echo '<p>' . __('Configura las fuentes que se utilizarán en todo el sitio.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para la fuente del cuerpo
     */
    public function body_font_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['body_font']) ? $options['body_font'] : 'Roboto, sans-serif';
        $fonts = $this->get_available_fonts();
        
        echo '<select id="body_font" name="newspaperweb_options[body_font]" class="newspaperweb-select2">';
        foreach ($fonts as $font_family => $font_name) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($font_family),
                selected($selected, $font_family, false),
                esc_html($font_name)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona la fuente principal para el texto del cuerpo.', 'newspaperweb') . '</p>';
        echo '<p class="font-preview" style="font-family: ' . esc_attr($selected) . ';">' . __('Vista previa: El rápido zorro marrón salta sobre el perro perezoso.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el tamaño de fuente del cuerpo
     */
    public function body_font_size_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['body_font_size']) ? $options['body_font_size'] : '16px';
        
        $sizes = array(
            '12px' => '12px (Pequeño)',
            '14px' => '14px (Medio-Pequeño)',
            '16px' => '16px (Medio - Recomendado)',
            '18px' => '18px (Medio-Grande)',
            '20px' => '20px (Grande)'
        );
        
        echo '<select id="body_font_size" name="newspaperweb_options[body_font_size]">';
        foreach ($sizes as $size => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($size),
                selected($selected, $size, false),
                esc_html($label)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona el tamaño de la fuente para el texto del cuerpo.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para la sección de tipografía de títulos
     */
    public function headings_typography_section_callback() {
        echo '<p>' . __('Configura las fuentes que se utilizarán para los títulos.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para la fuente de títulos
     */
    public function headings_font_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['headings_font']) ? $options['headings_font'] : 'Montserrat, sans-serif';
        $fonts = $this->get_available_fonts();
        
        echo '<select id="headings_font" name="newspaperweb_options[headings_font]" class="newspaperweb-select2">';
        foreach ($fonts as $font_family => $font_name) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($font_family),
                selected($selected, $font_family, false),
                esc_html($font_name)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona la fuente para los títulos (h1-h6).', 'newspaperweb') . '</p>';
        echo '<p class="font-preview" style="font-family: ' . esc_attr($selected) . '; font-weight: bold;">' . __('Vista previa: Título de ejemplo', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el tamaño de fuente de títulos
     */
    public function headings_font_size_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['headings_font_size']) ? $options['headings_font_size'] : '24px';
        
        $sizes = array(
            '16px' => '16px (Pequeño)',
            '20px' => '20px (Medio-Pequeño)',
            '24px' => '24px (Medio - Recomendado)',
            '28px' => '28px (Medio-Grande)',
            '32px' => '32px (Grande)',
            '36px' => '36px (Extra Grande)'
        );
        
        echo '<select id="headings_font_size" name="newspaperweb_options[headings_font_size]">';
        foreach ($sizes as $size => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($size),
                selected($selected, $size, false),
                esc_html($label)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona el tamaño de la fuente para los títulos.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el peso de los títulos
     */
    public function headings_weight_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['headings_weight']) ? $options['headings_weight'] : '700';
        
        $weights = array(
            '300' => 'Light (300)',
            '400' => 'Regular (400)',
            '500' => 'Medium (500)',
            '600' => 'Semi-Bold (600)',
            '700' => 'Bold (700)',
            '800' => 'Extra-Bold (800)',
            '900' => 'Black (900)'
        );
        
        echo '<select id="headings_weight" name="newspaperweb_options[headings_weight]">';
        foreach ($weights as $weight => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($weight),
                selected($selected, $weight, false),
                esc_html($label)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona el peso (grosor) de los títulos.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para la sección de tipografía del menú
     */
    public function menu_typography_section_callback() {
        echo '<p>' . __('Configura las fuentes que se utilizarán en los menús de navegación.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para la fuente del menú
     */
    public function menu_font_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['menu_font']) ? $options['menu_font'] : 'Roboto, sans-serif';
        $fonts = $this->get_available_fonts();
        
        echo '<select id="menu_font" name="newspaperweb_options[menu_font]" class="newspaperweb-select2">';
        foreach ($fonts as $font_family => $font_name) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($font_family),
                selected($selected, $font_family, false),
                esc_html($font_name)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona la fuente para los elementos del menú.', 'newspaperweb') . '</p>';
        echo '<p class="font-preview" style="font-family: ' . esc_attr($selected) . ';">' . __('Vista previa: Elemento del menú', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el tamaño de fuente del menú
     */
    public function menu_font_size_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['menu_font_size']) ? $options['menu_font_size'] : '14px';
        
        $sizes = array(
            '12px' => '12px (Pequeño)',
            '13px' => '13px (Pequeño-Medio)',
            '14px' => '14px (Medio - Recomendado)',
            '16px' => '16px (Grande)',
            '18px' => '18px (Muy grande)'
        );
        
        echo '<select id="menu_font_size" name="newspaperweb_options[menu_font_size]">';
        foreach ($sizes as $size => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($size),
                selected($selected, $size, false),
                esc_html($label)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona el tamaño de la fuente para los elementos del menú.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para la transformación de texto del menú
     */
    public function menu_text_transform_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['menu_text_transform']) ? $options['menu_text_transform'] : 'none';
        
        $transforms = array(
            'none' => 'Ninguna',
            'uppercase' => 'MAYÚSCULAS',
            'lowercase' => 'minúsculas',
            'capitalize' => 'Capitalizar Primera Letra'
        );
        
        echo '<select id="menu_text_transform" name="newspaperweb_options[menu_text_transform]">';
        foreach ($transforms as $transform => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($transform),
                selected($selected, $transform, false),
                esc_html($label)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona la transformación de texto para los elementos del menú.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el peso de la fuente del menú
     */
    public function menu_font_weight_callback() {
        $options = get_option('newspaperweb_options');
        $selected = isset($options['menu_font_weight']) ? $options['menu_font_weight'] : '400';
        
        $weights = array(
            '100' => __('Thin (100)', 'newspaperweb'),
            '200' => __('Extra Light (200)', 'newspaperweb'),
            '300' => __('Light (300)', 'newspaperweb'),
            '400' => __('Regular (400)', 'newspaperweb'),
            '500' => __('Medium (500)', 'newspaperweb'),
            '600' => __('Semi Bold (600)', 'newspaperweb'),
            '700' => __('Bold (700)', 'newspaperweb'),
            '800' => __('Extra Bold (800)', 'newspaperweb'),
            '900' => __('Black (900)', 'newspaperweb')
        );
        
        echo '<select id="menu_font_weight" name="newspaperweb_options[menu_font_weight]">';
        foreach ($weights as $weight => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($weight),
                selected($selected, $weight, false),
                esc_html($label)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona el peso (grosor) de la fuente para los elementos del menú.', 'newspaperweb') . '</p>';
    }

    /**
     * Generar estilos CSS personalizados basados en las opciones
     */
    public function output_custom_styles() {
        $options = get_option('newspaperweb_options');
        
        // Obtener las opciones de tipografía
        $body_font = isset($options['body_font']) ? $options['body_font'] : 'Arial, sans-serif';
        $body_font_size = isset($options['body_font_size']) ? $options['body_font_size'] : '16px';
        $headings_font = isset($options['headings_font']) ? $options['headings_font'] : 'Arial, sans-serif';
        $headings_weight = isset($options['headings_weight']) ? $options['headings_weight'] : '700';
        $menu_font = isset($options['menu_font']) ? $options['menu_font'] : 'Arial, sans-serif';
        $menu_font_size = isset($options['menu_font_size']) ? $options['menu_font_size'] : '16px';
        $menu_text_transform = isset($options['menu_text_transform']) ? $options['menu_text_transform'] : 'none';
        $menu_font_weight = isset($options['menu_font_weight']) ? $options['menu_font_weight'] : '400';
        $menu_layout = isset($options['menu_layout']) ? $options['menu_layout'] : 'classic';
        
        // Verificar si se están usando fuentes de Google
        $google_fonts = array();
        if (strpos($body_font, 'Google Font') !== false) {
            $google_fonts[] = str_replace(' (Google Font)', '', $body_font);
        }
        if (strpos($headings_font, 'Google Font') !== false) {
            $google_fonts[] = str_replace(' (Google Font)', '', $headings_font);
        }
        if (strpos($menu_font, 'Google Font') !== false) {
            $google_fonts[] = str_replace(' (Google Font)', '', $menu_font);
        }
        
        // Construir la URL de Google Fonts si es necesario
        if (!empty($google_fonts)) {
            $google_fonts = array_unique($google_fonts);
            $font_families = array();
            foreach ($google_fonts as $font) {
                $font_families[] = str_replace(' ', '+', $font);
            }
            $google_fonts_url = 'https://fonts.googleapis.com/css?family=' . implode('|', $font_families);
            echo '<link href="' . esc_url($google_fonts_url) . '" rel="stylesheet">';
        }
        
        // Construir el CSS
        $css = '
        <style type="text/css">
            body {
                font-family: ' . esc_attr($body_font) . ';
                font-size: ' . esc_attr($body_font_size) . ';
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: ' . esc_attr($headings_font) . ';
                font-weight: ' . esc_attr($headings_weight) . ';
            }
            
            .navbar-nav .nav-link {
                font-family: ' . esc_attr($menu_font) . ';
                font-size: ' . esc_attr($menu_font_size) . ';
                text-transform: ' . esc_attr($menu_text_transform) . ';
                font-weight: ' . esc_attr($menu_font_weight) . ';
            }
            
            .navbar {
                padding: 1rem 0;
            }
        </style>';
        
        echo $css;
        
        // Añadir la clase del layout del menú al body
        add_filter('body_class', function($classes) use ($menu_layout) {
            $classes[] = 'menu-layout-' . esc_attr($menu_layout);
            return $classes;
        });
    }

    /**
     * Lista de familias de fuentes disponibles
     */
    private function get_available_fonts() {
        return array(
            'Arial, sans-serif' => 'Arial',
            'Helvetica, Arial, sans-serif' => 'Helvetica',
            'Georgia, serif' => 'Georgia',
            'Times New Roman, Times, serif' => 'Times New Roman',
            'Verdana, sans-serif' => 'Verdana',
            'Tahoma, sans-serif' => 'Tahoma',
            'Trebuchet MS, sans-serif' => 'Trebuchet MS',
            'Impact, sans-serif' => 'Impact',
            'Courier New, monospace' => 'Courier New',
            'Roboto, sans-serif' => 'Roboto (Google Fonts)',
            'Open Sans, sans-serif' => 'Open Sans (Google Fonts)',
            'Lato, sans-serif' => 'Lato (Google Fonts)',
            'Montserrat, sans-serif' => 'Montserrat (Google Fonts)',
            'Poppins, sans-serif' => 'Poppins (Google Fonts)',
            'Raleway, sans-serif' => 'Raleway (Google Fonts)',
            'Source Sans Pro, sans-serif' => 'Source Sans Pro (Google Fonts)',
            'Oswald, sans-serif' => 'Oswald (Google Fonts)',
            'Merriweather, serif' => 'Merriweather (Google Fonts)',
            'Playfair Display, serif' => 'Playfair Display (Google Fonts)',
            'Nunito, sans-serif' => 'Nunito (Google Fonts)'
        );
    }

    /**
     * Callback para la sección de posts
     */
    public function posts_section_callback() {
        echo '<p>' . __('Configura la visualización de posts en las diferentes plantillas de inicio.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el campo de número de posts por página en home-1
     */
    public function home1_posts_per_page_callback() {
        $options = get_option('newspaperweb_options');
        $value = isset($options['home1_posts_per_page']) ? $options['home1_posts_per_page'] : 20;
        echo '<input type="number" id="home1_posts_per_page" name="newspaperweb_options[home1_posts_per_page]" value="' . esc_attr($value) . '" min="1" max="100" />';
        echo '<p class="description">' . __('Número de posts a mostrar por página en el diseño Home 1.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el campo de categorías de la primera columna en home-1
     */
    public function home1_column1_categories_callback() {
        $options = get_option('newspaperweb_options');
        $selected_categories = isset($options['home1_column1_categories']) ? $options['home1_column1_categories'] : array();
        
        $categories = get_categories(array(
            'hide_empty' => false,
        ));
        
        echo '<select id="home1_column1_categories" name="newspaperweb_options[home1_column1_categories][]" multiple="multiple" style="width: 100%; min-height: 100px;">';
        foreach ($categories as $category) {
            $selected = in_array($category->term_id, $selected_categories) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr($category->term_id) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona las categorías que se mostrarán en la primera columna del diseño Home 1.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el campo de categorías de la segunda columna en home-1
     */
    public function home1_column2_categories_callback() {
        $options = get_option('newspaperweb_options');
        $selected_categories = isset($options['home1_column2_categories']) ? $options['home1_column2_categories'] : array();
        
        $categories = get_categories(array(
            'hide_empty' => false,
        ));
        
        echo '<select id="home1_column2_categories" name="newspaperweb_options[home1_column2_categories][]" multiple="multiple" style="width: 100%; min-height: 100px;">';
        foreach ($categories as $category) {
            $selected = in_array($category->term_id, $selected_categories) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr($category->term_id) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . __('Selecciona las categorías que se mostrarán en la segunda columna del diseño Home 1.', 'newspaperweb') . '</p>';
    }

    /**
     * Callback para el campo de número de posts por página en home-2
     */
    public function home2_posts_per_page_callback() {
        $options = get_option('newspaperweb_options');
        $value = isset($options['home2_posts_per_page']) ? $options['home2_posts_per_page'] : 20;
        echo '<input type="number" id="home2_posts_per_page" name="newspaperweb_options[home2_posts_per_page]" value="' . esc_attr($value) . '" min="1" max="100" />';
        echo '<p class="description">' . __('Número de posts a mostrar por página en el diseño Home 2.', 'newspaperweb') . '</p>';
    }
}

// Inicializar la clase
new NewspaperWeb_Theme_Options();

/**
 * Agregar JavaScript para inicializar los componentes del panel de opciones
 */
function newspaperweb_admin_scripts() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Inicializar color pickers
            $('.color-picker').wpColorPicker();
            
            // Inicializar Select2
            if ($.fn.select2) {
                $('.newspaperweb-select2').select2();
            }
            
            // Manejar el botón de subir logo
            $('#upload_logo_button').on('click', function(e) {
                e.preventDefault();
                
                var image = wp.media({
                    title: '<?php _e('Seleccionar o subir logo', 'newspaperweb'); ?>',
                    multiple: false
                }).open()
                .on('select', function() {
                    var uploaded_image = image.state().get('selection').first();
                    var image_url = uploaded_image.toJSON().url;
                    
                    $('#newspaperweb_site_logo').val(image_url);
                    
                    // Actualizar vista previa
                    $('.logo-preview-wrapper').html('<div class="logo-preview"><img src="' + image_url + '" alt="<?php _e('Logo del sitio', 'newspaperweb'); ?>" style="max-width: 200px; height: auto;" /></div>');
                    
                    // Añadir botón de eliminar si no existe
                    if ($('#remove_logo_button').length === 0) {
                        $('.newspaperweb-logo-uploader').append('<input type="button" id="remove_logo_button" class="button" value="<?php _e('Eliminar Logo', 'newspaperweb'); ?>" />');
                    }
                });
            });
            
            // Manejar el botón de eliminar logo (delegación de eventos)
            $(document).on('click', '#remove_logo_button', function(e) {
                e.preventDefault();
                
                $('#newspaperweb_site_logo').val('');
                $('.logo-preview-wrapper').empty();
                $(this).remove();
            });
            
            // Manejar el botón de subir favicon
            $('#upload_favicon_button').on('click', function(e) {
                e.preventDefault();
                
                var image = wp.media({
                    title: '<?php _e('Seleccionar o subir favicon', 'newspaperweb'); ?>',
                    multiple: false
                }).open()
                .on('select', function() {
                    var uploaded_image = image.state().get('selection').first();
                    var image_url = uploaded_image.toJSON().url;
                    
                    $('#newspaperweb_site_favicon').val(image_url);
                    
                    // Actualizar vista previa
                    $('.favicon-preview-wrapper').html('<div class="favicon-preview"><img src="' + image_url + '" alt="<?php _e('Favicon del sitio', 'newspaperweb'); ?>" style="max-width: 32px; height: auto;" /></div>');
                    
                    // Añadir botón de eliminar si no existe
                    if ($('#remove_favicon_button').length === 0) {
                        $('.newspaperweb-favicon-uploader').append('<input type="button" id="remove_favicon_button" class="button" value="<?php _e('Eliminar Favicon', 'newspaperweb'); ?>" />');
                    }
                });
            });
            
            // Manejar el botón de eliminar favicon (delegación de eventos)
            $(document).on('click', '#remove_favicon_button', function(e) {
                e.preventDefault();
                
                $('#newspaperweb_site_favicon').val('');
                $('.favicon-preview-wrapper').empty();
                $(this).remove();
            });
            
            // Vista previa de fuentes al cambiar selección
            $('#body_font, #headings_font, #menu_font').on('change', function() {
                var fontFamily = $(this).val();
                var previewElement = $(this).siblings('.font-preview');
                
                if (previewElement.length) {
                    previewElement.css('font-family', fontFamily);
                }
            });
        });
    </script>
    <?php
}

// Agregar el script al pie de página de la pantalla de opciones del tema
add_action('admin_print_footer_scripts-appearance_page_newspaperweb-options', 'newspaperweb_admin_scripts'); 