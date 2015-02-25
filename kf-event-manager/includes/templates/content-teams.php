<?php defined( 'ABSPATH' ) or die( 'nope!' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'kf-tm-team' ); ?>>
	<?php
		// Page thumbnail and title.
		if (has_post_thumbnail ()) {
			the_post_thumbnail ();
		} else {
			the_title ();
		}
	?>

	<div class="entry-content">
		<?php
			the_content();

			edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->