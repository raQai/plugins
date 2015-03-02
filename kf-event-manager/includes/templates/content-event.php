<?php defined( 'ABSPATH' ) or die( 'nope!' ); 
	global $wpdb;
	$table = $wpdb->prefix . 'kfem_tickets';
  $sql = 'SELECT name FROM ' . $table . ' WHERE postID = ' . get_the_id(); 
	$tickets = $wpdb->get_col( $sql );
	$is_active = get_post_meta( get_the_id(), 'kf-em-booking-checkbox', true );
?>
<tr>
  <td>
    <?php
      $start = get_post_meta( get_the_id(), 'kf-em-start', true );
      echo date( 'd.m.Y', $start );
    ?>
  </td>
  <td>
    <?php the_taxonomies( array( 'template' => '% %l' ) ); ?>
  </td>
  <td>
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
  </td>
</tr>
<!-- .entry-content -->
