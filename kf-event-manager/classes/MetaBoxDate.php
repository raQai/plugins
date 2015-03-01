<?php namespace KF\EVENTMANAGER;

defined( 'ABSPATH' ) or die( 'nope!' );

class MetaBoxDate extends MetaBox_Abstract {

	function kfem_display_meta_box_html($post) {

		$fields[] = 'kf-em-date-date';
		$fields[] = 'kf-em-date-open';
		$fields[] = 'kf-em-date-start';

    foreach ( $fields as $field ) {
      $values[] = get_post_meta( $post->ID, $field, true );
    }
		?>
    <div class="kf-meta-box-side">
      <label for="<?php echo $fields[0]; ?>">Datum</label>
      <input required type="date" class="kf-meta-box-date" placeholder="dd.mm.jjjj"
        name="<?php echo $fields[0]; ?>"
        value="<?php echo $values[0]; ?>"
        />
    </div>
    <div class="kf-meta-box-side">
      <label for="<?php echo $fields[1]; ?>">&Ouml;ffnung</label>
      <input required type="time" class="kf-meta-box-time" placeholder="hh:mm"
        name="<?php echo $fields[1]; ?>"
        value="<?php echo $values[1]; ?>"
        />
    </div>
    <div class="kf-meta-box-side">
      <label for="<?php echo $fields[2]; ?>">Start</label>
      <input required type="time" class="kf-meta-box-time" placeholder="hh:mm"
        name="<?php echo $fields[2]; ?>"
        value="<?php echo $values[2]; ?>"
        />
    </div>
    <?php
    return $fields;
	}

  function kfem_save_meta_box_fields( $post_id ) {

    $new_meta_value = ( isset( $_POST['kf-em-date-date'] ) ? $_POST['kf-em-date-date'] : '' );
    update_post_meta( $post_id, 'kf-em-date-date', $new_meta_value );

    $new_meta_value = ( isset( $_POST['kf-em-date-open'] ) ? $_POST['kf-em-date-open'] : '' );
    update_post_meta( $post_id, 'kf-em-date-open', $new_meta_value );

    $new_meta_value = ( isset( $_POST['kf-em-date-start'] ) ? $_POST['kf-em-date-start'] : '' );
    update_post_meta( $post_id, 'kf-em-date-start', $new_meta_value );

    return $post_id;
  }
}

