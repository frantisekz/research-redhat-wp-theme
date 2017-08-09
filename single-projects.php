<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area col-sm-12 col-md-12">
		<div class="front-sidebar"><div class="arrow"><a id="enlrg"><strong>Upcoming events</strong></a><a id="ensml"><strong>Upcoming events</strong></a></div><?php if (is_active_sidebar('events_sidebar')) {dynamic_sidebar('events_sidebar');} ?></div>
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

			<section id="project_coordinator">
				<div class="row">
					<div class="col-md-1">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 96, '', '', array('class' => 'img-circle img-margin') ); ?>
					</div>
					<div class="col-md-6" style="padding-left: 2.5em;">
						<h3><?php echo get_the_author_meta('display_name') ?></h3>
						<?php echo get_the_author_meta('user_email'); ?> <br/>
						<?php echo get_the_author_meta('description'); ?><br/>
					</div>
					<div class="col-md-5 text-right">
						<?php
						$terms = array();
						$terms = wp_get_post_terms(get_the_ID()/* $post->ID broken here */, 'project_place');
						foreach ($terms as $term) {
							echo '<span class="city"><i class="fa fa-home" aria-hidden="true"></i> ' . $term->name . '</span><br/>';
						}
						echo '<span class="city-notice">You can participate even if you live somwhere else.</span>';
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">

					</div>
				</div>
			</section>
			<section id="contact-after-project">
				<?php if( function_exists( 'ninja_forms_display_form' ) ){ ninja_forms_display_form( 7 ); } ?>
			</section>
			<?php rh_the_post_navigation(); ?>
		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php // get_sidebar(); ?>
<?php get_footer();
