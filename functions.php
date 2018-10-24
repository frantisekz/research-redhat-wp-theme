<?php
/*
* Unlike style.css, the functions.php of a child theme does not override its counterpart from the parent.
* Instead, it is loaded in addition to the parent’s functions.php. (Specifically, it is loaded right before the parent theme's functions.php).
* Source: http://codex.wordpress.org/Child_Themes#Using_functions.php
*
* Be sure not to define functions, that already exist in the parent theme!
* A common pattern is to prefix function names with the (child) theme name.
* Also if the parent theme supports pluggable functions you can use function_exists( 'put_the_function_name_here' ) checks.
*/

/**
 * Fill these manually!!!!
 * Ninja Form IDs
*/
global $wpdb;
$create_table_set = "
   CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}research_config` (
       `id` bigint(20) unsigned NOT NULL,
       `diploma_form_id` int(20) NOT NULL default '0',
       `project_form_id` int(20) NOT NULL default '0',
       `after_diploma_form_id` int(20) NOT NULL default '0',
       `footer_contact_id` int(20) NOT NULL default '0',
       PRIMARY KEY (id)
   ) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4;
";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $create_table_set );

$post_id = $wpdb->get_results("SELECT diploma_form_id, project_form_id, after_diploma_form_id, footer_contact_id FROM wp_research_config");
$array = json_decode(json_encode($post_id), True);

$DIPLOMA_FORM_ID = (string)($array[0]['diploma_form_id']);
$PROJECT_FORM_ID = (string)($array[0]['project_form_id']);
$AFTER_DIPLOMA_FORM_ID = (string)($array[0]['after_diploma_form_id']);
$FOOTER_CONTACT_ID = (string)($array[0]['footer_contact_id']);

$RH_LISTINGS = array('diploma_theses', 'diploma_archive', 'diploma_universities', 'diploma_topics', 'rnd', 'research_news', 'internships_open-positions', 'partner-universities', 'academic-research-groups', 'academic-publications', 'research_events', 'high-school-internships', 'internships_filled-positions');

// Register Custom Navigation Walker
require_once('wp-bootstrap-navwalker.php');

function is_secure_ssl() {
	if ($_SERVER['SERVER_PORT'] == 443) {
		return true;
	}
}

// Set custom role names
function wps_change_role_name() {
	global $wp_roles;
	if ( ! isset( $wp_roles ) )
	$wp_roles = new WP_Roles();
	$wp_roles->roles['contributor']['name'] = 'Approved Student';
	$wp_roles->role_names['contributor'] = 'Approved Student';
	$wp_roles->roles['subscriber']['name'] = 'Student';
	$wp_roles->role_names['subscriber'] = 'Student';
	$wp_roles->roles['author']['name'] = 'Topic Author';
	$wp_roles->role_names['author'] = 'Topic Author';
	}
	add_action('init', 'wps_change_role_name');

function dequeue_broken_parrent_scripts() {
    wp_dequeue_script('rh_parent-main-scripts');
        wp_deregister_script('rh_parent-main-scripts');
}
add_action( 'wp_print_scripts', 'dequeue_broken_parrent_scripts' );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'awesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css' );
		wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css' );
}

function mark_menu_item_as_active($classes, $item) {
		if(in_array('diplomas-menu-btn', $classes) && ((is_singular('diplomas') || (is_singular('theses'))) || (is_page(array('diploma_archive', 'diploma_universities', 'diploma_topics', 'diploma_theses')))))   {
			$classes[] = 'current-menu-item';
		}
		return $classes;
	}
add_filter('nav_menu_css_class', 'mark_menu_item_as_active', 10, 2);

/**
 * Checks for ptential double-approval and prevents User from doing so
 * @param  [int] $leader_id Id of Diploma Thesis leader
 * @param  [int] $topic_id Id of DIploma Topic
 * @param  [int] $student_id Id of student
 * @return [int] 0 => Approval not possible; 1 => We didn't find why we shouldn't allow you to approve the thesis, you are good2go!
*/
function is_able_to_aprove($leader_id, $topic_id, $student_id) {
	$args = array(
		'author'        =>  $student_id,
		'orderby'       =>  'post_date',
		'order'         =>  'ASC',
		'post_type' 	=>  'theses'
		);

	$my_query = new WP_Query($args);
	while ($my_query->have_posts()) : $my_query->the_post();
		if (get_the_title() == get_the_title($topic_id)) {
			echo 'It looks like you have already approved this Thesis...<br/>';
			return 0;
		}
	endwhile;

	if (get_post_field('post_author', $topic_id) != $leader_id) {
		return 0;
	}
	return 1;
}

/**
 * Sends an email to student if his Diploma Application was or wasn't accepted
 * @param  [int] $topic_id The Topic Student applied for
 * @param  [int] $student_id The Id of student
 * @param  [str] $status Was these approved("approved") or not("rejected")?
 * @return [int] Mailing status 0 => sending email failed, take care of error handling; 1 => mail was sent succesfully
*/
function rh_mail_approval_result($topic_id, $thesis_id, $student_id, $status) {
	$student_email = get_the_author_meta('email', $student_id);
	if ($status == "approved") {
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$subject = 'Red Hat University Program - Application Approved!';
		$message = 'Your Thesis Application on research.redhat.com was Approved!<br/> You can edit your Thesis via <a href="' . get_home_url() . '/?p=' . $thesis_id . '">this link</a>.';
		if (wp_mail($student_email, $subject, $message, $headers)) {
			return 1;
		}
	}
	else if ($status == "rejected") {
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$subject = 'Red Hat University Program - Application Rejected!';
		$message = 'Your Thesis Application on research.redhat.com was Rejected!<br/>';
		if (wp_mail($student_email, $subject, $message, $headers)) {
			return 1;
		}
	}
	return 0;
}

/**
 * Spawns a copy of Diploma Topic as Diploma These
 * @param  [int] $post_id The Topic you want to clone
 * @return [int] The duplicated Post ID
  * TODO: Error reporting?
*/
function spawn_these($post_id, $student_id) {
		global $cpt_onomy;
		$title   = get_the_title($post_id);
		$topic_post = get_post($post_id);
		$post = array(
						'post_title'     => $topic_post->post_title,
						'post_type'      => $topic_post->post_type,
						'to_ping'        => $topic_post->to_ping,
						'menu_order'     => $topic_post->menu_order,
						'post_content'   => $topic_post->post_content,
						'post_excerpt'   => $topic_post->post_excerpt,
						'post_name'      => $topic_post->post_name,
						'post_parent'    => $topic_post->post_parent,
						'post_password'  => $topic_post->post_password,
						'post_type'      => 'theses',
						'post_status'    => 'publish',
						'comment_status' => $topic_post->comment_status,
						'ping_status'    => $topic_post->ping_status,
						'post_author'    => $student_id);
		$new_post_id = wp_insert_post($post);
		// Copy post metadata
		$data = get_post_custom($post_id);
		foreach ($data as $key => $values) {
			foreach ($values as $value) {
				update_post_meta($new_post_id, $key, $value);
			}
		}
		//update_post_meta($new_post_id, 'parrent_university_student', get_the_author_meta('university', $student_id));
		$cpt_onomy->wp_set_object_terms($new_post_id, $topic_post->post_name, 'diplomas');
		return $new_post_id;
	}

/**
 * Function of filtration of topics and saving student display_name into DB for further putting into taxonomy Student box
*/
function filtration_and_save_data($student_display_name, $these_post_name, $new_thesis_id, $student_id, $topic_id) {
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
    global $wpdb;

    /* DIPLOMAS - Will return difference ID's between wp_posts and wp_filtration for existing rows
     * $insert_post_sql -> will insert difference ID's between tables into wp_filtration based on array from $select_new_posts_diplomas
     */
         $select_new_posts_diplomas = $wpdb->get_results("SELECT l.ID FROM wp_posts l WHERE l.post_type='diplomas' AND l.post_status='publish'
                                           AND NOT EXISTS ( SELECT ID FROM wp_filtration r WHERE r.id = l.ID )");
         $new_posts_diplomas = array();
         foreach($select_new_posts_diplomas as $row) {
            $new_posts_diplomas[] = $row->ID;
         }
         $new_posts_ids_diplomas = implode( ",", $new_posts_diplomas );

         if ($new_posts_ids_diplomas != "") {
             $insert_post_sql = "INSERT INTO wp_filtration (`id`, `post_type`, `post_name`) SELECT `id`, `post_type`, `post_name` FROM wp_posts WHERE id IN ({$new_posts_ids_diplomas})";
             $wpdb->query($insert_post_sql);
         }

    /* THESES - Will return difference ID's between wp_posts and wp_filtration for existing rows
    * $insert_post_sql -> will insert difference ID's between tables into wp_filtration based on array from $select_new_posts_theses
    */
        $select_new_posts_theses = $wpdb->get_results("SELECT l.ID FROM wp_posts l WHERE l.post_type='theses' AND l.post_status='publish'
                                           AND NOT EXISTS ( SELECT ID FROM wp_filtration r WHERE r.id = l.ID )");
        $new_posts_theses = array();
        foreach($select_new_posts_theses as $row) {
            $new_posts_theses[] = $row->ID;
        }
        $new_posts_ids_theses = implode( ",", $new_posts_theses );

        if ($new_posts_ids_theses != "") {
            $insert_post_sql = "INSERT INTO wp_filtration (`id`, `post_type`, `post_name`) SELECT `id`, `post_type`, `post_name` FROM wp_posts WHERE id IN ({$new_posts_ids_theses})";
            $wpdb->query($insert_post_sql);
        }

    /* DELETE/SYNC OF THESES/DIPLOMAS - Will return difference ID's between wp_filtration and wp_posts for existing rows
     * $delete_post_sql -> will remove difference ID's between tables inside of wp_filtration based on array from $select_old_posts
     */
         $select_old_posts = $wpdb->get_results("SELECT l.id FROM wp_filtration l
                                           WHERE NOT EXISTS ( SELECT id FROM wp_posts r WHERE r.ID = l.id AND r.post_status='publish' )");
         $old_posts = array();
         foreach($select_old_posts as $row) {
            $old_posts[] = $row->id;
         }
         $old_posts_ids = implode( ",", $old_posts );

         if ( $select_new_posts_diplomas != "" || $select_new_posts_theses != "" ) {
             $delete_post_sql = "DELETE FROM wp_filtration WHERE id IN ({$old_posts_ids})";
             $wpdb->query($delete_post_sql);
         }

    // Will save name of student of theses
    $name_student_sql = "UPDATE wp_filtration SET student='$student_display_name' WHERE post_name='$these_post_name' AND post_type='theses'";
    $wpdb->query($name_student_sql);

    //wp_set_object_terms( 2568, $student_display_name, 'testimonial_service', false );
    wp_set_object_terms( $new_thesis_id, $student_display_name, 'student', false );
    wp_set_object_terms( $new_thesis_id, get_the_author_meta('university', $student_id), 'parrent_university_student', false );

    $uni_sup_diploma_value = wp_get_post_terms($topic_id, 'university_supervisor_diplomas');

    foreach( $uni_sup_diploma_value as $term ) {
        $uni_sup_diploma_value_sep = $term->name;
    }

    wp_set_object_terms( $new_thesis_id, $uni_sup_diploma_value_sep, 'university_supervisor', false );
}

// Load all the custom js scripts
function front_scripts() {
		wp_register_script( 'tpl_scripts', get_stylesheet_directory_uri().'/js/scripts.js', array('jquery'), '', true );
		wp_enqueue_script( 'tpl_scripts' );
		wp_register_script( 'bootstrap_js', get_stylesheet_directory_uri().'/js/bootstrap.min.js', array('jquery'), '', true );
		wp_enqueue_script( 'bootstrap_js' );
		wp_register_script( 'jq_simulate', get_stylesheet_directory_uri().'/js/jquery.simulate.js', array('jquery'), '', true );
		wp_enqueue_script( 'jq_simulate' );
}
add_action( 'wp_enqueue_scripts', 'front_scripts' );

function wpdocs_custom_excerpt_length( $length ) {
		return 100;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

// Provides shortcode for search form
function diplomas_search() {
    ob_start();
    get_template_part('diplomas_search_tpl');
    return ob_get_clean();
}
add_shortcode('diplomas_search', 'diplomas_search');

// Deprecated, to be removed!
function max_title_length($title){
	// Make title cutting more inteligent ;)
		$max = 140;
		if ($title != substr( $title, 0, $max )) {
			return substr( $title, 0, ($max-3) ). " &hellip;";
		}
		else {
			return $title;
		}
}

// Parrent Red Hat Office - Custom WP meta for wp_user
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
	<h3><?php _e("Extra profile information", "blank"); ?></h3>

	<table class="form-table">
		<tr>
			<td>
				<input type="text" name="parrent_rh_office" id="parrent_rh_office" value="<?php echo esc_attr( get_the_author_meta( 'parrent_rh_office', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description"><?php _e("Please enter your Red Hat Office City Name (eg. Brno, Boston, etc..)."); ?></span>
				</td>
				<td>
<select name="university">
<?php
	$selected = 0;
	$generic_terms_place = get_terms(['taxonomy' => 'parrent_university', 'hide_empty' => false]);
	foreach ($generic_terms_place as $generic_term_place) {
		if ($generic_term_place->name != esc_attr(get_the_author_meta('university', $user->ID))) {
			echo '<option value="'.$generic_term_place->name.'">' . $generic_term_place->name . '</option>';
		} else {
			$selected = 1;
			echo '<option selected="selected" value="'.$generic_term_place->name.'">' . $generic_term_place->name . '</option>';
		}
                   }
	if ($selected == 0) {
		echo '<option selected="selected" value="Default">Default</option>';
	}
?>
</select><br/>
					<span class="description"><?php _e("Your University"); ?></span>
				</td>
		</tr>
		<tr>
			<td>
				<input type="text" name="rh_team" id="rh_team" value="<?php echo esc_attr( get_the_author_meta( 'rh_team', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Your Team in Red Hat"); ?></span>
			</td>
			<td>
				<input type="text" name="university" id="university" value="<?php echo esc_attr( get_the_author_meta( 'university', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Your university"); ?></span>
			</td>
		</tr>
	</table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) ) {
				return false;
		}
		update_user_meta( $user_id, 'parrent_rh_office', $_POST['parrent_rh_office'] );
		update_user_meta( $user_id, 'university', $_POST['university'] );
		update_user_meta( $user_id, 'rh_team', $_POST['rh_team'] );
}

// Custom Registration Page
// add_action( 'register_form', 'research_register_form' );
function research_register_form() {

$university = ( ! empty( $_POST['university'] ) ) ? trim( $_POST['university'] ) : '';

        ?>
        <p>
            <label for="university"><?php _e( 'University', 'mydomain' ) ?><br />
                <input type="text" name="university" id="university" class="input" value="<?php echo esc_attr( wp_unslash( $university ) ); ?>" size="25" /></label>
        </p>
        <?php
    }

//2. Add validation. In this case, we make sure university is required. ... Not needed
// add_filter( 'registration_errors', 'research_registration_errors', 10, 3 );
function research_registration_errors( $errors, $sanitized_user_login, $user_email ) {

        if ( empty( $_POST['university'] ) || ! empty( $_POST['university'] ) && trim( $_POST['university'] ) == '' ) {
            $errors->add( 'university_error', __( '<strong>ERROR</strong>: You must include your University.', 'mydomain' ) );
        }

        return $errors;
    }

    //3. Finally, save our extra registration user meta.
// add_action( 'user_register', 'research_user_register' );
function research_user_register( $user_id ) {
        if ( ! empty( $_POST['university'] ) ) {
            update_user_meta( $user_id, 'university', trim( $_POST['university'] ) );
        }
    }


// Registration Page logo
function research_registration_logo() {
	?>
	<style type="text/css">
	body.login div#login h1 a {
	background-image: url(wp-content/themes/research-rh/rh_login_logo.png);
	padding-bottom: 30px;
	}
	</style>
	<?php
	} add_action( 'login_enqueue_scripts', 'research_registration_logo' );

// Widgets
function rh_widgets_init() {
	register_sidebar( array(
		'name'          => 'Front page - Heading',
		'id'            => 'home_heading',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<div style="display: none;">',
		'after_title'   => '</div>'
	) );
	register_sidebar( array(
		'name'          => 'Front page - Intro Post',
		'id'            => 'home_intro',
		'before_widget' => '<div class="row">',
		'after_widget'  => '</div>',
		'before_title'  => '<h1>',
		'after_title'   => '</h1>'
	) );
	register_sidebar( array(
		'name'          => 'Front page - Above Footer',
		'id'            => 'home_pre_footer',
		'before_widget' => '<div class="row">',
		'after_widget'  => '</div>',
		'before_title'  => '<div style="display: none;">',
		'after_title'   => '</div>'
	) );
	register_sidebar( array(
		'name'          => 'Events Sidebar',
		'id'            => 'events_sidebar',
		'before_widget' => '<div class="sidebar">',
		'after_widget'  => '<div class="all-events"onclick="window.location=\'' . get_site_url(null, "/events/", null) . '\';" >All Events</div></div>',
		'before_title'  => '<h4 style="display: none;">',
		'after_title'   => '</h4>'
	) );
}
add_action( 'widgets_init', 'rh_widgets_init' );
