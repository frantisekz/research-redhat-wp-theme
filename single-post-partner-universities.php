<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area col-sm-12">
		
		<main id="main" class="site-main" role="main">
			<h1 style="color: white;"><?php the_title(); ?></h1>
	<section id="rnd-listing">

<div class="dropdown" style="float: left;">
  	<button class="btn btn-white btn-default dropdown-toggle" type="button" id="university_drop_down" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    	All Locations
    	<span class="caret"></span>
  	</button>
  	<ul class="dropdown-menu" aria-labelledby="university_drop_down">
  		<?php 
		$generic_terms_place = get_terms(['taxonomy' => 'university_location', 'hide_empty' => false]);
		foreach ($generic_terms_place as $generic_term_place) {
			echo '<li onclick="trigger_box(' . $generic_term_place->term_id . ', 1)" class="university_drop_down" id="trigger-' . $generic_term_place->term_id . '"><a href="#">' . $generic_term_place->name . '</a></li>';
		}?>
	</ul>
</div>

<button class="btn btn-white btn-default master-reset-btn" onclick="reset_filter(1)">Reset Filters</button>

			<div class="row">
			<?php
				$args=array(
            		'post_type' => 'partner_unis',
            		'post_status' => 'publish',
            		'posts_per_page' => -1,
            		'ignore_sticky_posts' => 1);
				$my_query = null;
        		$my_query = new WP_Query($args); 
				remove_filter('term_description','wpautop');
            	while ($my_query->have_posts()) : $my_query->the_post();
				$terms_category = wp_get_post_terms(get_the_ID(), 'university_location');
			?>
			<a href="<?php echo the_permalink(); ?>">
				<div class="col-md-12 project-box">
					<div class="project-inner">
						<div class="row">
							<div class="col-md-12">
								<p class="project-heading"><?php the_title(); ?></p>
							</div>
						</div>
						<?php the_excerpt(); ?>
						<div class="col-md-12 tag-listing">
								<?php foreach ($terms_category as $term) {
									echo '<span class="diploma-tag"><i class="fa fa-tag" aria-hidden="true"></i><span id="' . $term->term_id . '"';
									echo ' style="white-space: nowrap;">' . $term->name . '</span></span>';
									}?>
						</div>
					</div>
				</div>
			</a>
            <?php endwhile;
            add_filter('term_description','wpautop');
			?>
			</div>
	</section>
	</main><!-- #main -->
	</div><!-- #primary -->
</article><!-- #post-## -->
<?php get_footer();
