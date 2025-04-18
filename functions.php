<?php
if (!defined('ABSPATH')) {
    exit;
}

// Cargar el NavWalker de Bootstrap
require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';

// Cargar archivo de opciones del tema
require_once get_template_directory() . '/inc/theme-options.php';

// Cargar archivo de meta boxes
require_once get_template_directory() . '/inc/metaboxes.php';

// Cargar funciones de templates
require_once get_template_directory() . '/templates/template-functions.php';

// Configuración del tema
function newspaperweb_setup() {
    // Soporte para título automático
    add_theme_support('title-tag');
    
    // Soporte para imágenes destacadas
    add_theme_support('post-thumbnails');
    
    // Soporte para menús
    register_nav_menus(array(
        'primary' => __('Menú Principal', 'newspaperweb'),
        'footer' => __('Menú del Pie de Página', 'newspaperweb'),
        'social' => __('Menú de Redes Sociales', 'newspaperweb'),
    ));
    
    // Soporte para widgets
    add_theme_support('widgets');
    
    // Registrar área de widgets para el pie de página
    register_sidebar(array(
        'name'          => __('Área de Widgets del Pie de Página', 'newspaperweb'),
        'id'            => 'footer-1',
        'description'   => __('Agrega widgets aquí para que aparezcan en el pie de página.', 'newspaperweb'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Registrar área de widgets para la barra lateral
    register_sidebar(array(
        'name'          => __('Barra Lateral', 'newspaperweb'),
        'id'            => 'sidebar-1',
        'description'   => __('Agrega widgets aquí para que aparezcan en la barra lateral.', 'newspaperweb'),
        'before_widget' => '<div id="%1$s" class="card mb-4 widget %2$s">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<div class="card-header"><h3 class="widget-title h5 mb-0">',
        'after_title'   => '</h3></div><div class="card-body">',
    ));
}
add_action('after_setup_theme', 'newspaperweb_setup');

// Cargar Bootstrap y estilos del tema
function newspaperweb_scripts() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), '5.3.0');
    
    // Bootstrap Icons
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css', array(), '1.11.0');
    
    // Bootstrap JavaScript y Popper.js
    wp_enqueue_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), '5.3.0', true);
    
    // Estilos del tema
    wp_enqueue_style('newspaperweb-style', get_stylesheet_uri(), array('bootstrap'), '1.0');
    
    // Cargar estilos específicos del footer
    wp_enqueue_style('newspaperweb-footer', get_template_directory_uri() . '/assets/css/footer.css', array('newspaperweb-style'), '1.0');
    
    // Cargar estilos del menú según la opción seleccionada
    $options = get_option('newspaperweb_options');
    $menu_layout = isset($options['menu_layout']) ? $options['menu_layout'] : 'classic';
    
    switch ($menu_layout) {
        case 'centered':
            wp_enqueue_style('menu-centered', get_template_directory_uri() . '/css/menus/menu-centered.css', array('newspaperweb-style'), '1.0');
            break;
        case 'vertical':
            wp_enqueue_style('menu-vertical', get_template_directory_uri() . '/css/menus/menu-vertical.css', array('newspaperweb-style'), '1.0');
            break;
        default:
            // No se necesita CSS adicional para el menú clásico
            break;
    }
}
add_action('wp_enqueue_scripts', 'newspaperweb_scripts');

// Añadir clases a los enlaces de paginación
function newspaperweb_pagination_classes($html) {
    $html = str_replace('<ul class=\'page-numbers\'>', '<ul class="pagination justify-content-center">', $html);
    $html = str_replace('<li>', '<li class="page-item">', $html);
    $html = str_replace('<a class="page-numbers', '<a class="page-link', $html);
    $html = str_replace('<a class="prev page-numbers', '<a class="page-link', $html);
    $html = str_replace('<a class="next page-numbers', '<a class="page-link', $html);
    $html = str_replace('<span aria-current="page" class="page-numbers current">', '<span aria-current="page" class="page-link current active">', $html);
    return $html;
}
add_filter('paginate_links_output', 'newspaperweb_pagination_classes');

/**
 * Obtener el menú seleccionado en las opciones del tema
 */
function newspaperweb_get_selected_menu() {
    $options = get_option('newspaperweb_options');
    $menu_id = isset($options['primary_menu']) ? $options['primary_menu'] : '';
    
    if (empty($menu_id)) {
        // Si no hay menú seleccionado, usar el menú asignado a la ubicación 'primary'
        $locations = get_nav_menu_locations();
        $menu_id = isset($locations['primary']) ? $locations['primary'] : '';
    }
    
    return $menu_id;
}

/**
 * Comprobar si la barra lateral está activada para un post o página específico
 * 
 * @param int $post_id ID del post a comprobar. Si no se proporciona, se utiliza el post actual.
 * @return bool True si la barra lateral está activada, false en caso contrario.
 */
function newspaperweb_is_sidebar_enabled($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (!$post_id) {
        return true; // Por defecto, mostrar barra lateral
    }
    
    $sidebar_enabled = get_post_meta($post_id, '_newspaperweb_sidebar_enabled', true);
    
    // Si no hay valor guardado, asumir que sí está activada
    if ('' === $sidebar_enabled) {
        return true;
    }
    
    return 'yes' === $sidebar_enabled;
}

/**
 * Obtener el logo del sitio desde las opciones del tema
 * 
 * @return string URL del logo o cadena vacía si no hay logo
 */
function newspaperweb_get_site_logo() {
    $options = get_option('newspaperweb_options');
    return isset($options['site_logo']) ? $options['site_logo'] : '';
}

/**
 * Obtener el color de la barra de navegación
 * 
 * @return string Color hexadecimal o color por defecto si no está configurado
 */
function newspaperweb_get_navbar_color() {
    $options = get_option('newspaperweb_options');
    return isset($options['navbar_color']) ? $options['navbar_color'] : '#ffffff';
}

/**
 * Obtener el esquema de color de la barra de navegación
 * 
 * @return string 'light' o 'dark' según la configuración
 */
function newspaperweb_get_navbar_scheme() {
    $options = get_option('newspaperweb_options');
    return isset($options['navbar_scheme']) ? $options['navbar_scheme'] : 'light';
}

/**
 * Añadir estilos personalizados al head
 */
function newspaperweb_custom_styles() {
    $navbar_color = newspaperweb_get_navbar_color();
    $navbar_scheme = newspaperweb_get_navbar_scheme();
    
    // Determinar si el color es oscuro o claro para seleccionar el contraste adecuado
    // Esta es una función simple, pero podría hacerse más sofisticada
    
    $css = '
    <style type="text/css">
        .site-header {
            background-color: ' . esc_attr($navbar_color) . ' !important;
            border-bottom: 1px solid rgba(0,0,0,0.1) !important;
        }
        
        /* Estilos del footer de ancho completo */
        body {
            overflow-x: hidden; /* Prevenir scroll horizontal */
        }
        
        .site-footer {
            width: 100%;
            display: block;
            background-color: #212529;
            color: #f8f9fa;
            padding: 2rem 0;
            margin-top: 2rem;
            overflow-x: hidden;
            box-sizing: border-box;
        }
        
        .site-footer .container-fluid {
            width: 100%;
            max-width: 100%;
            padding-left: 2rem;
            padding-right: 2rem;
        }
        
        @media (min-width: 992px) {
            .site-footer .container-fluid {
                padding-left: 4rem;
                padding-right: 4rem;
            }
        }
        
        .footer-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            padding-left: 0;
            list-style: none;
        }
        
        .footer-nav a {
            color: #f8f9fa;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-nav a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
    ';
    
    // Añadir colores adicionales basados en el esquema seleccionado
    if ($navbar_scheme == 'light') {
        $css .= '
        .site-header .navbar-brand,
        .site-header .site-title a,
        .site-header .nav-link,
        .site-header .site-description {
            color: #212529 !important;
        }
        .site-header .navbar-toggler {
            color: rgba(0,0,0,0.55) !important;
            border-color: rgba(0,0,0,0.1) !important;
        }
        .site-header .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 30 30\'%3e%3cpath stroke=\'rgba%280, 0, 0, 0.55%29\' stroke-linecap=\'round\' stroke-miterlimit=\'10\' stroke-width=\'2\' d=\'M4 7h22M4 15h22M4 23h22\'/%3e%3c/svg%3e") !important;
        }
        .site-header .dropdown-menu {
            background-color: #f8f9fa !important;
            border-color: rgba(0,0,0,0.15) !important;
        }
        .site-header .dropdown-item {
            color: #212529 !important;
        }
        .site-header .dropdown-item:hover,
        .site-header .dropdown-item:focus {
            background-color: #e9ecef !important;
        }
        ';
    } else { // dark scheme
        $css .= '
        .site-header .navbar-brand,
        .site-header .site-title a,
        .site-header .nav-link,
        .site-header .site-description {
            color: #f8f9fa !important;
        }
        .site-header .navbar-toggler {
            color: rgba(255,255,255,0.55) !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        .site-header .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 30 30\'%3e%3cpath stroke=\'rgba%28255, 255, 255, 0.55%29\' stroke-linecap=\'round\' stroke-miterlimit=\'10\' stroke-width=\'2\' d=\'M4 7h22M4 15h22M4 23h22\'/%3e%3c/svg%3e") !important;
        }
        .site-header .dropdown-menu {
            background-color: #343a40 !important;
            border-color: rgba(255,255,255,0.15) !important;
        }
        .site-header .dropdown-item {
            color: #f8f9fa !important;
        }
        .site-header .dropdown-item:hover,
        .site-header .dropdown-item:focus {
            background-color: #495057 !important;
        }
        ';
    }
    
    $css .= '</style>';
    
    echo $css;
}
add_action('wp_head', 'newspaperweb_custom_styles');

/**
 * Registra las áreas de widgets
 */
function newspaperweb_widgets_init() {
    // Área de widgets para la barra lateral
    register_sidebar(array(
        'name'          => __('Barra Lateral', 'newspaperweb'),
        'id'            => 'sidebar-1',
        'description'   => __('Agrega widgets aquí para que aparezcan en la barra lateral.', 'newspaperweb'),
        'before_widget' => '<div id="%1$s" class="card mb-4 widget %2$s">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<div class="card-header"><h3 class="widget-title h5 mb-0">',
        'after_title'   => '</h3></div><div class="card-body">',
    ));
    
    // Área de widgets para publicidad en el header
    register_sidebar(array(
        'name'          => __('Publicidad Header', 'newspaperweb'),
        'id'            => 'header-ads',
        'description'   => __('Añade widgets de publicidad que aparecerán debajo de la cabecera.', 'newspaperweb'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title d-none">',
        'after_title'   => '</h3>',
    ));

    // Área de widgets para la barra lateral de la página de inicio
    register_sidebar(array(
        'name'          => __('Barra Lateral Home', 'newspaperweb'),
        'id'            => 'home-sidebar',
        'description'   => __('Agrega widgets aquí para que aparezcan en la barra lateral de la página de inicio.', 'newspaperweb'),
        'before_widget' => '<div id="%1$s" class="card mb-4 widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="card-header"><h3 class="widget-title h5 mb-0">',
        'after_title'   => '</h3></div><div class="card-body">',
    ));

    // Área de widgets para la primera columna del footer
    register_sidebar(array(
        'name'          => __('Footer Columna 1', 'newspaperweb'),
        'id'            => 'footer-1',
        'description'   => __('Agrega widgets aquí para que aparezcan en la primera columna del footer.', 'newspaperweb'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    // Área de widgets para la segunda columna del footer
    register_sidebar(array(
        'name'          => __('Footer Columna 2', 'newspaperweb'),
        'id'            => 'footer-2',
        'description'   => __('Agrega widgets aquí para que aparezcan en la segunda columna del footer.', 'newspaperweb'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'newspaperweb_widgets_init');

/**
 * Registra las plantillas de página
 */
function newspaperweb_register_page_templates($templates) {
    $templates['template-parts/home/home-1.php'] = __('Home Design 1', 'newspaperweb');
    $templates['template-parts/home/home-2.php'] = __('Home Design 2', 'newspaperweb');
    return $templates;
}
add_filter('theme_page_templates', 'newspaperweb_register_page_templates');

/**
 * Carga los estilos específicos de las plantillas de inicio
 */
function newspaperweb_load_home_template_styles() {
    // Obtener la plantilla actual
    $template = get_post_meta(get_the_ID(), '_wp_page_template', true);
    
    // Cargar los estilos según la plantilla
    if ($template === 'template-parts/home/home-1.php') {
        wp_enqueue_style(
            'newspaperweb-home-1',
            get_template_directory_uri() . '/assets/css/home-templates/home-1.css',
            array(),
            '1.0.0'
        );
    } elseif ($template === 'template-parts/home/home-2.php') {
        wp_enqueue_style(
            'newspaperweb-home-2',
            get_template_directory_uri() . '/assets/css/home-templates/home-2.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'newspaperweb_load_home_template_styles'); 