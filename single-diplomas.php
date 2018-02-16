<?php
/**
 * The template for displaying all single posts.
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

	<div id="primary" class="content-area col-sm-9 col-md-9">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<div class="text-center">
			<?php
				// We need to get meta early because of doing another query
				$author_name = get_the_author_meta('display_name');
				$author_team = get_the_author_meta('rh_team');
				$author_location = get_the_author_meta('parrent_rh_office');

				$this_id = get_the_ID(); // Save current ID
				$args=array(
					'post_type' => 'theses',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'ignore_sticky_posts' => 1);
				$my_query = new WP_Query($args);
				$this_id = get_the_ID();

				if( $my_query->have_posts() ) {
					$thesis_with_this = 0;
					while ($my_query->have_posts()) : $my_query->the_post();
						$terms_topics = wp_get_object_terms(get_the_ID(), 'diplomas');
						foreach ($terms_topics as $terms_topic) {
							if ($terms_topic->term_id == $this_id) {
								$thesis_with_this = $thesis_with_this + 1;
							}
						}
					endwhile;
				}
				$temp_terms = wp_get_post_terms($this_id, 'topic_allowed_applicants');
				foreach ($temp_terms as $temp_term) {
					$max_applicants = $temp_term->name;
					break;
				}
				if (!isset($max_applicants)) {
					$max_applicants = $thesis_with_this + 1;
				}

				if ($thesis_with_this < $max_applicants) {
					if((is_user_logged_in()) && (function_exists('Ninja_Forms'))) { 
						echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#apply_for_topic">
						Apply for this topic
					</button>';
					}

					else {
						// get_permalink() doesn't work here, this is ugly :(
						$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						echo '<div class="text-center">
								<strong>You must be logged in to perform this action!</strong><br>
								<a href="' . wp_login_url($actual_link) . '"><button class="btn btn-primary">Login to apply for this topic</button></a><br/>	
							</div>';
					}
				}

				else {
					echo '<strong>This topic is no longer accepting new applications!</strong>';
				}
				?>
			</div>

			<section id="project_coordinator">
				<div class="row">
						<?php // echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', array('class' => 'img-circle img-margin') ); ?>
					<div class="col-md-6" style="padding-left: 2em;">
						<h3><?php echo $author_name; ?></h3>
						<strong>Team: </strong><?php echo $author_team; ?><br/>
						<strong>Location: </strong><?php echo $author_location; ?><br/>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#get_in_touch_modal">
							Get in Touch!
						</button>
					</div>
					<div class="col-md-6 single-project-meta">
						<?php
						$args=array(
							'post_type' => 'theses',
							'post_status' => 'publish',
							'posts_per_page' => -1,
							'ignore_sticky_posts' => 1);
						$thesis_query = new WP_Query($args);

						if( $thesis_query->have_posts() ) {
							if ($thesis_with_this != 0) {
								echo '<strong>Diploma theses with this Topic: </strong><br/>';
								while ($thesis_query->have_posts()) : $thesis_query->the_post();
								$terms_topics = wp_get_object_terms(get_the_ID(), 'diplomas');
								foreach ($terms_topics as $terms_topic) {
									if ($terms_topic->term_id == $this_id) {
										echo '<a href="' . get_permalink() . '">' . get_the_title() . "</a><br/>";
									}
								}
								endwhile;
							}
						}
						?>
					</div>
				</div>
			</section>

			<?php endwhile; // we need to reset the loop here, or ugly things are going to happen below in the form ?>

			<section id="contact-after-project">
				<?php
				while ( have_posts() ) : the_post(); // And start the loop again, new and shiny one
				if((is_user_logged_in()) && (function_exists('Ninja_Forms'))) { 
					?>
					<div class="modal fade" tabindex="-1" role="dialog" id="apply_for_topic">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Thesis Application</h4>
						</div>
						<div class="modal-body">
							<?php Ninja_Forms()->display($DIPLOMA_FORM_ID); ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
				<?php
				} ?>

				<div class="modal fade" tabindex="-1" role="dialog" id="get_in_touch_modal">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Get in touch!</h4>
						</div>
						<div class="modal-body">
							<?php if (function_exists('Ninja_Forms')) { Ninja_Forms()->display($AFTER_DIPLOMA_FORM_ID); } ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
			</section>
		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<div class="col-md-3" style="margin-top: 20px;">

	<?php 
		$terms_uni = wp_get_post_terms(get_the_ID(), 'parrent_university');
		echo '<span class="university">';
		foreach ($terms_uni as $term) {
			echo '<i class="fa fa-fw fa-university" aria-hidden="true"></i> ' . $term->name . '<br/>';	
		}
		echo '</span>'; ?>
	</div>

<?php // get_sidebar(); ?>
<?php get_footer();
