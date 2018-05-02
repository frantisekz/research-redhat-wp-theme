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

				// $my_query -> vypis vsech diplomas pro vykreslovani topics
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
				$my_query = new WP_Query($args);

				// $my_query_calc -> vypis vsech theses pro pocitani povolenych uchazecu (pouzijeme jako jednu z promennych pri vypoctu)
				$args_calc=array(
					'post_type' => 'theses',
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
				$my_query_calc = new WP_Query($args_calc);

				remove_filter('term_description','wpautop');

				while ($my_query->have_posts()) : $my_query->the_post();
				$terms_uni = wp_get_post_terms(get_the_ID(), 'parrent_university');
				$terms_category = wp_get_post_terms(get_the_ID(), 'these_category');

				if( $my_query->have_posts() ) {

					// $this_id -> id aktualniho topicu
					$this_id = get_the_ID();
					// $this_id = '1946';

					// $post_name -> hodnota podle ktere budeme filtrovat/hledat shody
					$sql = $wpdb->get_results("SELECT post_name FROM wp_posts WHERE id={$this_id}", ARRAY_A);
					$merged_sql = array_merge(...$sql);
					$post_name = $merged_sql['post_name'];
					// var_dump($post_name);

					// $sql_same -> dotaz na db aby nasla shodu
					$sql_same = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_name='{$post_name}' AND post_type='theses'");

					// $count -> pocet shod
					$count = count($sql_same);

					// var_dump($count);

				}

				$temp_terms = wp_get_post_terms($this_id, 'topic_allowed_applicants');

				// var_dump($temp_terms);

				foreach ($temp_terms as $temp_term) {
					$max_applicants = $temp_term->name;
					break;
				}

				if (!isset($max_applicants)) {
					$max_applicants = $count + 1;
				}

				 var_dump($count);
				 var_dump($max_applicants);

				if ($count < $max_applicants) {
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

						// zajisti prepsani prislusne diploma na 0/1 (podle stejneho post_name -> $post_name_change)
						$sql_change = $wpdb->get_results("SELECT post_name, post_type, active FROM wp_posts WHERE id={$this_id}", ARRAY_A);
						$merged_sql_change = array_merge(...$sql_change);
						$post_name_change = $merged_sql['post_name'];

						$sql_change = "UPDATE wp_posts SET active='1' WHERE post_name='{$post_name_change}' AND post_type='diplomas'";

						if ($conn->query($sql_change) === TRUE ) {
						   $last_id = $conn->insert_id;
						} else {
						   echo "Error: " . $sql_change . "<br>" . $conn->error;
						}

						$conn->close();
					}
				}

				exit;

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
