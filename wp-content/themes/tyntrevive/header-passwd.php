<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *i
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
<div id="page2" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'revive' ); ?></a>
	<div id="jumbosearch">
		<span class="fa fa-remove closeicon"></span>
		<div class="form">
			<?php get_search_form(); ?>
		</div>
	</div>	

	<header id="masthead2" class="site-header" role="banner">
		<div class="container">
			<div class="site-branding">
				<div id="text-title-desc">
                    <h4 id="site-pass">Psst, what is the secret password?</h4>
                    <?php if ( ! tynt_is_visitor_allowed() ): ?>
                    <form id="site-passwd-form" method="post" action="/password/">
                        <input type="password" id="passwd" name="passwd" placeholder="Type something..">
                        <input type="hidden" name="onward" value="<?php echo isset( $_GET['onward'] ) ? htmlentities( wp_kses( $_GET['onward'], [] ) ) : '' ?>">
                        <button type="submit" name="submit" id="submit">Let me in</button>
                    </form>         
                    <?php endif; ?>
				</div>
			</div>	
		</div>	
		
	
	</header><!-- #masthead -->
	

	<div class="mega-container">
		
		<div id="content" class="site-content container">
