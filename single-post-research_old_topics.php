<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */
die();
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
            'post_type' => 'diplomas',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'ignore_sticky_posts' => 1,
            );
			  $my_query = null;
        	  $my_query = new WP_Query($args);
			  remove_filter('term_description','wpautop');

			  while ($my_query->have_posts()) : $my_query->the_post();
			  $past_event = 0;
			  $date = get_the_date();
			  echo '<h1>Will compare past 2 years(' . strtotime('-2 years') . ') with post_date(' . strtotime($date) . ')</h1>';
			  if (strtotime('-3 years') > strtotime($date)) {
				  $past_event = 1;
			  }
              ?>
              <div onclick="window.location='<?php echo the_permalink(); ?>';" class="col-md-12 project-box" <?php if ($past_event == 0) {echo 'style="display: none;"';} ?>>
			  <div class="project-inner">
				  <div class="row">
						<div class="col-md-12">
							<p class="project-heading"><?php the_title(); ?></p>
						</div>
					</div>
			<h1><?php echo $date; ?></h1>
			<?php if ($past_event == 1) {
				#delete me
				echo 'Will attempt to delete ' . get_the_ID() . '<br/>';
				#wp_update_post(array('ID' => get_the_ID(), 'post_status' => 'draft'));
			}
			?>
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
