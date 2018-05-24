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
<div class="row">

	<div class="col-md-6">
		<div class="dropdown" style="float: left;">
		<button class="btn btn-white btn-default dropdown-toggle" type="button" id="university_drop_down_common" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			All Universities
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="university_drop_down">
				<?php 
				$generic_terms_place = get_terms(['taxonomy' => 'parrent_university', 'hide_empty' => false]);
				foreach ($generic_terms_place as $generic_term_place) {
					echo '<li onclick="trigger_box(' . $generic_term_place->term_id . ', 1)" class="university_drop_down" id="trigger-' . $generic_term_place->term_id . '"><a href="#">' . $generic_term_place->name . '</a></li>';
				}?>
		</ul>
		</div>

		<div class="dropdown" style="float: left;">
		<button class="btn btn-white btn-default dropdown-toggle" type="button" id="spec_drop_down" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			All Specializations
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="spec_drop_down">
				<?php 
				$generic_terms_tags = get_terms(['taxonomy' => 'these_category', 'hide_empty' => false]);
				foreach ($generic_terms_tags as $generic_term_tag) {
					echo '<li onclick="trigger_box(' . $generic_term_tag->term_id . ', 0)" class="spec_drop_down" id="trigger-' . $generic_term_tag->term_id . '"><a href="#">' . $generic_term_tag->name . '</a></li>';
				}?>
		</ul>
		</div>
	</div>

	<div class="col-md-2">
		<button class="btn btn-white btn-default master-reset-btn" onclick="reset_filter(1)">Reset Filters</button>
	</div>

	<div class="col-md-4 text-right">
		<form class="search" action="<?php echo home_url( '/' ); ?>">
				<input type="search" value="<?php echo esc_html( get_search_query() ); ?>" size="25" name="s" placeholder="Title/Tag/Description....">
				<button type="submit" class="btn btn-white btn-default">Search</button>
				<input type="hidden" name="post_type" value="diplomas">
		</form>
	</div>

</div>

			<div class="row">
			<?php
			  $args=array(
            'post_type' => 'diplomas',
			'post_status' => 'publish',
			'orderby'     => 'modified',
            'posts_per_page' => -1,
            'ignore_sticky_posts' => 1,
            'these_not_full'=> 'yes',
		    'tax_query' => array(
				array(
					'taxonomy' => 'topic_allowed_applicants',
					'field' => 'slug',
					'terms' => array(
						'0'
					),
					'operator' => 'NOT IN'
					)));
			  $my_query = null;
        	  $my_query = new WP_Query($args); 
			  remove_filter('term_description','wpautop');

              while ($my_query->have_posts()) : $my_query->the_post();
              $terms_uni = wp_get_post_terms(get_the_ID(), 'parrent_university');
			  $terms_category = wp_get_post_terms(get_the_ID(), 'these_category');
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
				<div style="visibility: hidden; position: absolute; bottom: 2.2em; font-size: 1.0em;">
					<?php foreach ($terms_uni as $term) {
						echo '<i class="fa fa-university" aria-hidden="true"></i><span id="' . $term->term_id . '"';
						echo ' style="white-space: nowrap;">' . $term->name . '</span>';
					} ?>
				</div>
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
