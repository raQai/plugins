<?php namespace KF\EVENTMANAGER;

defined( 'ABSPATH' ) or die( 'nope!' );

class MetaBoxBooking extends MetaBox_Abstract
{
	function kfem_display_meta_box_html( $post )
	{
		$this->fields[] = 'kf-em-booking-checkbox';
		$this->fields[] = 'kf-em-ticket-type';
		
		$dummy[] = array('ID' => '0', 'name' => 'OD', 'price' => '5,00', 'spots' => '36' );
		$dummy[] = array('ID' => '1', 'name' => 'ND', 'price' => '10,00', 'spots' => '36' );
		$dummy[] = array('ID' => '2', 'name' => 'AD', 'price' => '15,00', 'spots' => '36' );
		
		$tickets = $dummy;
		unset($dummy);
		
		// 		TODO $tickets = get tickets from db
		//		FORMAT: array( 'ID' => i, 'name' => n, 'price' => p, 'spots' => s )
		$settings = array(
				'is_active' => get_post_meta( $post->ID, $this->fields[0], true ),
				'type' => get_post_meta( $post->ID, $this->fields[1], true ),
				'string' => $this->kfem_implode( $tickets )
		);
		
		?>
		<div class="kf-meta-box-checkbox">
			<input onClick="kfem_checkboxDivDisplay( this.id, 'kf-em-booking' ); kfem_creaetLinksString();" <?php if ( $settings['is_active'] ) echo 'checked '; ?>type="checkbox" id="<?php echo $this->fields[0]?>" name="<?php echo $this->fields[0]?>" value="1" />
			<label id="kf-em-booking-checkbox-label" for="kfem-booking-checkbox">Voranmeldungen aktivieren</label>
		</div>
		<div id="kf-em-booking" <?php if ( !$settings['is_active'] ) echo 'style="display:none" '; ?>>
			<div class="kf-em-ticket-type">
				<p class="kf-em-description">Typ des Turniers:</p>
				<input onChange="kfem_changeLabelText( 'kf-em-ticket-label-max', 'Max. Teams' );kfem_createBookingString();" required <?php if ( $settings['type'] != 1 ) echo 'checked '; ?>type="radio" name="<?php echo $this->fields[1]; ?>" value="2" />
				<lable for="kf-em-ticket-type-double">Doppel</lable>
				<input onChange="kfem_changeLabelText( 'kf-em-ticket-label-max', 'Max. Teilnehmer' );kfem_createBookingString();" required <?php if ( $settings['type'] == 1 ) echo 'checked '; ?>type="radio" name="<?php echo $this->fields[1]; ?>" value="1" />
				<lable for="kf-em-ticket-type-single">Einzel</lable>
				<p class="kf-em-info">Bitte Doppel- und Einzeldisziplinen in getrennten Veranstaltungen erstellen.</p>
			</div>
			<div class="kf-em-ticket-header">
				<label>Name</label>
				<label>Preis in &euro;</label>
				<!-- check for kf-em-ticket-type -->
				<label id="kf-em-ticket-label-max" >Max. <?php echo ( ( $settings['type'] != 1 ) ? 'Teams' : 'Teilnehmer' ); ?></label>
			</div>
			<div id="kf-em-tickets">
				<?php 
				foreach ( $tickets as $ID => $ticket )
				{
					$this->kfem_ticket_display_html( $ID, $ticket, $settings['is_active'] );
				}
				if ( count( $tickets ) < 1 ) {
					$this->kf_links_item_display_html( 0, array( 'ID' => 'new', 'name' => '' ), false );
				}
				?>
			</div>
			<h4><a href="#kf-em-ticket-add" onClick="kfem_createTicket()">+ Weitere Disziplin hinzufügen</a></h4>
			<input type="hidden" id="kf-em-ticket-counter" value="<?php echo count($tickets); ?>" />
			<input type="hidden" name="kf-em-tickets-information" id="kf-em-tickets-information" value="<?php echo $settings['string'] ?>" />
		</div>
		<?php
	}
	
	private function kfem_implode( $tickets ) {
		foreach ( $tickets as $ticket ) {
			$string_arr[] = implode( ';', $ticket );
		}
		return implode( '-', $string_arr );
	}
	
	private function kfem_ticket_display_html( $ID, $ticket, $is_active )
	{
		?>
		<div id="kf-em-ticket-item[<?php echo $ID; ?>]" class="kf-em-ticket-item">
			<input type="hidden" value="<?php echo $ticket['ID']; ?>" />
			<input onChange="kfem_createBookingString();"<?php if ( $is_active ) echo ' required'; ?> value="<?php echo $ticket['name']; ?>" placeholder="Name" />
			<input onChange="kfem_createBookingString();"<?php if ($ticket['price'] > 0 ) echo ' value="' . $ticket['price'] . '"'; ?> placeholder="frei" />
			<input onChange="kfem_createBookingString();"<?php if ($ticket['spots'] > 0 ) echo ' value="' . $ticket['spots'] . '"'; ?> placeholder="unbeschränkt"/>
			<input onClick="kfem_deleteTicket( 'kf-em-ticket-item[<?php echo $ID; ?>]' ); kfem_createBookingString()" value="&cross;" type="button" class="button button-small button-primary" />
		</div>
		<?php
	}
}