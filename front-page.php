<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Red Hat Blog Theme
 */

get_header('front'); ?>

	<div id="primary" class="content-area col-sm-12">
		<div class="front-sidebar"><div class="arrow"><a id="enlrg"><strong>Upcoming events</strong></a><a id="ensml"><strong>Upcoming events</strong></a></div><?php if (is_active_sidebar('events_sidebar')) {dynamic_sidebar('events_sidebar');} ?></div>
		<section id="front-categories">
			<div class="container">
			<div class="row front-items-container">
			              <?php
										$tag = get_term_by('name', 'title_post', 'post_tag');
			                      $args=array(
																'orderby'=>'ID',
																'order'=>'ASC',
			                          'tag__in' => $tag,
			                          'posts_per_page'=>4, // Number of related posts to display.
			                          'caller_get_posts'=>1
			                      );

			                  $my_query = new wp_query( $args );

			                  while( $my_query->have_posts() ) {
			                      $my_query->the_post();
			                  ?>
			                  <div class="col-md-3 col-sm-6 col-xs-12">
			                    <div class="front-item" onclick="window.location='<?php echo the_permalink(); ?>';">
														<div class="front-thumb-img hidden-xs">
				                      <?php
				                      if ( has_post_thumbnail() ) {
				                          the_post_thumbnail("front-thumb");
				                      } ?>
														</div>
														<div class="front-meta">
				                      <h2 class="page-header text-center"><?php the_title(); ?></h2>
				                      <?php the_content(); ?>
														</div>
			                    </div>
			                  </div>
			                  <?php }
			                  wp_reset_query();
												?>
					</div>
				</div>
		</section>
		<section id="front-intro">
			<div class="container">
				<?php if (is_active_sidebar('home_intro')) {dynamic_sidebar('home_intro');} ?>
			</div>
		</section>
		<section id="front-pre-footer">
			<div class="container">
			<?php if (is_active_sidebar('home_pre_footer')) {dynamic_sidebar('home_pre_footer');} ?>
		</div>
		</section>
		</div><!-- #primary -->

	<?php //get_sidebar(); ?>

<?php get_footer();
