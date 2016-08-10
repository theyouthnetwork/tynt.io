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

        // AY: Probably better to use wp_login_url() or wp_logout_url() ??

		ob_start();
		wp_loginout('index.php');
		$loginoutlink = ob_get_contents();
		ob_end_clean();

		$items .= '<li>'. $loginoutlink .'</li>';

	return $items;
}


/**
 * Password handler function to check for a submitted password, and 
 * if correct then set it as a cookie
 */
add_action( 'init', 'tynt_password_handler' );
function tynt_password_handler() {
    
    $is_password_block_page = preg_match( '#/password/?#', $_SERVER['REQUEST_URI'] );
    
    // We will want to do a hard redirect, to avoid browser messages about double-posting.
    // Then redirect them to original destination, or if unknown go to homepage
    if ( !empty( $_POST["passwd"] ) && $is_password_block_page ){
        setcookie('site-passwd', $_POST["passwd"], time() + 3600, '/'); 
        if ( $_POST['onward'] ){
            wp_redirect( home_url( $_POST['onward'], 'https' ) );
            exit;
        }
        wp_redirect( home_url( '/', 'https' ) );
        exit;
    }
    
}

/**
 * Determines access to the page based on if they are authenticated.
 * Todo: abstract the page URI to be configuratble or something
 * Todo: store the password NOT IN PLAIN TEXT
 */
add_action( 'posts_selection', 'tynt_password_check' );
function tynt_password_check() {
        
    $is_correct_pass = !empty( $_COOKIE['site-passwd'] ) && $_COOKIE['site-passwd'] == 'passtynt';
    $is_password_block_page = preg_match( '#/password/?#', $_SERVER['REQUEST_URI'] );
    
        
    if ( is_home() || is_single() || is_page() || is_404() ){
        
        if ( $is_correct_pass ){
            if ( $is_password_block_page ){
                // header('location:http://stage.tynt.io/');
                wp_redirect( home_url( '/', 'https' ) );
                exit;
            }
            else {
                return;       
            }
        }
        elseif ( !$is_password_block_page ){ 
            // header('location:http://stage.tynt.io/password'); 
            wp_redirect( home_url( '/password/', 'https' ) . '?onward='.urlencode($_SERVER['REQUEST_URI']) );                // Todo: remove this URL hardcoding to /password/
            exit;
        }
        
    }
    
}



/**
 * Password page renderer. Will render nice stuff without 
 * need for an underlying Page entry
 */
add_filter('template_redirect', 'tynt_render_password_page' );
function tynt_render_password_page() {
    global $wp_query;
    $is_password_block_page = preg_match( '#/password/?#', $_SERVER['REQUEST_URI'] );
    
    if ($is_password_block_page) {
        status_header( 200 );
        $wp_query->is_404=false;
        get_template_part( '403' );     // Corresponding template file is /403.php, edit that if you need
        exit;
    }
}





/***** INCLUDES *****/

/**
 * Custom template tags for this (child) theme.
 */
require get_stylesheet_directory() . '/inc/template-tags.php';

