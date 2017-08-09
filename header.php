<?php
/*if (is_secure_ssl()) {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

if (!is_ssl()) {
    wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
    exit();
}*/

/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Red Hat Blog Theme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() ) . '/favicon.ico'; ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link visuallyhidden" href="#content"><?php esc_html_e( 'Skip to content', 'rh_parent' ); ?></a>
	<header id="masthead" class="site-header" role="banner" >
	<?php if ( get_header_image() ) : ?>
		<div class="header-image-wrap" style="background-image:url(<?php header_image();?>)">
			<div class="right-overlay with-image"><img src="<?php echo esc_url( get_template_directory_uri() );?>/img/g_pattern-overlay_image.png"/> </div>
	<?php else : // End header image check. ?>
		<div class="header-image-wrap" >
			<div class="right-overlay"><img src="<?php echo esc_url( get_template_directory_uri() );?>/img/g_pattern-overlay.png"/> </div>
	<?php endif; // End header image check. ?>

			<div class="container">
				<div class="site-branding">
						<div class="row"><div style="margin-top: 35px;" class="col-md-2"><a href="<?php echo esc_url( home_url('/') ); ?>" title="<?php bloginfo('name'); ?>"><img src="https://research.redhat.com/wp-content/uploads/2016/05/logo-rh.png"></a></div><div class="col-md-10">
					<h2 class="site-name">
						<a href="<?php echo esc_url( home_url('/') ); ?>" title="<?php bloginfo('name'); ?>"><?php if (is_active_sidebar('home_heading')) {dynamic_sidebar('home_heading');} else {bloginfo('name');} ?></a>
					</h2>
					<p class="sub-description"><?php // bloginfo( 'description' ); ?></p>
				</div>
			</div>
		</div>
			</div>
		</div>
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<div class="container">
				<div class="menu-toggle"><span class="menu-icon"></span> <?php esc_html_e( 'Main Menu', 'rh_parent' ); ?></div>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'primary-nav', 'container_class' => 'menu-primary-container' ) ); ?>
			</div>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
		<div class="container">
			<div class="row">
