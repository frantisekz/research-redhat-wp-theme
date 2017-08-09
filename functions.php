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

function is_secure_ssl() {
	if ($_SERVER['SERVER_PORT'] == 443) {
		return true;
	}
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

wp_enqueue_style( 'awesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css' );


add_action( 'ninja_forms_display_pre_init', 'rh_ninja_forms_listing_agent' );
function rh_ninja_forms_listing_agent( $form_id ) {
	global $ninja_forms_loading;
	if( 7 == $form_id ) { /* 7 is id of form to be used , it should be used only on single projects! */
		$field_id = 15; /* Hidden field will be filed with project coordinator */
		$ninja_forms_loading->update_field_value($field_id, antispambot(get_the_author_meta('email')));
    $field_id = 16; /* Hidden field will be filled with project title */
		$ninja_forms_loading->update_field_value($field_id, antispambot( get_the_title()));
	}
}

// Front sidebar (actually site-wide)
function front_scripts() {
    wp_register_script( 'tpl_scripts', get_stylesheet_directory_uri().'/js/scripts.js', array('jquery'), '', true );
    wp_enqueue_script( 'tpl_scripts' );
    wp_register_script( 'jq_simulate', get_stylesheet_directory_uri().'/js/jquery.simulate.js', array('jquery'), '', true );
    wp_enqueue_script( 'jq_simulate' );
}
add_action( 'wp_enqueue_scripts', 'front_scripts' );

function wpdocs_custom_excerpt_length( $length ) {
    return 35;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

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
        <th><label for="parrent_rh_office"><?php _e("City name of parrent Red Hat office"); ?></label></th>
        <td>
            <input type="text" name="parrent_rh_office" id="parrent_rh_office" value="<?php echo esc_attr( get_the_author_meta( 'parrent_rh_office', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your Red Hat Office City Name (eg. Brno, Boston, etc..)."); ?></span>
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
}

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
