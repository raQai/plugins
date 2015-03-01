<?php namespace KF\EVENTMANAGER;

defined( 'ABSPATH' ) or die( 'nope!' );

class MetaBoxLocation extends MetaBox_Abstract
{
	function kfem_display_meta_box_html( $post )
	{
		$fields[] = 'kf-em-location-is-extern';
		$fields[] = 'kf-em-location-name';
		$fields[] = 'kf-em-location-street';
		$fields[] = 'kf-em-location-city';

    foreach ( $fields as $field ) {
      $values[] = get_post_meta( $post->ID, $field, true );
    }
		?>

		<div class="kf-meta-box-checkbox">
      <input type="checkbox" id="kf-em-location-checkbox" value="yes"
        onClick="kfem_checkboxDivDisplay(this.id, 'kf-em-location')"
        name="<?php echo $fields[0]; ?>"
        <?php if ( !empty($values[0] ) ) echo 'checked '; ?>
        >
			<label for="kf-em-location-checkbox">Externe Veranstaltung</label>
		</div>
		<div id="kf-em-location" <?php if ( empty($values[0]) ) echo 'style="display:none"'; ?>>
			<div class="kf-meta-box">
				<label for="<?php echo $fields[1]; ?>">Name</label>
        <input placeholder="Veranstaltungsort"
          name="<?php echo $fields[1]; ?>"
          value="<?php echo $values[1]; ?>"
          />
			</div>
			<div class="kf-meta-box">
				<label for="<?php echo $fields[2]; ?>">Straße</label>
        <input placeholder="Str. #NR"
          name="<?php echo $fields[2]; ?>"
          value="<?php echo $values[2]; ?>"
          />
			</div>
			<div class="kf-meta-box">
				<label for="<?php echo $fields[3]; ?>">Ort/PLZ</label>
				<input placeholder="PLZ Ort"
          name="<?php echo $fields[3]; ?>"
          value="<?php echo $values[3]; ?>"
          />
			</div>
		</div>
	<?php
    return $fields;
	}

  function kfem_save_meta_box_fields( $post_id ) {

    $new_meta_value = ( isset( $_POST['kf-em-location-is-extern'] ) ? $_POST['kf-em-location-is-extern'] : '' );
    update_post_meta( $post_id, 'kf-em-location-is-extern', $new_meta_value );

    $new_meta_value = ( isset( $_POST['kf-em-location-name'] ) ? $_POST['kf-em-location-name'] : '' );
    update_post_meta( $post_id, 'kf-em-location-name', $new_meta_value );

    $new_meta_value = ( isset( $_POST['kf-em-location-street'] ) ? $_POST['kf-em-location-street'] : '' );
    update_post_meta( $post_id, 'kf-em-location-street', $new_meta_value );

    $new_meta_value = ( isset( $_POST['kf-em-location-city'] ) ? $_POST['kf-em-location-city'] : '' );
    update_post_meta( $post_id, 'kf-em-location-city', $new_meta_value );

    return $post_id;
  }
}
