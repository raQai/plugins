<?php namespace KF\EVENTMANAGER;

defined( 'ABSPATH' ) or die( 'nope!' );

class MetaBoxLocation extends MetaBox_Abstract
{
	function kfem_display_meta_box_html( $post )
	{
		$this->fields[] = 'kf-em-location-is-extern';
		$this->fields[] = 'kf-em-location-name';
		$this->fields[] = 'kf-em-location-street';
		$this->fields[] = 'kf-em-location-city';
		?>

		<div class="kf-meta-box-checkbox">
			<input type="checkbox" id="kf-em-location-checkbox" onClick="kfem_checkboxDivDisplay( this.id, 'kf-em-location' )" name="<?php echo $this->fields[0]; ?>" value="yes" <?php if ( get_post_meta( $post->ID, $this->fields[0], true ) != '') echo 'checked '; ?>/>
			<label id="kf-em-location-checkbox-label" for="kf-em-location-checkbox">Externe Veranstaltung</label>
		</div>
		<div id="kf-em-location" <?php if ( get_post_meta( $post->ID, $this->fields[0], true ) == '') echo 'style="display:none" '; ?>>
			<div class="kf-meta-box">
				<label id="<?php echo $this->fields[1]; ?>-label" for="<?php echo $this->fields[1]; ?>">Name</label>
				<input type="text" id="<?php echo $this->fields[1]; ?>" name="<?php echo $this->fields[1]; ?>" value="<?php echo get_post_meta( $post->ID, $this->fields[1], true ); ?>" placeholder="Veranstaltungsort" />
			</div>
			<div class="kf-meta-box">
				<label id="<?php echo $this->fields[2]; ?>-label" for="<?php echo $this->fields[2]; ?>">Straße</label>
				<input type="text" id="<?php echo $this->fields[2]; ?>" name="<?php echo $this->fields[2]; ?>" value="<?php echo get_post_meta( $post->ID, $this->fields[2], true ); ?>" placeholder="Str. #NR" />
			</div>
			<div class="kf-meta-box">
				<label id="<?php echo $this->fields[3]; ?>-label" for="<?php echo $this->fields[3]; ?>">Ort/PLZ</label>
				<input type="text" id="<?php echo $this->fields[3]; ?>" name="<?php echo $this->fields[3]; ?>" value="<?php echo get_post_meta( $post->ID, $this->fields[3], true ); ?>" placeholder="PLZ Ort" />
			</div>
		</div>
	<?php
	}
}
