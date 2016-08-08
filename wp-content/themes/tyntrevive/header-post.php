<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package revive
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'revive' ); ?></a>
	<div id="jumbosearch">
		<span class="fa fa-remove closeicon"></span>
		<div class="form">
			<?php get_search_form(); ?>
		</div>
	</div>	
	
	<div id="top-bar">
		<div class="container">
			<div id="top-menu">
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</div>
		</div>
	</div>
	<header id="masthead" class="site-header" role="banner">
		<div class="container">
			<div id="featured-image">
			<?php the_post_thumbnail('full'); ?>
			</div>
		</div>
	</header>

	<div class="mega-container">
			

		<div id="content" class="site-content container">
