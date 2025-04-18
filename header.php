<?php
// Obtener el esquema de color
$navbar_scheme = newspaperweb_get_navbar_scheme();
$navbar_class = ($navbar_scheme === 'dark') ? 'navbar-dark' : 'navbar-light';

// Obtener las opciones del tema
$options = get_option('newspaperweb_options');
$menu_layout = isset($options['menu_layout']) ? $options['menu_layout'] : 'classic';
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header border-bottom">
    <div class="container">
        <nav class="navbar navbar-expand-lg <?php echo esc_attr($navbar_class); ?> menu-layout-<?php echo esc_attr($menu_layout); ?>">
            <div class="container-fluid">
                <?php
                if ($menu_layout === 'centered') {
                    // Logo a la izquierda
                    if (!empty($options['site_logo'])) {
                        echo '<a class="navbar-brand" href="' . esc_url(home_url('/')) . '"><img src="' . esc_url($options['site_logo']) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="site-logo"></a>';
                    } else {
                        echo '<a class="navbar-brand" href="' . esc_url(home_url('/')) . '">' . get_bloginfo('name') . '</a>';
                    }
                    
                    // Menú centrado
                    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>';
                    
                    echo '<div class="collapse navbar-collapse justify-content-center" id="navbarNav">';
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'navbar-nav',
                        'fallback_cb' => '__return_false',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth' => 2,
                        'walker' => new WP_Bootstrap_Navwalker()
                    ));
                    echo '</div>';
                    
                    // Espacio vacío a la derecha para balancear
                    echo '<div class="d-none d-lg-block" style="width: 150px;"></div>';
                    
                } elseif ($menu_layout === 'vertical') {
                    // Menú vertical
                    echo '<div class="d-flex flex-column w-100">';
                    if (!empty($options['site_logo'])) {
                        echo '<a class="navbar-brand mb-3" href="' . esc_url(home_url('/')) . '"><img src="' . esc_url($options['site_logo']) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="site-logo"></a>';
                    } else {
                        echo '<a class="navbar-brand mb-3" href="' . esc_url(home_url('/')) . '">' . get_bloginfo('name') . '</a>';
                    }
                    
                    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>';
                    
                    echo '<div class="collapse navbar-collapse" id="navbarNav">';
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'navbar-nav flex-column',
                        'fallback_cb' => '__return_false',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth' => 2,
                        'walker' => new WP_Bootstrap_Navwalker()
                    ));
                    echo '</div>';
                    echo '</div>';
                    
                } else {
                    // Diseño clásico (por defecto)
                    if (!empty($options['site_logo'])) {
                        echo '<a class="navbar-brand" href="' . esc_url(home_url('/')) . '"><img src="' . esc_url($options['site_logo']) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="site-logo"></a>';
                    } else {
                        echo '<a class="navbar-brand" href="' . esc_url(home_url('/')) . '">' . get_bloginfo('name') . '</a>';
                    }
                    
                    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>';
                    
                    echo '<div class="collapse navbar-collapse" id="navbarNav">';
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'navbar-nav ms-auto',
                        'fallback_cb' => '__return_false',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth' => 2,
                        'walker' => new WP_Bootstrap_Navwalker()
                    ));
                    echo '</div>';
                }
                ?>
            </div>
        </nav>
    </div>
</header> 

<!-- Área de publicidad después de la cabecera -->
<div class="header-ads-container py-3 bg-light border-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <?php if (is_active_sidebar('header-ads')) : ?>
                    <div class="header-ads text-center">
                        <?php dynamic_sidebar('header-ads'); ?>
                    </div>
                <?php else : ?>
                    <!-- Mensaje que solo verán los administradores si no hay widgets configurados -->
                    <?php if (current_user_can('manage_options')) : ?>
                        <div class="alert alert-info text-center">
                            <?php _e('Este es el área para publicidad. Añade widgets en "Apariencia > Widgets > Publicidad Header"', 'newspaperweb'); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 


