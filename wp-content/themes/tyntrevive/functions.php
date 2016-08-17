<?php

//
// https://github.com/theyouthnetwork/tynt.io/wiki/Coding,-Style-Guide-and-TYNT-conventions 
// 

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    
    // Enqueue the style.css's, which provide meta info
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css' );
    
    // Enqueue stylesheets here. Order should be:
    // 1. Framework libraries e.g. Twitter Bootstrap
    // 2. Other libraries e.g. (dunno, what else is there??)
    // 3. TYNT custom CSS, compacted into a single file (and minified for production)
    wp_enqueue_style( 'tynt-style', get_stylesheet_directory_uri() . '/assets/css/tynt.css' );
    
    
    // Enqueue scripts here. Order should be:
    // 1. Framework Libraries e.g. Underscore (Note: jQuery is included in WP by default)
    // 2. Other libraries e.g. Slick slider, Google Maps, etc
    // 3. TYNT custom objects (must be js-namespaced as TYNT.something)
    wp_enqueue_script('top-menu-script', get_stylesheet_directory_uri() . '/assets/js/top-menu-update.js', array());
}

// Code from http://vanweerd.com/enhancing-your-wordpress-3-menus/#add_login
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
function add_login_logout_link($items, $args) {

        // AY: Probably better to use wp_login_url() or wp_logout_url() ??

		ob_start();
		wp_loginout('index.php');
		$loginoutlink = ob_get_contents();
		ob_end_clean();

		$items .= '<li>'. $loginoutlink .'</li>';

	return $items;
}





// Include password checker functions and password admin page
include_once __DIR__ . '/inc/tynt-password.php';
include_once __DIR__ . '/inc/admin-password.php';

