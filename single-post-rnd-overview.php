<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		
		<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single-red' ); ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<section id="rnd-listing">
			<div class="row">
				<?php
				$args=array(
            		'post_type' => 'projects',
            		'post_status' => 'publish',
            		'posts_per_page' => -1,
            		'ignore_sticky_posts' => 1);
				$my_query = new WP_Query($args);
					while ($my_query->have_posts()) : $my_query->the_post();
						$terms_place = wp_get_post_terms(get_the_ID(), 'project_place');
						$terms_categories = wp_get_post_terms(get_the_ID(), 'project_category');

						?>
						<div class="col-md-9">
							<?php the_title(); ?>
						</div>
						<div class="col-md-3">
							<?php the_author(); ?>
						</div>
				<?php endwhile;
				?>
			</div>
	</section>
<?php get_footer();
