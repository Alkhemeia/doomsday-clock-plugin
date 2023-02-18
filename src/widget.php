<?php 
	// Register and load the widget
	function bdc_wpb_load_widget() {
		register_widget( 'bdc_doomsday_clock_wpb_widget' );
	}
	add_action( 'widgets_init', 'bdc_wpb_load_widget' );

	// Creating the widget 
	if (class_exists('WP_Widget')) {
	class bdc_doomsday_clock_wpb_widget extends WP_Widget {
 
		function __construct() {
			parent::__construct('wpb_widget', __('Doomsday Clock', 'wpb_widget_domain'), 
		
			// Widget description
			array('description'=>__('Widget display doomsday clock', 'wpb_widget_domain' ), ));
		}
	
	
		public function widget($args, $instance) {
			$title = apply_filters('widget_title', $instance['title']);
			$bgcolor = $instance['clockcolor'];
			
			// before and after widget arguments are defined by themes
			echo $args['before_widget'];
			if (!empty($title)) {
				echo $args['before_title'] . '<span class="bdc-title" style="color:'.$bgcolor.';">' . $title . '</span>' . $args['after_title']; 
			}
			
			if (function_exists('bdc_get_doomsday_clock')) {
				$clock_data = bdc_get_doomsday_clock();
			}
			
			// This is where you run the code and display the output
			//echo __( 'Doomsday Clock', 'wpb_widget_domain' );
			
			if(!empty($clock_data)){
				echo '<span class="bdc-text" style="color:'.$bgcolor.';">'.$clock_data.'</span>';
			}
			echo $args['after_widget'];
			
		}
			
		// Widget Backend 
		public function form($instance) {
			if (isset($instance['title'])) {
				$title = $instance['title']; 
			}
			else {
				$title = __('Doomsday Clock', 'wpb_widget_domain');
			}

			if (isset( $instance[ 'clockcolor' ])) {
				$clockcolor = $instance['clockcolor']; 
			}
			else {
				$clockcolor = '#ffffff';
			}

			// Widget admin form
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'clockcolor' ); ?>"><?php _e( 'Clock Color:' ); ?></label> 
				<input style="width: 80px; vertical-align: middle;" class="widefat" id="<?php echo $this->get_field_id('clockcolor'); ?>" name="<?php echo $this->get_field_name('clockcolor'); ?>" type="color" value="<?php echo esc_attr($clockcolor); ?>" />
			</p>    
			<?php 
		}
		
		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
			$instance['clockcolor'] = (!empty( $new_instance['clockcolor'])) ? strip_tags( $new_instance['clockcolor'] ) : '';	
			return $instance;
		}
	}
}