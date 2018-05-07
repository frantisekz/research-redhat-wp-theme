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

	while ($my_query->have_posts()) : $my_query->the_post();
	$terms_uni = wp_get_post_terms(get_the_ID(), 'parrent_university');
	$terms_category = wp_get_post_terms(get_the_ID(), 'these_category');

	// $this_id -> id aktualniho topicu
	$this_id = (string)(get_the_ID());

	// $post_name -> hodnota podle ktere budeme filtrovat/hledat shody
	$sql = $wpdb->get_results("SELECT post_name FROM wp_posts WHERE id={$this_id}", ARRAY_A);
	$merged_sql = array_merge(...$sql);
	$post_name = $merged_sql['post_name'];

	// $sql_same -> dotaz na db aby nasla shodu
	$sql_same = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_name='{$post_name}' AND post_type='theses'");

	// $count -> pocet shod
	$count = count($sql_same);

	$temp_terms = wp_get_post_terms($this_id, 'topic_allowed_applicants');

	if (!empty($temp_terms)) {
		foreach ($temp_terms as $temp_term) {
			$max_applicants = $temp_term->name;
			break;
		}
	} else {
		$max_applicants = 999;
	}

	if ($count < $max_applicants) {
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
