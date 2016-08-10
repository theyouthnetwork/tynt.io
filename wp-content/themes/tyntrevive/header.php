<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *i
 * @package revive
 */

// if ($_SERVER['REQUEST_URI'] == '/password/')
// {
	// setcookie('site-passwd', $_POST["passwd"], time() + 3600, '/'); 

    /**
     * Bear in mind that the password to your website is on a public GitHub repository...
     * This stuff is now moved into an action hook
     */
    
	// if (isset($_COOKIE['site-passwd']) && $_COOKIE['site-passwd'] == "passtynt")
	// { 
		// header('location:http://stage.tynt.io/');
	// }

// }

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
	
<div id="top"></div>
	<div id="top-bar">

		<div class="container">
			<div id="top-menu">
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</div>
		</div>
	</div>
	
	<header id="masthead" class="site-header" role="banner">
		<div class="container">
			<div class="site-branding">
				<?php if ( get_theme_mod('revive_logo') != "" ) : ?>
				<div id="site-logo">
					<a href="<?php echo home_url('/blog') ?>"><img src="<?php echo esc_url( get_theme_mod('revive_logo') ); ?>"></a>
				</div>
				<?php endif; ?>
				<div id="text-title-desc">
                    <h1 class="site-title title-font"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                    <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
                    <?php if ( ! tynt_is_authenticated() ): ?>
                    <form id="site-passwd-form" method="post" action="/password/">
                        <input type="password" id="passwd" name="passwd" placeholder="What is your secret">
                        <input type="hidden" name="onward" value="<?php echo htmlentities( wp_kses( $_GET['onward'], [] ) ) ?>">
                        <button type="submit" name="submit" id="submit">Submit</button>
                    </form>         
                    <?php endif; ?>
				</div>
			</div>	
		</div>	
		
		<div id="search-icon">
			<a id="searchicon">
				<span class="fa fa-search"></span>
			</a>
		</div>	
		
	</header><!-- #masthead -->
	
<div id="articles"></div>
	<div id="social-icons">
		<?php get_template_part('social', 'fa'); ?>
	</div>
	
	<div class="mega-container">
			
		<?php get_template_part('featured', 'area1'); ?>
		<?php get_template_part('featured', 'area2'); ?>
		
		<?php get_template_part('slider', 'nivo' ); ?>
        <div id="back-top"><a href="#top"><img src="<?php echo get_stylesheet_directory_uri()."/assets/images/back-top.png"; ?>" height="100" width="100"></a></div>
		<div id="content" class="site-content container">
