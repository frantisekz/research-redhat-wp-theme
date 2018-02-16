<?php
/**
 * @package Red Hat Blog Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content('',FALSE,''); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php // rh_parent_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
