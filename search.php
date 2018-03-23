<?php
/**
 * The template for displaying search results pages.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<section id="primary" class="content-area col-sm-12">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 style="color: white;" class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'rh_parent' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
			</header><!-- .page-header -->
			<?php include('diplomas_search_tpl.php'); ?>
			<?php /* Start the Loop */ ?>
			<div class="row">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				// get_template_part( 'content', 'search' );
				?>
			<div onclick="window.location='<?php echo the_permalink(); ?>';" class="col-md-12 project-box">
			  <div class="project-inner">
				  <div class="row">
						<div class="col-md-12">
							<p class="project-heading"><?php the_title(); ?></p>
						</div>
					</div>
			<?php the_excerpt(); ?>
			</div>
			</div>

			<?php endwhile; ?>
			</div>

			<?php
				if ( function_exists( 'wp_page_numbers' ) ) {
					wp_page_numbers();
				}
			?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer();