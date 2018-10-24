<?php
/**
 * The template for displaying These Approval Form
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area col-sm-12">
		<main id="main" class="site-main" role="main">
			<?php if (is_user_logged_in()) { ?>

			<?php }	else { ?>
					<?php $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
					<strong>You must be logged in to perform this action!</strong><br>
					<a href="<?php echo wp_login_url( $actual_link ); ?>"><button class="btn btn-primary">Login</button></a><br/>				
			<?php } ?>
	</main><!-- #main -->
	</div><!-- #primary -->
</article><!-- #post-## -->
<?php get_footer();
