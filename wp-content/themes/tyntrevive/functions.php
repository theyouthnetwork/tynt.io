<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function my_theme_enqueue_styles() {
    
    // Enqueue the style.css's, which provide meta info
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css' );
    
    // Enqueue stylesheets here. Order should be:
    // 1. Framework libraries e.g. Twitter Bootstrap
    // 2. Other libraries e.g. (dunno, what else is there??)
    // 3. TYNT custom CSS, compacted into a single file (and minified for production)
    wp_enqueue_style( 'tynt-style', get_stylesheet_directory_uri() . '/css/tynt.css' );
    
    
    // Enqueue scripts here. Order should be:
    // 1. Framework Libraries e.g. Underscore (Note: jQuery is included in WP by default)
    // 2. Other libraries e.g. Slick slider, Google Maps, etc
    // 3. TYNT custom objects (must be js-namespaced as TYNT.something)
    wp_enqueue_script('top-menu-script', get_stylesheet_directory_uri() . '/js/top-menu-update.js', array());
}

// Code from http://vanweerd.com/enhancing-your-wordpress-3-menus/#add_login
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);

function add_login_logout_link($items, $args) {

		ob_start();
		wp_loginout('index.php');
		$loginoutlink = ob_get_contents();
		ob_end_clean();

		$items .= '<li>'. $loginoutlink .'</li>';

	return $items;
}


/**
 * Password checker hook
 * Todo: abstract the page URI to be configuratble or something
 * Todo: store the password NOT IN PLAIN TEXT
 */
function tynt_password_check() {

    if ( $_COOKIE["site-passwd"] == 'passtynt' && preg_match('#/password/?#', $_SERVER['REQUEST_URI']) ){
        // header('location:http://stage.tynt.io/');
        wp_redirect( home_url( '/', 'https' ) );
        exit;
    }
    elseif ( !($_COOKIE["site-passwd"] == 'passtynt' || preg_match('#/password/?#', $_SERVER['REQUEST_URI'])) ){
        // header('location:http://stage.tynt.io/password'); 
        wp_redirect( home_url( '/password', 'https' ) );     
        exit;
    }

}
add_action( 'init', 'tynt_password_check' );
