<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

<style>
.em-location-map-container {
	display: none;
}

.project-inner p:first-of-type{
	float: left;
	margin-right: 25%;
	width: 40%;
}
</style>

	<div id="primary" class="content-area col-sm-12">

		<main id="main" class="site-main" role="main">
			<h1 style="color: white;"><?php the_title(); ?></h1>
	<section id="rnd-listing">
<div class="row">

	<div class="col-md-12 text-right">
		<form class="search" action="<?php echo home_url( '/' ); ?>">
				<input type="search" value="<?php echo esc_html( get_search_query() ); ?>" size="20" name="s" placeholder="Title/Tag/Description....">
				<button type="submit" class="btn btn-white btn-default">Search</button>
				<input type="hidden" name="post_type" value="event">
		</form>
	</div>

</div>

			<div class="row">
			<?php
			  $args=array(
            'post_type' => 'event',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'ignore_sticky_posts' => 1,
            );
			  $my_query = null;
        	  $my_query = new WP_Query($args);
			  remove_filter('term_description','wpautop');

			  while ($my_query->have_posts()) : $my_query->the_post();
			  $all_meta = get_post_meta(get_the_ID());
			  $past_event = 0;
			  if (strtotime('now') > strtotime($all_meta['_event_end_date'][0])) {
				  $past_event = 1;
			  }
              ?>
              <div onclick="window.location='<?php echo the_permalink(); ?>';" class="col-md-12 project-box" <?php if ($past_event == 1) {echo 'style="display: none;"';} else {echo 'style="display: flex;"';}?>>

				  <?php
				  	$thumb_id = get_post_thumbnail_id();
					$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
					$thumb_url = $thumb_url_array[0];
				  ?>

				<div class="event-img" style="background-image: url('<?php echo $thumb_url; ?>')">
					<div class="pattern">
					  <div class="picture">

					  </div>
					</div>
		        </div>

			  <div class="project-inner-events" >
				  <div class="row">
					  <div class="col-md-12">
						  <p class="project-heading"><?php the_title(); ?></p>
					  </div>
				  </div>
				<?php the_content(); ?>
			  </div>
			</div>
            <?php endwhile;
            add_filter('term_description','wpautop');
			?>
			</div>

			<hr>

			<div class="row">
			<?php
			  $args=array(
            'post_type' => 'event',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'ignore_sticky_posts' => 1,
            );
			  $my_query = null;
        	  $my_query = new WP_Query($args);
			  remove_filter('term_description','wpautop');

			  while ($my_query->have_posts()) : $my_query->the_post();
			  $all_meta = get_post_meta(get_the_ID());
			  $future_event = 0;
			  if (strtotime('now') < strtotime($all_meta['_event_end_date'][0])) {
				  $future_event = 1;
			  }
              ?>
              <div onclick="window.location='<?php echo the_permalink(); ?>';" class="col-md-12 project-box" <?php if ($future_event == 1) {echo 'style="display: none;"';} ?>>
			  <div class="project-inner past-project">
				  <div class="row">
						<div class="col-md-12">
							<p class="project-heading"><?php the_title(); ?></p>
						</div>
					</div>
			<?php the_content(); ?>
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
