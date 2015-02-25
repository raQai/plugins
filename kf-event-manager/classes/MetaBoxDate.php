<?php namespace KF\EVENTMANAGER;

defined ( 'ABSPATH' ) or die ( 'nope!' );
class MetaBoxDate extends MetaBox_Abstract {
	function kfem_display_meta_box_html($post) {
		$this->fields [] = 'kf-em-date-date';
		$this->fields [] = 'kf-em-date-open';
		$this->fields [] = 'kf-em-date-start';
		?>
<div class="kf-meta-box-side">
	<label id="<?php echo $this->fields[0]; ?>-label"
		for="<?php echo $this->fields[0]; ?>">Datum</label> <input required
		type="date" class="kf-meta-box-date"
		id="<?php echo $this->fields[0]; ?>"
		name="<?php echo $this->fields[0]; ?>"
		value="<?php echo get_post_meta( $post->ID, $this->fields[0], true ); ?>"
		placeholder="dd.mm.jjjj" />
</div>
<div class="kf-meta-box-side">
	<label id="<?php echo $this->fields[1]; ?>-label"
		for="<?php echo $this->fields[1]; ?>">&Ouml;ffnung</label> <input
		required type="time" class="kf-meta-box-time"
		id="<?php echo $this->fields[1]; ?>"
		name="<?php echo $this->fields[1]; ?>"
		value="<?php echo get_post_meta( $post->ID, $this->fields[1], true ); ?>"
		placeholder="hh:mm" />
</div>
<div class="kf-meta-box-side">
	<label id="<?php echo $this->fields[2]; ?>-label"
		for="<?php echo $this->fields[2]; ?>">Start</label> <input required
		type="time" class="kf-meta-box-time"
		id="<?php echo $this->fields[2]; ?>"
		name="<?php echo $this->fields[2]; ?>"
		value="<?php echo get_post_meta( $post->ID, $this->fields[2], true ); ?>"
		placeholder="hh:mm" />
</div>
<?php
	}
}