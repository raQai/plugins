<?php namespace KF\EVENTMANAGER;

defined( 'ABSPATH' ) or die( 'nope!' );

class MetaBoxDate extends MetaBox_Abstract {

	function kfem_display_meta_box_html($post) {

		$fields[] = 'kf-em-date-date';
		$fields[] = 'kf-em-date-open';
		$fields[] = 'kf-em-date-start';

    $open = get_post_meta( $post->ID, 'kf-em-open', true );
    $start = get_post_meta( $post->ID, 'kf-em-start', true );
    $values[] = date( 'Y-m-d', $open );
    $values[] = date( 'H:i', $open );
    $values[] = date( 'H:i', $start );
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

    $date = $_POST['kf-em-date-date'];
    $opentime = $_POST['kf-em-date-open'];
    $starttime = $_POST['kf-em-date-start'];
    $open = strtotime( $date . $opentime );
    $start = strtotime( $date . $starttime );

    update_post_meta( $post_id, 'kf-em-open', $open );
    update_post_meta( $post_id, 'kf-em-start', $start );

    return $post_id;
  }
}

