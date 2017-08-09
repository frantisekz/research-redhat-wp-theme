<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div class="front-sidebar"><div class="arrow"><a id="enlrg"><strong>Upcoming events</strong></a><a id="ensml"><strong>Upcoming events</strong></a></div><?php if (is_active_sidebar('events_sidebar')) {dynamic_sidebar('events_sidebar');} ?></div>
		<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single-red' ); ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<section id="rnd-listing">
	<div style="height: 5em;">
		<button class="master-reset-btn" onclick="reset_filter(1)">Reset Filters</button><br/>
		<div class="dropdown" style="float: left;">
			<button class="btn btn-default dropdown-toggle" type="button" id="city_drop_down" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				All Cities
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" aria-labelledby="city_drop_down">
					<?php 
					$generic_terms_place = get_terms(['taxonomy' => 'project_place', 'hide_empty' => false]);
					foreach ($generic_terms_place as $generic_term_place) {
						echo '<li onclick="trigger_box(' . $generic_term_place->term_id . ', 1)" class="city_drop_down" id="trigger-' . $generic_term_place->term_id . '"><a href="#">' . $generic_term_place->name . '</a></li>';
					}?>
			</ul>
			</div>

			<div class="dropdown" style="float: left;">
			<button class="btn btn-default dropdown-toggle" type="button" id="spec_drop_down" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				All Specializations
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" aria-labelledby="spec_drop_down">
					<?php 
					$generic_terms_tags = get_terms(['taxonomy' => 'project_category', 'hide_empty' => false]);
					foreach ($generic_terms_tags as $generic_term_tag) {
						echo '<li onclick="trigger_box(' . $generic_term_tag->term_id . ', 0)" class="spec_drop_down" id="trigger-' . $generic_term_tag->term_id . '"><a href="#">' . $generic_term_tag->name . '</a></li>';
					}?>
			</ul>
		</div>
	</div>
			<div class="row">
				<?php
				$args=array(
            		'post_type' => 'projects',
            		'post_status' => 'publish',
            		'posts_per_page' => -1,
            		'caller_get_posts'=> 1);
				$my_query = new WP_Query($args);

				if( $my_query->have_posts() ) {
					remove_filter('term_description','wpautop');
					while ($my_query->have_posts()) : $my_query->the_post();
						$terms_place = wp_get_post_terms(get_the_ID(), 'project_place');
						$terms_categories = wp_get_post_terms(get_the_ID(), 'project_category');

						?>
						<div onclick="window.location='<?php echo the_permalink(); ?>';" class="col-md-12 project-box" id="<?php foreach ($terms_place as $term) {echo $term->term_id . " ";}?>">
						<div class="project-inner">
							<div class="row">
								<div class="col-md-7">
									<p style="font-weight: 700;"><?php add_filter( 'the_title', 'max_title_length'); the_title(); remove_filter( 'the_title', 'max_title_length'); ?></p>
								</div>
								<div class="col-md-5">
								<?php
									foreach ($terms_categories as $term) {
										echo '<span class="diploma-tag"><i class="fa fa-tag" aria-hidden="true"></i><span id="' . $term->term_id . '"';
										echo ' style="white-space: nowrap; padding-right: 15px;">' . $term->name . '</span></span>';
									}
								?>
								</div>
						</div>
						<?php the_excerpt(); ?>
						<div style="font-size: 1.1em; display: inline-block; width: 100%; visibility: hidden;">
							<?php 
							foreach ($terms_place as $term) {
								echo '<i class="fa fa-home" aria-hidden="true"></i><span id="' . $term->term_id . '"';
								echo ' style="white-space: nowrap; padding-right: 15px;">' . $term->name . '</span>';
							}?>
						</div>
						</div>
						</div>
						<?php add_filter('term_description','wpautop');
				endwhile;
				}
				?>
			</div>
	</section>
<?php get_footer();
