<?php defined( 'ABSPATH' ) or die( 'nope!' ); 
	global $wpdb;
	$table = $wpdb->prefix . 'kfem_tickets';
  $sql = 'SELECT name FROM ' . $table . ' WHERE postID = ' . get_the_id(); 
	$tickets = $wpdb->get_col( $sql );
	$is_active = get_post_meta( get_the_id(), 'kf-em-booking-checkbox', true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'kf-em-event' ); ?>>
	<div class="kfem-time">
		<?php
      $start = get_post_meta( get_the_id(), 'kf-em-start', true );
      echo date( 'd.m.Y', $start ) . '<br />';
      echo date( 'H:i', $start );
		?>
	</div>
  <div class="kfem-cat">
    <?php the_taxonomies( array( 'template' => '% %l' ) ); ?>
  </div>
	<div class="kfem-info">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php
				the_title();
				if ( $is_active ) {
					echo ' ( ' . implode( ', ', $tickets ) . ' )';
				}
				echo '<br />&raquo;Weitere Infos'; 
				if ( $is_active ) {
					echo ' und Voranmeldung';
				}
			?>
		</a>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
