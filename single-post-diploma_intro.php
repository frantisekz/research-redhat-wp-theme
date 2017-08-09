<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area col-sm-12">
		<div class="front-sidebar"><div class="arrow"><a id="enlrg"><strong>Upcoming events</strong></a><a id="ensml"><strong>Upcoming events</strong></a></div><?php if (is_active_sidebar('events_sidebar')) {dynamic_sidebar('events_sidebar');} ?></div>
		<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single-red' ); ?>

		<?php endwhile; // end of the loop. ?>

	<section id="rnd-listing">
		<button class="master-reset-btn" onclick="reset_filter(1)">Reset Filters</button><br/>
<div class="dropdown" style="float: left;">
  <button class="btn btn-default dropdown-toggle" type="button" id="university_drop_down" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
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
  <button class="btn btn-default dropdown-toggle" type="button" id="spec_drop_down" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
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

			<div class="row">
			<?php
			  $args=array(
            'post_type' => 'diplomas',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'caller_get_posts'=> 1,
            'these_not_full'=> 'yes');
			  $my_query = null;
        	  $my_query = new WP_Query($args); 
			  remove_filter('term_description','wpautop');
              while ($my_query->have_posts()) : $my_query->the_post();
              $terms_uni = wp_get_post_terms(get_the_ID(), 'parrent_university');
			  $terms_category = wp_get_post_terms(get_the_ID(), 'these_category');
              ?>
              <div onclick="window.location='<?php echo the_permalink(); ?>';" class="col-md-12 project-box">
			  <div class="project-inner">
				  <div class="row">
						<div class="col-md-7">
							<p style="font-weight: 700;"><?php the_title(); ?></p>
						</div>
						<div class="col-md-5">
							<?php foreach ($terms_category as $term) {
                    			echo '<span class="diploma-tag"><i class="fa fa-tag" aria-hidden="true"></i><span id="' . $term->term_id . '"';
                    			echo ' style="white-space: nowrap;">' . $term->name . '</span></span>';
               					}?>
						</div>
					</div>
			  <?php the_excerpt(); ?>
			  <div style="visibility: hidden; position: absolute; bottom: 2.2em; font-size: 1.0em;">
                <?php foreach ($terms_uni as $term) {
					if (strlen($term->name) > 45) {
						$safe_term = mb_substr($term->name, 0, 45);
					}
					else {
						$safe_term = $term->name;
					}
                    echo '<i class="fa fa-university" aria-hidden="true"></i><span id="' . $term->term_id . '"';
                    echo ' style="white-space: nowrap;">' . $safe_term . '</span>';
                } ?>
				</div>
			</div>
			</div>
            <?php endwhile;
            add_filter('term_description','wpautop');
			?>
			</div>
	</section>
	</main><!-- #main -->
	</div><!-- #primary -->
</article><!-- #post-## -->
<?php get_footer();
