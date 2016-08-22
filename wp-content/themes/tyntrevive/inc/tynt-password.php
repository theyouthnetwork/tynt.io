<?php 
/**
 ************************************************************
 * TYNT Password Functions
 ************************************************************
 *
 * @package tyntrevive
 */

if ( ! function_exists( 'tynt_is_visitor_allowed' ) ) :
/**
 * Check whether visitor has applied the password
 */
function tynt_is_visitor_allowed() {
    $hashed = get_option( 'tynt_viewonly_password' );
    $is_valid_tynt_person = is_user_logged_in() && current_user_can( 'edit_posts' );
    $is_password_correct = !empty( $_COOKIE['site-passwd'] ) &&  wp_check_password( $_COOKIE['site-passwd'], $hashed );
	return $is_valid_tynt_person || $is_password_correct || !$hashed ;      // i.e. allow if there's no password lol
}
endif;


/**
 * Password handler function to check for a submitted password, and 
 * if correct then set it as a cookie
 */
add_action( 'init', 'tynt_password_handler' );
function tynt_password_handler() {
    
    $redirect_fragment = get_option( 'tynt_passwordpage_url_ending', '/password/' );
    // wp_die("#$redirect_fragment?#");
    $is_password_block_page = preg_match( "#$redirect_fragment?#", $_SERVER['REQUEST_URI'] );
    
    // We will want to do a hard redirect, to avoid browser messages about double-posting.
    // Then redirect them to original destination, or if unknown go to homepage
    if ( !empty( $_POST["passwd"] ) && $is_password_block_page ){
        
        $expiry_sec = time() + ( 60 * 60 * 24 * 365 );
        setcookie('site-passwd', $_POST["passwd"], $expiry_sec, '/'); 
        
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
        
    $is_valid_tynt_person = is_user_logged_in() && current_user_can( 'edit_posts' );
    $is_password_block_page = preg_match( '#/password/?#', $_SERVER['REQUEST_URI'] );
    $is_covered_by_passblock = is_home() || is_single() || is_page() || is_404();
    $redirect_fragment = get_option( 'tynt_passwordpage_url_ending', '/password/' );
        
    if ( $is_covered_by_passblock ){
        
        if ( tynt_is_visitor_allowed() ){
            if ( $is_password_block_page ){
                wp_redirect( home_url( '/', 'https' ) );
                exit;
            }
            else {
                // Let them pass
                return;       
            }
        }
        elseif ( !$is_password_block_page ){ 
            wp_redirect( home_url( $redirect_fragment, 'https' ) . '?onward='.urlencode($_SERVER['REQUEST_URI']) );                // Todo: remove this URL hardcoding to /password/
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
    $redirect_fragment = get_option( 'tynt_passwordpage_url_ending', '/password/' );
    $is_password_block_page = preg_match( "#$redirect_fragment?#", $_SERVER['REQUEST_URI'] );
    
    if ($is_password_block_page) {
        status_header( 200 );
        $wp_query->is_404=false;
        get_template_part( '403' );     // Corresponding template file is /403.php, edit that if you need
        exit;
    }
}