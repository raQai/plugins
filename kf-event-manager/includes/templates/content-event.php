<?php defined( 'ABSPATH' ) or die( 'nope!' ); 
	global $wpdb;
	$table = $wpdb->prefix . 'kfem_tickets';
	$tickets = $wpdb->get_col( 'SELECT name FROM ' . $table . ' WHERE postID = ' . get_the_id() );
	$is_active = get_post_meta( get_the_id(), 'kf-em-booking-checkbox', true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'kf-em-event' ); ?>>
	<div class="kfem-time">
		<?php
			$date = DateTime::createFromFormat( 'Y-m-d', get_post_meta( get_the_id(), 'kf-em-date-date', true ) );
			echo $date->format( 'd.m.Y' ) . '<br />';
			echo get_post_meta( get_the_id(), 'kf-em-date-start', true ) . ' Uhr';
		?>
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
