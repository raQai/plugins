<?php namespace KF\EVENTMANAGER;

defined ( 'ABSPATH' ) or die ( 'nope!' );
class MetaBoxResults extends MetaBox_Abstract {
	function kfem_display_meta_box_html($post) {
		echo 'input file Ergebnisse';
	}

  function kfem_save_meta_box_fields( $post_id ) {
    return $post_id;
  }
}
