<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area col-sm-12 col-md-9">
		
		<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

		<?php endwhile; // end of the loop. ?>

		<section>
			<div class="row front-items-container">
			    <?php
					$tag = get_term_by('name', 'research_theses_sub', 'post_tag');
			    	$args=array(
						'orderby'=>'ID',
						'order'=>'ASC',
			            'tag__in' => $tag,
			            'posts_per_page'=>3, // Number of related posts to display.
			            'ignore_sticky_posts'=>1);

			        $my_query = new wp_query( $args );
			        while( $my_query->have_posts() ) {
			            $my_query->the_post();
			        ?>
			            <div class="col-md-4 col-sm-6 col-xs-12">
							<a href="<?php echo the_permalink(); ?>">
								<div class="front-item">
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
							</a>
			            </div>
			        <?php }
			        	wp_reset_query();
					?>
					</div>
		</section>

	</main><!-- #main -->
	</div><!-- #primary -->
	
	<div class="col-md-3" style="margin-top: 20px;">
		<?php include('diplomas_search_tpl.php'); ?>
	</div>
</article><!-- #post-## -->
<?php get_footer();
