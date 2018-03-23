<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area col-sm-12">
		<main id="main" class="site-main" role="main">
			<h1><?php the_title(); ?></h1>
	<section id="rnd-listing">
			<div class="row">
			<?php
			$generic_terms_place = get_terms(['taxonomy' => 'parrent_university', 'hide_empty' => false]);
              foreach ($generic_terms_place as $generic_term_place) { ?>
			  	<div onclick="window.location='<?php echo get_home_url() . '/diploma_topics/?university=' . $generic_term_place->term_id ?>';" class="col-md-12 project-box">
			  		<div class="project-inner">
				  		<div class="row">
						<div class="col-md-12">
							<p style="font-weight: 700;"><?php echo $generic_term_place->name ?></p>
						</div>
					</div>
			</div>
			</div>
			  <?php } ?>
			</div>
			</div>
			</div>
	</section>
	</main><!-- #main -->
	</div><!-- #primary -->
</article><!-- #post-## -->
<?php get_footer();
