<?php
/**
 * The template for displaying These Approval Form
 *
 * @package Red Hat Blog Theme
 */

get_header(); ?>

<?php
// Sanitize $_GET variables
if (!is_numeric($_GET['topic_id'])) {
	$_GET['topic_id'] = -1;
	die('Malformed url!');
}

if (!is_numeric($_GET['student_id'])) {
	$_GET['student_id'] = -1;
	die('Malformed url!');
}
?>
	<div id="primary" class="content-area col-sm-12">
		<main id="main" class="site-main" role="main">
			<?php if (isset($_GET['action'])) {
				if ($_GET['action'] == 'approve') {
					if (is_able_to_aprove(get_current_user_id(), $_GET['topic_id'], $_GET['student_id'])) {
						$new_thesis_id = spawn_these($_GET['topic_id'], $_GET['student_id']);
						if ($new_thesis_id != -1) {
							rh_mail_approval_result($_GET['topic_id'], $new_thesis_id, $_GET['student_id'], 'approved');
							echo '<strong>Thesis approved succesfully! You can close this page now.</strong>';
						}
					}
					else {
						echo '<strong>Something went terribly wrong and thesis approval was not succesfull, contact site administrator!</strong>';
					}
				}
				else if ($_GET['action'] == 'reject') {
					rh_mail_approval_result($_GET['topic_id'], 0, $_GET['student_id'], 'rejected');
					echo 'Thesis was rejected, you can approve it later via the link in email informing about new these application.';
				}
			}
				else if ((is_user_logged_in()) && (isset($_GET['topic_id'])) && (isset($_GET['student_id']))) { ?>
					<h1>Thesis approval</h1>
					<div class="row" style="margin-bottom: 2em; border-bottom: 1px solid grey;">
						<div class="col-md-6"> Thesis Topic: </div><div class="col-md-6"><?php echo get_the_title($_GET['topic_id']);?></div>
						<div class="col-md-6"> Student Name: </div><div class="col-md-6"><?php echo get_the_author_meta('display_name', $_GET['student_id']);?></div>
						<div class="col-md-6"> University: </div><div class="col-md-6"><?php echo get_the_author_meta('university', $_GET['student_id']);?></div>
					</div>
					<?php if (is_able_to_aprove(get_current_user_id(), $_GET['topic_id'], $_GET['student_id'])) {
					echo '<div class="row"><div class="col-md-6 text-center"><a href="?student_id=' . $_GET['student_id'] . '&topic_id=' . $_GET['topic_id'] . '&action=approve"><button class="btn btn-primary">Approve</button></a></div>';
					echo '<div class="col-md-6 text-center"><a href="?student_id=' . $_GET['student_id'] . '&topic_id=' . $_GET['topic_id'] . '&action=reject"><button class="btn btn-primary">Reject</button></a></div></div>';
					}
					else {
						echo '<strong>Sorry, but you are not able to approve the Thesis. Contact site admin if you think that this is an error.</strong>';
					} ?>
			<?php }	else { ?>
					<?php $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
					<div class="text-center">
						<strong>You must be logged in to perform this action!</strong><br>
						<a href="<?php echo wp_login_url( $actual_link ); ?>"><button class="btn btn-primary">Login</button></a><br/>	
					</div>			
			<?php } ?>
	</main><!-- #main -->
	</div><!-- #primary -->
</article><!-- #post-## -->
<?php get_footer();
