<?php
/*
Plugin Name: WP-IRLP
Plugin URI: http://www.K0RET.com/WP-IRLP
Description: This plugin has a set of tools for IRLP repeaters. Right now, it just has a widget that displays an IRLP repeater's status.
Version: 0.1
Author: Ryan Turner
Author URI: http://www.K0RET.com/
License: GPLv2
*/
include('node.php');

class wp_my_plugin extends WP_Widget {

	// constructor
    function wp_my_plugin() {
        parent::WP_Widget(false, $name = __('WP-IRLP', 'wp_irlp') );
    }

	// widget form creation
	function form($instance) {
	
	// Check values
	if( $instance) {
	     $number = esc_attr($instance['text']);
	} else {
	     $number = '';
	}
	?>

	<p>
	<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Node Number:', 'wp_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
	</p>
	<?php
	}

	// update widget
	function update($new_instance, $old_instance) {
	      $instance = $old_instance;
	      // Fields
	      $instance['number'] = strip_tags($new_instance['number']);
	     return $instance;
	}

	// display widget
	function widget($args, $instance) {
		extract( $args );

		$number = $instance['number'];
		
		echo $before_widget;


		echo '<div class="widget-text wp_widget_plugin_box">';
		
		if( $number )
		{
			$node = new Node($number);
?>
<h3><?php echo $node->callsign; ?> <small>#<?php echo $node->number; ?></small></h3>
<?php echo $node->base; ?>, offset <?php echo $node->offset; ?><br />
<a href="https://maps.google.com/maps?q=<?php echo $node->latitude; ?>,+<?php echo $node->longitude; ?>&um=1&ie=UTF-8&sa=N&tab=wl"><?php echo $node->city; ?>, <?php echo $node->province; ?>, <?php echo $node->country; ?></a><br />
<?php echo $node->status; ?>
<?php
		} else {
			echo "Please configure the widget by adding a valid node number!";
		}
		echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));
?>