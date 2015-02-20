<?php namespace KF\LINKS;

defined ( 'ABSPATH' ) or die ( 'nope!' );

/**
 * Plugin Name: KF Attachment Links
 * Description: Adding attatchemts to post, pages and teams (Kong Foos Team Manager)
 * Version: 1.0.0
 * Author: Patrick Bogdan
 * Text Domain: kfl
 * License: GPL2
 *
 * Copyright 2015 Patrick Bogdan
 * TODO: Settings for post_types with checkboxes
 */

new KFLinksMetaBox();

class KFLinksMetaBox {	
	const kfl_key = 'kf-links-information';
	
	function __construct() {
		if (is_admin ()) {
			add_action ( 'admin_enqueue_scripts', wp_enqueue_style ( 'kfl-admin-style', plugins_url( 'includes/css/admin-styles.css', plugin_basename( __FILE__ ) ) ) );
			add_action ( 'admin_enqueue_scripts', wp_enqueue_script ( 'kfl-admin-js', plugins_url( 'includes/js/kfl-admin-scripts.js', plugin_basename( __FILE__ ) ) ) );
		
			add_action ( 'add_meta_boxes', array( &$this, 'kf_links_meta_box_add' ) );
			add_action ( 'save_post', array( &$this, 'kf_links_meta_box_save' ) );
		}
		
		register_deactivation_hook( __FILE__, array( &$this, 'kf_links_uninstall' ) );
		
		add_filter( 'the_content', array( $this, 'kf_links_add_to_posts' ) );	
	}
	
	function kf_links_uninstall() {
		delete_post_meta_by_key( self::kfl_key );
	}
	
	function kf_links_add_to_posts( $content ) {
		$links = $this->kf_links_explode( get_post_meta( get_the_ID(), self::kfl_key, true ) );
		if ( $links['is_active'] == '1' && count($links['items']) > 0 ) {
			$links_html = '';
			if ( !empty($links['title']) ) {
				$links_html .= '<strong>' . $links['title'] . '</strong>';
			}
			foreach ( $links['items'] as $link ) {
				if ( !empty( $link['name'] ) && !empty( $link['url'] ) ) {
					$links_html .= '<br /><a href="' . $link['url'] . '">&raquo; ' . $link['name'] . '</a>';
				}
			}
			if ( !empty( $links_html ) ) {
				$content .= '<p class="attachment-links">' . $links_html . '</p>';
			}
		}
		return $content;
	}
	
	function kf_links_meta_box_add() {
		$screens = array( 'post', 'page', 'teams' );
		
		foreach( $screens as $screen)
		{
			add_meta_box (
				'kf_links_meta_box', // id
				'Linksammlung', // title
				array( &$this, 'kf_links_meta_box_display' ), // callback
				$screen, // post_type
				'normal', // context
				'high' // priority
			);
		}
	}
	
	function kf_links_meta_box_display( $post ) {
		wp_nonce_field( 'kf_links_meta_box', 'kf_links_meta_box_nonce' );
		$this->kf_links_meta_box_display_html( $post );
	}
	
	function kf_links_meta_box_display_html( $post )
	{
		$post_string = get_post_meta( $post->ID, self::kfl_key, true );
		$links = $this->kf_links_explode( $post_string );
	
		?>
		<div class="kf-meta-box-checkbox">
			<input onClick="kfl_checkboxDivDisplay( this.id, 'kf-links' ); kfl_creaetLinksString();" <?php if ( $links['is_active'] ) echo 'checked '; ?>type="checkbox" id="kf-links-checkbox" value="1" />
			<label id="kf-links-checkbox-label" for="kf-links-checkbox">Linksammlung aktivieren</label>
		</div>
		<div id="kf-links" <?php if ( !$links['is_active'] ) echo 'style="display:none" '; ?>>
			<div class="kf-meta-box-full">
				<label for="kf-links-title">Titel der Linksammlung</label>
				<input onChange="kfl_creaetLinksString()" id="kf-links-title" value="<?php echo $links['title']; ?>" placeholder="Titel der Linksammlung" />
			</div>
			<div class="kf-links-header">
				<label>Name</label>
				<label>URL</label>
			</div>
			<div id="kf-links-items">
				<?php 
				foreach ( $links['items'] as $ID => $arr ) {
					$this->kf_links_item_display_html( $ID, $arr, $links['is_active'] );
				}
				if ( count( $links['items'] ) < 1 ) {
					$this->kf_links_item_display_html( 0, array( 'name' => '', 'url' => '' ), false );
				}
				?>
			</div>
			<h4><a href="#kf-link-add" onClick="kfl_createLinkItem()">+ Weiteren Link hinzufügen</a></h4>
			<input type="hidden" id="kf-links-counter" value="<?php echo ( ( count($links['items']) < 1 ) ? 1 : count($links['items']) ); ?>" />
			<input type="hidden" name="<?php echo self::kfl_key; ?>" id="<?php echo self::kfl_key; ?>" value="<?php echo $post_string; ?>" />
		</div>
		<?php
	}
	
	function kf_links_item_display_html( $ID, $arr, $is_active )
	{
		?>
		<div id="kf-links-item[<?php echo $ID; ?>]" class="kf-links-item">
			<input onChange="kfl_creaetLinksString();" value="<?php echo $arr['name']; ?>" <?php if ( $is_active ) echo 'required '; ?>placeholder="Name" />
			<input onChange="kfl_creaetLinksString();" value="<?php echo $arr['url'];  ?>" <?php if ( $is_active ) echo 'required '; ?>placeholder="http://..." />
			<input onClick="kfl_deleteLink( 'kf-links-item[<?php echo $ID; ?>]' ); kfl_creaetLinksString();" value="&cross;" type="button" class="button button-small button-primary" />
		</div>
		<?php
	}
		
	function kf_links_meta_box_save($post_id)
	{
		if ( !isset( $_POST['kf_links_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['kf_links_meta_box_nonce'], 'kf_links_meta_box' )) {
			return $post_id;
		}
		
		$post_type = get_post_type_object( $_POST['post_type'] );
	
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return;
		}
		
		$new_meta_value = ( isset( $_POST[self::kfl_key] ) ? $_POST[self::kfl_key] : '');
		update_post_meta( $post_id, self::kfl_key, $new_meta_value );
	}
		
	function kf_links_explode( $string )
	{
		if ( empty($string) || !is_string($string) ) {
			$links['is_active'] = 0;
			return $links;
		}
		
		$explode = explode( ';$;', $string );
	
		$links['is_active'] = ( isset( $explode[0] ) ? $explode[0] : 0 );
		$links['title'] = ( isset( $explode[1] ) ? $explode[1] : '' );
	
		$links['items'] = array();
		for ( $i = 2; $i < count( $explode ); $i++ ) {
			$explode2 = explode( ';?;', $explode[$i] );
			$link = array(
					'name' 	=> $explode2[0],
					'url' => $explode2[1]
			);
			$links['items'][] = $link;
		}
		return $links;
	}
}