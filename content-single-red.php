<?php
/**
 * @package Red Hat Blog Theme
 */
?>

<style>
.hentry {
	margin: 0;
	padding: 0;
	border-bottom: 0px solid grey;
}
</style>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header listing-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->