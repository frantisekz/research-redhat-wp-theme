<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area col-sm-12 col-md-12">
		
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
						<?php // echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', array('class' => 'img-circle img-margin') ); ?>
					<div class="col-md-6" style="padding-left: 2em;">
						<h3><?php echo get_the_author_meta('display_name'); ?></h3>
						<strong>Team: </strong><?php echo get_the_author_meta('rh_team'); ?><br/>
						<strong>Location: </strong><?php echo get_the_author_meta('parrent_rh_office'); ?><br/>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#get_in_touch_modal">
							Get in Touch!
						</button>
					</div>
					<div class="col-md-6 single-project-meta">
					<?php
						$terms = array();
						$terms = wp_get_post_terms(get_the_ID(), 'project_place');
						foreach ($terms as $term) {
							echo '<span class="city"><i class="fa fa-home" aria-hidden="true"></i> ' . $term->name . '</span><br/>';
						}
						// echo '<span class="city-notice">You can participate even if you live somewhere else.</span>';
						?>
					</div>
				</div>
			</section>

			<?php endwhile; // end of the loop. ?>
			
			<section id="contact-after-project">
				<?php
				while ( have_posts() ) : the_post(); // And start the loop again, new and shiny one
				?>
				<div class="modal fade" tabindex="-1" role="dialog" id="get_in_touch_modal">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Get in touch!</h4>
						</div>
						<div class="modal-body">
							<?php if (function_exists('Ninja_Forms')) { Ninja_Forms()->display($PROJECT_FORM_ID); } ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<?php endwhile; // end of the loop. ?>
			</section>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php // get_sidebar(); ?>
<?php get_footer();
