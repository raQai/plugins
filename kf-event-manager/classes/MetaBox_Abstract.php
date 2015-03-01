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
		$fields = $this->kfem_display_meta_box_html( $post );
	}

	function kfem_save_meta_box( $post_id ) {
		if ( !isset( $_POST[$this->ID . '_nonce'] ) || ! wp_verify_nonce( $_POST[$this->ID . '_nonce'], basename ( __FILE__ ) ) ) {
			return $post_id;
		}

		$post_type = get_post_type_object( self::post_type );

		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

    $this->kfem_save_meta_box_fields( $post_id );

    return $post_id;
	}

  abstract function kfem_save_meta_box_fields( $post_id );

	abstract function kfem_display_meta_box_html( $post );
}

