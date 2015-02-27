<?php namespace KF\EVENTMANAGER;

defined ( 'ABSPATH' ) or die ( 'nope!' );

abstract class MetaBoxContext {
	const NORMAL = 'normal';
	const ADVANCED = 'advanced';
	const SIDE = 'side';
}

abstract class MetaBoxPriority {
	const DEF = 'default';
	const HIGH = 'high';
	const CORE = 'core';
	const LOW = 'low';
}

abstract class MetaBox_Abstract {
	const post_type = 'events';
	protected $title;
	protected $ID;
	protected $context;
	protected $priority;
	protected $fields;
	
	public function __construct( $title, $context = MetaBoxContext::NORMAL, $priority = MetaBoxPriority::DEF ) {
		$this->title = esc_html__ ( $title );
		$this->ID = 'kfem_meta_box_' . sanitize_title( $this->title );
		$this->context = $context;
		$this->priority = $priority;
		
		$fields = array();
		
		add_action ( 'add_meta_boxes', array( $this, 'kfem_add_meta_box' ) );
		add_action ( 'save_post', array( $this, 'kfem_save_meta_box' ) );
	}
	
	function kfem_add_meta_box() {
		add_meta_box (
			$this->ID, // id
			$this->title, // title
			array( $this, 'kfem_display_meta_box' ), // callback
			self::post_type, // post_type
			$this->context, // context
			$this->priority // priority
		);
	}
	
	function kfem_display_meta_box( $post ) {
		wp_nonce_field ( basename ( __FILE__ ), $this->ID . '_nonce' );
		$this->kfem_display_meta_box_html( $post );
		echo '<input type="hidden" name="' . $this->ID . '_fields" value="' . implode ( ',', $this->fields ) . '" />';
	}
	
	function kfem_save_meta_box( $post_id ) {
		if ( !isset( $_POST[$this->ID . '_nonce'] ) || ! wp_verify_nonce( $_POST[$this->ID . '_nonce'], basename ( __FILE__ ) ) ) {
			return $post_id;
		}

		$post_type = get_post_type_object( self::post_type );

		$this->kfem_save_ticket_data( $post_id );

		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}
		foreach ( explode( ',', $_POST[$this->ID . '_fields'] ) as $field ) {
			$new_meta_value = ( isset( $_POST[$field] ) ? $_POST[$field] : '' );
			update_post_meta( $post_id, $field, $new_meta_value );
		}
	}
	
	function kfem_save_ticket_data( $post_id ) {
		if ( isset(	$_POST['kf-em-tickets-information'] ) ) {
			global $wpdb;
			$table = $wpdb->prefix . 'kfem_tickets';
			$ticket_ids = array();

			$ticket_strs = explode( '-', $_POST['kf-em-tickets-information'] );
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
			$table_ids = $wpdb->get_col( 'SELECT id FROM ' . $table . ' WHERE postID = ' . $post_id );
			foreach ( $table_ids as $table_id ) {
				if ( !in_array( $table_id, $ticket_ids ) ) {
					$wpdb->delete( $table, array( 'id' => $table_id ), array( '%d' ) );
				}
			}
		}
	}

	abstract function kfem_display_meta_box_html( $post );
}

