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
						$terms_city = wp_get_post_terms(get_the_ID(), 'home_city');
						$city_count = count($terms_city);
						$i = 0;
						echo '<span class="city"><i class="fa fa-fw fa-home" aria-hidden="true"></i>';
						foreach ($terms_city as $term) {
							$i += 1;
							if ($i != $city_count) {
								echo $term->name . ', ';	
							}
							else {
								echo $term->name;
						}
						}
						echo '</span><br/>';

						$terms_uni = wp_get_post_terms(get_the_ID(), 'parrent_university');
						echo '<span class="university"><i class="fa fa-fw fa-university" aria-hidden="true"></i> ';
						$uni_count = count($terms_uni);
						$i = 0;
						foreach ($terms_uni as $term) {
							$i += 1;
							if ($i != $uni_count) {
								echo $term->name . ', ';	
							}
							else {
								echo $term->name;
							}
						}
						echo '</span>';
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<strong>Diploma theses with this Topic: </strong><br/>
						<?php
						$args=array(
							'post_type' => 'theses',
							'post_status' => 'publish',
							'posts_per_page' => -1,
							'caller_get_posts'=> 1);
						$my_query = new WP_Query($args);
						$this_id = get_the_ID();

						if( $my_query->have_posts() ) {
							while ($my_query->have_posts()) : $my_query->the_post();
								$terms_topics = wp_get_object_terms(get_the_ID(), 'diplomas');
								foreach ($terms_topics as $terms_topic) {
									if ($terms_topic->term_id == $this_id) {
										echo '<a href="' . get_permalink() . '">' . get_the_title() . "</a><br/>";
									}
								}
							endwhile;
						}
						?>
					</div>
				</div>
			</section>
			<section id="contact-after-project">
				<?php if(function_exists('ninja_forms_display_form')){ ninja_forms_display_form(9/*ID of relevant Contact form*/); } ?>
			</section>
			<?php rh_the_post_navigation(); ?>
		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php // get_sidebar(); ?>
<?php get_footer();
