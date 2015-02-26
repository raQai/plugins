<?php namespace KF\EVENTMANAGER;

defined ( 'ABSPATH' ) or die ( 'nope!' );

/**
 * Plugin Name: KF Event Manager
 * Description: A simple tool to manage foosball events
 * Version: 1.0.0
 * Author: Patrick Bogdan
 * Text Domain: kftm
 * License: GPL2
 *
 * Copyright 2015 Patrick Bogdan
 * TODO: MetaBoxBooking load tickets
 * TODO: MetaBox_Abstract explode and store to / update / delete from db
 */

// load classes
load_template( plugin_dir_path ( __FILE__ ) . 'classes/ClassLoader.php' );

new KFEventPostType( );

class KFEventPostType {
	const title 		= 'Veranstaltungen';
	const post_type 	= 'events';
	const textdomain 	= 'kf_em';
	const slabel 		= 'Event';
	const plabel 		= 'Events';
	const ticket_tab	= 'kfem_tickets'; 
	protected $menu_parent_set;
		
	public function __construct() {
		$this->menu_parent_set 	= false;
		
		if ( is_admin () ) {
			// load scripts and styles to back end
			add_action( 'admin_enqueue_scripts', wp_enqueue_style ( 'kfem-admin-style', plugins_url ( 'includes/css/admin-styles.css', plugin_basename ( __FILE__ ) ) ) );
			add_action( 'admin_enqueue_scripts', wp_enqueue_script ( 'kfem-admin-js', plugins_url ( 'includes/js/kfem-admin-scripts.js', plugin_basename ( __FILE__ ) ) ) );
		}
		
		register_activation_hook( __FILE__, array( $this, 'kfem_create_page' ) );
		register_activation_hook( __FILE__, array( $this, 'kfem_create_db_tables' ) );
		register_deactivation_hook( __FILE__, array( $this, 'kfem_drop_db_tables' ) );

		add_action( 'init', array( $this, 'kfem_register_custom_post' ) );
		add_action( 'init', array( $this, 'kfem_register_custom_taxonomy' ) );
		
		add_filter( 'nav_menu_css_class', array( $this, 'kfem_menu_classes' ), 10, 2 );
		
		// Create MetaBoxes
		new MetaBoxDate( 'Datum und Zeit', MetaBoxContext::SIDE, MetaBoxPriority::HIGH );
		new MetaBoxResults( 'Ergebnisse', MetaBoxContext::SIDE, MetaBoxPriority::HIGH );
		new MetaBoxBooking( 'Voranmeldungen', MetaBoxContext::NORMAL, MetaBoxPriority::HIGH );
		new MetaBoxLocation( 'Veranstaltungsort', MetaBoxContext::NORMAL, MetaBoxPriority::HIGH );
	}
	
	function kfem_register_custom_post() {
		$labels = array(
				'name' 					=> __( self::title, self::textdomain ),
				'singular_name' 		=> __( self::slabel, self::textdomain ),
				'menu_name' 			=> __( self::plabel, self::textdomain ),
				'add_new_item' 			=> __( 'Add New ' . self::slabel, self::textdomain ),
				'edit_item' 			=> __( 'Edit ' . self::slabel, self::textdomain ),
				'new_item' 				=> __( 'New ' . self::slabel, self::textdomain ),
				'view_item' 			=> __( 'View ' . self::slabel, self::textdomain ),
				'search_items' 			=> __( 'Search ' . self::plabel, self::textdomain ),
				'not_found' 			=> __( 'No ' . self::plabel . ' found', self::textdomain ),
				'not_found_in_trash' 	=> __( 'No ' . self::plabel . ' found in trash', self::textdomain ),
				'parent_item_colon' 	=> __( 'Parent ' . self::slabel, self::textdomain )
		);
		$args = array(
				'labels' 		=> $labels,
				'public' 		=> true,
				'has_archive' 	=> true,
				'menu_position' => 20.168,
				'menu_icon' 	=> 'dashicons-calendar-alt', 
				'supports' 		=> array( 'title', 'editor' ),
				'rewrite' 		=> array( 'slug' => self::post_type )
		);
	
		register_post_type( self::post_type, $args );
	}
	
	function kfem_register_custom_taxonomy() {
		$args = array (
				'label' 			=> 'Event Kategorien',
				'hierarchical' 		=> true,
				'show_admin_column' => true
		);
		register_taxonomy ( self::post_type, self::post_type, $args );
	}
	
	/*
	 * Register new page
	 */
	function kfem_create_page() {
		$page = array (
				'post_content' 	=> 'Dieser Inhalt wird durch das KF EVENT MANAGER PLUGIN &uuml;berschrieben.<br />Der Titel kann beliebig bearbeitet werden.<br />Die Seite darf nicht in der Seitenhierarchie verschoben werden!',
				'post_name' 	=> self::post_type,
				'post_title' 	=> self::title,
				'post_type' 	=> 'page',
				'post_status' 	=> 'publish',
				'post_date' 	=> date( 'Y-m-d H:i:s' )
		);
	
		$ID = wp_insert_post( $page );
	
		// save the id in the database
		update_option( self::post_type, $ID );
	}

	function kfem_create_db_tables() {
		global $wpdb;
		$ticket_table = $wpdb->prefix . self::ticket_tab;
		$checkdb = $wpdb->query('CREATE TABLE IF NOT EXISTS ' . $ticket_table . ' (
			id int(11) NOT NULL auto_increment,
			postID int(11) NOT NULL,
			name VARCHAR(32) NOT NULL,
			price VARCHAR(10),
			spots VARCHAR(10),
			PRIMARY KEY (id)
		);');
		return $checkdb;
	}

	function kfem_drop_db_tables() {
		global $wpdb;
		$ticket_table = $wpdb->prefix . self::ticket_tab;
		$wpdb->query('DROP TABLE IF EXISTS ' . $ticket_table . ';');
	}

	/*
	 * Apply filters
	 */
	// update navigation items to display active page propperly
	function kfem_menu_classes( $classes , $item ) {
	
		if ( get_post_type() == self::post_type ) {
			if ( $item->url == get_site_url() . '/' . self::post_type . '/' ) {
				$classes[] = ' current-menu-item';
			}
		}
		return $classes;
	}
}
