<?php namespace KF\TEAMMANAGER;

defined ( 'ABSPATH' ) or die ( 'nope!' );

/**
 * Plugin Name: KF Team Manager
 * Description: A simple tool to manage teams
 * Version: 1.0.0
 * Author: Patrick Bogdan
 * Text Domain: kftm
 * License: GPL2
 *
 * Copyright 2015 Patrick Bogdan
 * TODO: find parent navigation elements automatically (kftm_menu_classes)
 * TODO: is_single() template
 */

new KFTeamPostType();

class KFTeamPostType {
	const title 		= 'Mannschaften';
	const post_type 	= 'teams';
	const textdomain 	= 'kf_tm';
	const slabel 		= 'Team';
	const plabel 		= 'Teams';
	protected $menu_parent_set;
		
	public function __construct() {
		$this->menu_parent_set 	= false;
		
		register_activation_hook( __FILE__, array( $this, 'kftm_create_page' ) );
		
		add_action( 'init', array( $this, 'kftm_register_custom_post' ) );
		
		add_filter( 'archive_template', array( $this, 'kftm_get_archive_template' ) ) ;
		add_filter( 'posts_orderby', array( $this, 'kftm_orderby' ) );
		add_filter( 'nav_menu_css_class', array( $this, 'kftm_menu_classes' ), 10, 2 );
	}
	
	/*
	 * Register post type
	 */
	function kftm_register_custom_post() {
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
				'menu_icon' 	=> 'dashicons-groups',
				'supports' 		=> array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
				'rewrite' 		=> array( 'slug' => self::post_type )
		);
	
		register_post_type( self::post_type, $args );
	}
	
	/*
	 * Register new page
	 */
	function kftm_create_page() {
		$page = array (
				'post_content' 	=> 'Dieser Inhalt wird durch das KF TEAM MANAGER PLUGIN &uuml;berschrieben.<br />Der Titel kann beliebig bearbeitet werden.<br />Die Seite darf nicht in der Seitenhierarchie verschoben werden!',
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
	
	/*
	 * Create Filters
	 */
	// change archive template
	function kftm_get_archive_template( $archive_template ) {
		if ( is_post_type_archive( self::post_type ) ) {
			$archive_template = dirname( __FILE__ ) . '/includes/templates/archive-teams.php';
		}
		return $archive_template;
	}
	
	// change post order to menu_order
	function kftm_orderby( $orderby ) {
		return 'menu_order ASC';
	}
	
	// update navigation items to display active page propperly
	function kftm_menu_classes( $classes , $item ) {
	
		if ( get_post_type() == self::post_type ) {
			if ( $item->url == get_site_url() . '/' . self::post_type . '/' ) {
				$classes[] = ' current-menu-item';
			}
			if ( $item->url == get_site_url() . '/verein/' && !$this->menu_parent_set ) {
				$classes[] = ' current-menu-item';
				$this->menu_parent_set = true;
			}
		}
		return $classes;
	}
}
