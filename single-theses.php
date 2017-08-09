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
					<div class="col-md-2">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 96, '', '', array('class' => 'img-circle img-margin') ); ?>
					</div>
					<div class="col-md-4">
						<h3><?php echo get_the_author_meta('display_name') ?></h3>
						<?php echo get_the_author_meta('user_email'); ?> <br/>
						<?php echo get_the_author_meta('description'); ?><br/>
					</div>
					<div class="col-md-6 single-project-meta">
						<?php
						$terms_parrent = wp_get_object_terms(get_the_ID(), 'diplomas');
						$terms_uni = wp_get_post_terms(get_the_ID(), 'parrent_university');

						echo '<br/><span class="university"><i class="fa fa-fw fa-university" aria-hidden="true"></i> ';
						foreach ($terms_uni as $term) {
							echo $term->name;
							break;
						}
						echo '</span><br/>';

						echo '<strong>Topic: </strong>';
						foreach ($terms_parrent as $term) {
							echo '<a href="' . get_permalink($term->term_id) . '">' . $term->name . '</a>';
							break;
						}
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">

					</div>
				</div>
			</section>
			<?php rh_the_post_navigation(); ?>
		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php // get_sidebar(); ?>
<?php get_footer();
