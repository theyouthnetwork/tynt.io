<?php
/**
 * Custom template tags for this theme.
 *
 * @package tyntrevive
 */

if ( ! function_exists( 'tynt_is_authenticated' ) ) :
/**
 * Check whether visitor has applied the password
 */
function tynt_is_authenticated() {
	return !empty( $_COOKIE['site-passwd'] ) && $_COOKIE['site-passwd'] == 'passtynt';
}
endif;