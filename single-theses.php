<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area col-sm-12 col-md-12">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>
			<?php
						$terms_parrent = wp_get_object_terms(get_the_ID(), 'diplomas');
						foreach ($terms_parrent as $term) {
							$topic_author_id = get_post_field('post_author', $term->term_id);
							break;
						}
						?>
			<section id="project_coordinator">
				<div class="row">
					<div style="display: none;" class="col-md-2">
						<?php // echo get_avatar($topic_author_id, 96, '', '', array('class' => 'img-circle img-margin') ); ?>
					</div>
					<div class="col-md-6">
						<h3>Leader: <?php echo get_the_author_meta('display_name', $topic_author_id) ?></h3>
						<strong>Team: </strong><?php echo get_the_author_meta('rh_team', $topic_author_id); ?><br/>
						<strong>Location: </strong><?php echo get_the_author_meta('parrent_rh_office', $topic_author_id); ?><br/>
						<?php
						echo '<strong>Topic: </strong>';
						foreach ($terms_parrent as $term) {
							echo '<a href="' . get_permalink($term->term_id) . '">' . $term->name . '</a>';
							break;
						} ?>
					</div>
					<div class="col-md-6">
						<?php 
						$terms_uni = wp_get_post_terms(get_the_ID(), 'parrent_university_student');
						$terms_grade = wp_get_post_terms(get_the_ID(), 'student_grade');
						$terms_type = wp_get_post_terms(get_the_ID(), 'these_type_student');
						$terms_date = wp_get_post_terms(get_the_ID(), 'defence_date');
						?>

						<h3>Student: <?php echo get_the_author_meta('display_name') ?></h3>
						<strong>University: </strong> <?php echo $terms_uni[0]->name; ?><br/>
						<strong>Type: </strong> <?php echo $terms_type[0]->name; ?><br/>
						<strong>Date of Defence: </strong> <?php echo $terms_date[0]->name; ?><br/>
						<strong>Grade: </strong> <?php echo $terms_grade[0]->name; ?><br/>
						<strong>Link: </strong> <?php // echo $terms_grade[0]->name; ?><br/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">

					</div>
				</div>
			</section>
		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php // get_sidebar(); ?>
<?php get_footer();
