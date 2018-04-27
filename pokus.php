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
						<button class="btn btn-white btn-default dropdown-toggle" type="button" id="university_drop_down" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
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
						<input type="search" value="<?php echo esc_html( get_search_query() ); ?>" size="20" name="s" placeholder="Title/Tag/Description....">
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


				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// changes


				// $post_ids obsahuje id vsech zobrazenych topicu
				$post_ids = get_posts(array(
					'fields'        => 'ids', // Only get post IDs
					'post_type' => 'diplomas',
					'post_status' => 'publish',
					'orderby'     => 'modified',
					'order' => 'DESC',
					'posts_per_page' => -1,
					'ignore_sticky_posts' => 1,
					'these_not_full'=> 'yes',

				));
				

				// zabrani nekonecnemu cyklu - neni trvale reseni
				function wpse_add_new_post_id_to_table( $post_id ) {
					global $wpdb;

					$post_status = get_post_status( $post_id );

					if ( 'publish' != $post_status )
						return false;

					$wpdb->insert( 'wp_posts', array( 'post_id' => $post_id ) );

				}
				add_action( 'wp_insert_post', 'wpse_add_new_post_id_to_table' );

				
				foreach ($post_ids as $post_id) {
					$this_id = $post_id;
					// proc vezme jen jeden topic ?
					if( $my_query->have_posts() ) {
						$thesis_with_this = 0;
						while ($my_query->have_posts()) : $my_query->the_post();
						$terms_topics = $post_id;

						foreach ($terms_topics as $terms_topic) {
							$this_id = $terms_topic;
							if ($terms_topic->term_id == $this_id) {
								$thesis_with_this = $thesis_with_this + 1;
							}
							/*
							var_dump($this_id);
							var_dump($terms_topic);
							exit;
							*/
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
					
					var_dump($thesis_with_this);
					var_dump($max_applicants);
					exit;
					
					
					if ($thesis_with_this < $max_applicants) {
						if((is_user_logged_in()) && (function_exists('Ninja_Forms'))) {


							$servername = "localhost";
							$username = "root";
							$password = "123456";
							$dbname = "research_beta";

							// Create connection
							$conn = new mysqli($servername, $username, $password, $dbname);
							// Check connection
							if ($conn->connect_error) {
								die("Connection failed: " . $conn->connect_error);
							}



							$sql = "UPDATE wp_posts SET active='1' WHERE id={$this_id}";
							var_dump($this_id);



							if ($conn->query($sql) === TRUE) {
								$last_id = $conn->insert_id;
							} else {
								echo "Error: " . $sql . "<br>" . $conn->error;
							}

							$conn->close();


						}
					}
				}

				while ($my_query->have_posts()) : $my_query->the_post();
				$terms_uni = wp_get_post_terms(get_the_ID(), 'parrent_university');
				$terms_category = wp_get_post_terms(get_the_ID(), 'these_category');
				$this_id = get_the_ID();
				var_dump($this_id);

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
