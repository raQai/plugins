<?php namespace KF\EVENTMANAGER;

defined( 'ABSPATH' ) or die( 'nope!' );

class MetaBoxBooking extends MetaBox_Abstract
{
	function kfem_display_meta_box_html( $post )
	{
		$fields[] = 'kf-em-booking-checkbox';
		$fields[] = 'kf-em-ticket-type';

		global $wpdb;
		$ticket_query = 'SELECT id, name, price, spots ';
		$ticket_query .= 'FROM wp_kfem_tickets ';
		$ticket_query .= 'WHERE postID = ' . $post->ID;

		$tickets = $wpdb->get_results( $ticket_query, ARRAY_A );

		if ( empty( $tickets ) ) {
			$settings = array(
				'is_active' => 0,
				'type' => 0,
				'string' => ''
			);
		} else {
			$settings = array(
				'is_active' => get_post_meta( $post->ID, $fields[0], true ),
				'type' => get_post_meta( $post->ID, $fields[1], true ),
				'string' => $this->kfem_implode( $tickets )
			);
		}
		?>
		<div class="kf-meta-box-checkbox">
      <input type="checkbox" id="<?php echo $fields[0]?>" value="1"
        onClick="kfem_checkboxDivDisplay(this.id,'kf-em-booking'); kfem_createBookingString()"
        name="<?php echo $fields[0]?>"
        <?php if ( $settings['is_active'] ) echo 'checked '; ?>
        />
			<label for="kfem-booking-checkbox">Voranmeldungen aktivieren</label>
		</div>
		<div id="kf-em-booking" <?php if ( !$settings['is_active'] ) echo 'style="display:none"'; ?>>
			<div class="kf-em-ticket-type">
				<p class="kf-em-description">Typ des Turniers:</p>
        <input required type="radio" value="2"
          onChange="kfem_changeLabelText('kf-em-ticket-label-max', 'Max. Teams'); kfem_createBookingString()"
          name="<?php echo $fields[1]; ?>"
          <?php if ( $settings['type'] != 1 ) echo 'checked '; ?>
          />
				<label>Doppel</label>
        <input required type="radio" value="1"
          onChange="kfem_changeLabelText('kf-em-ticket-label-max', 'Max. Teilnehmer'); kfem_createBookingString()"
          name="<?php echo $fields[1]; ?>"
          <?php if ( $settings['type'] == 1 ) echo 'checked '; ?>
          />
				<label>Einzel</label>
				<p class="kf-em-info">Doppel- und Einzeldisziplinen müssen in getrennten Veranstaltungen erstellt werden.</p>
			</div>
			<div class="kf-em-ticket-header">
				<label>Name</label>
				<label>Preis in &euro;</label>
				<label id="kf-em-ticket-label-max" >Max. <?php echo ( ( $settings['type'] != 1 ) ? 'Teams' : 'Teilnehmer' ); ?></label>
			</div>
			<div id="kf-em-tickets">
				<?php
				if ( empty( $tickets ) ) {
					$this->kfem_ticket_display_html( 0, array( 'id' => 'new', 'name' => '' ), false );
				} else {
					foreach ( $tickets as $ID => $ticket )
					{
						$this->kfem_ticket_display_html( $ID, $ticket, $settings['is_active'] );
					}
				}
				?>
			</div>
			<h4><a href="#kf-em-ticket-add" onClick="kfem_createTicket()">+ Weitere Disziplin hinzufügen</a></h4>
			<input type="hidden" id="kf-em-ticket-counter" value="<?php echo ( empty( $tickets ) ? 1 : count($tickets) ); ?>" >
			<input type="hidden" name="kf-em-tickets-information" id="kf-em-tickets-information" value="<?php echo $settings['string'] ?>" >
		</div>
		<?php
    return $fields;
	}

	private function kfem_ticket_display_html( $ID, $ticket, $is_active )
	{
		?>
		<div id="kf-em-ticket-item[<?php echo $ID; ?>]" class="kf-em-ticket-item">
			<input type="hidden" value="<?php echo $ticket['id']; ?>" />
			<input placeholder="Name"
				onChange="kfem_createBookingString();"
				<?php if ( $is_active ) echo ' required'; ?>
				<?php if ( !empty( $ticket['name'] ) ) echo ' value="' . $ticket['name'] . '"'; ?>
			>
			<input placeholder="frei"
				onChange="kfem_createBookingString();"
				<?php if ( $ticket['price'] > 0 ) echo ' value="' . $ticket['price'] . '"'; ?>
			>
			<input placeholder="frei"
				onChange="kfem_createBookingString();"
				<?php if ( !empty( $ticket['spots'] ) ) echo ' value="' . $ticket['spots'] . '"'; ?>
			>
			<input value="&cross;" type="button"
				class="button button-small button-primary"
				onClick="kfem_deleteTicket( 'kf-em-ticket-item[<?php echo $ID; ?>]' );kfem_createBookingString()"
			>
		</div>
		<?php
	}

	private function kfem_implode( $tickets ) {
		foreach ( $tickets as $ticket ) {
			$string_arr[] = implode( ';', $ticket );
		}
		return implode( '-', $string_arr );
	}

  function kfem_save_meta_box_fields( $post_id ) {

    $new_meta_value = ( isset( $_POST['kf-em-booking-checkbox'] ) ? $_POST['kf-em-booking-checkbox'] : '' );
    update_post_meta( $post_id, 'kf-em-booking-checkbox', $new_meta_value );

    $new_meta_value = ( isset( $_POST['kf-em-ticket-type'] ) ? $_POST['kf-em-ticket-type'] : '' );
    update_post_meta( $post_id, 'kf-em-ticket-type', $new_meta_value );

    $this->kfem_save_ticket_data_to_db( $post_id, $_POST['kf-em-tickets-information'] );

    return $post_id;
  }

	function kfem_save_ticket_data_to_db( $post_id, $ticket_data_str ) {
			global $wpdb;
			$table = $wpdb->prefix . 'kfem_tickets';
			$ticket_ids = [];
			$ticket_strs = explode( '-', $ticket_data_str );

			foreach ( $ticket_strs as $ticket_str ) {

				$ticket = explode( ';', $ticket_str );
				$ticket_data = array(
					'postID' => $post_id,
					'name' => $ticket[1],
					'price' => $ticket[2],
					'spots' => $ticket[3]
				);

				if ( $ticket[0] === 'new' ) {
					// add ticket to db if new
					$test = $wpdb->insert( $table, $ticket_data );
					$test2 = $wpdb->insert_id;
					$ticket_ids[] = $wpdb->insert_id;
				} else {
					// update tickets otherwise
					$wpdb->update( $table, $ticket_data, array( 'id' => $ticket[0] ) );
					$ticket_ids[] = $ticket[0];
				}
			}

			// delete tickets that are not present on submit
      $sql = 'SELECT id FROM ' . $table . ' WHERE postID = ' . $post_id;
			$table_ids = $wpdb->get_col( $sql );
			foreach ( $table_ids as $table_id ) {
				if ( !in_array( $table_id, $ticket_ids ) ) {
					$wpdb->delete( $table, array( 'id' => $table_id ), array( '%d' ) );
				}
			}
		}
}
