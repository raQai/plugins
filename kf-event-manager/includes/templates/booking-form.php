<?php 
  $is_active = get_post_meta( get_the_id(), 'kf-em-booking-checkbox', true );
  if ( $is_active ) {
    $type = get_post_meta( get_the_id(), 'kf-em-ticket-type', true );
?>
<div id="kfem-register">
  <h4>Voranmeldung</h4>
  <div class="kfem-register-player">
    <label>Anmeldedaten</label><br />
    <input required name="kfem-p1-name" placeholder="Name" /><br />
    <input required name="kfem-p1-pre" placeholder="Vorname" />
  </div>
  <?php if ( $type != 1 ) { ?>
  <div class="kfem-register-player">
    <label>Mitspieler</label><br />
    <input required name="kfem-p2-name" placeholder="Name" /><br />
    <input required name="kfem-p2-pre" placeholder="Vorname" /><br />
    <input type="checkbox" name="kfem-p2-search" value="1" /><span>&nbsp;Mitspieler gesucht</span><br />
  </div>
<?php } ?>
  <div id="kfem-tickets">
<?php
		global $wpdb;
		$ticket_query = 'SELECT id, name, price ';
		$ticket_query .= 'FROM wp_kfem_tickets ';
		$ticket_query .= 'WHERE postID = ' . get_the_id();

		$tickets = $wpdb->get_results( $ticket_query, ARRAY_A );
    echo '<table>';
    echo '<tr><th>Disziplinen</th><th>Teilnahmegebühr</th></tr>';
    foreach ( $tickets as $ticket ) {
      $price = ( ( !empty($ticket['price']) ) ? $ticket['price'] . ' &euro;' : 'frei' );
      $ticket_html = '<tr class="kfem-ticket"><td>';
      $ticket_html .= '<input type="checkbox" name="kfem-ticket-' . $ticket['id'] . '" value="' . $ticket['id'] . '" />';
      $ticket_html .= '<span class="kfem-ticket-name">&nbsp;&nbsp;' . $ticket['name'] . '</span>';
      $ticket_html .= '</td><td>';
      $ticket_html .= '<span class="kfem-ticket-price">' . $price . '</span>';
      $ticket_html .= '</td></tr>';
      echo $ticket_html;
    }
    echo '</table>';
    ?>
    <input type="submit" value="Anmeldung abschicken" class="primary" />
  </div>
</div>
<?php } ?>
