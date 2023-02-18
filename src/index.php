<?php
/*
Plugin Name: MORI Doomsday Clock
Plugin URI: https://memento.mori.rip/doomsday-clock-wordpress-plugin/
Description: Embed a live Doomsday clock on your WordPress website.
Author: MEMENTO MORI
Version: 1.0.1
Author URI: https://memento.mori.rip

*/


if (!class_exists('simple_html_dom_node')) {
    require_once plugin_dir_path(__FILE__).'simple_html_dom.php';
}

if (!class_exists('bdc_doomsday_clock_wpb_widget')) {
    require_once plugin_dir_path(__FILE__).'widget.php';
}

function bdc_get_doomsday_clock(){
	if (class_exists('simple_html_dom')) {
		$html = new simple_html_dom();
		//$html_data = bdc_requestUrl('https://thebulletin.org/doomsday-clock/'); 
		$response = wp_remote_get( 'https://thebulletin.org/doomsday-clock/' );
		$html_data = $response['body'];
		
		$minuts_midnight_value = false;
		if($html_data){
			$html->load($html_data);
			$count = 1;
			foreach($html->find('span.fl-heading-text') as $clock_text){
				if($count == 1){
					$text = ucfirst(trim(strip_tags($clock_text->innertext))); 
				}
				$count++;
			}
		}
		
		return $text;
	}
}

// function bdc_doomsday_clock_head() { 
// }

function bdc_doomsday_clock_shortcode($atts) {
	$shortcode_data = shortcode_atts(array('color' => '#ffffff'), $atts);
   	$color =  $shortcode_data['color'];
   
   	ob_start();
  
   	if (function_exists('bdc_get_doomsday_clock')) {
		$clock_data = bdc_get_doomsday_clock();
	}
		
	if(!empty($clock_data)){
		/* 
		if($color){		
				?>	
				<style>
					#bdc_mtmclock <?php echo $id;?> circle {
					fill: <?php echo $color; ?>;
					} 
					#bdc_mtmclock<?php echo $id;?> path{
					fill: <?php echo $color; ?>;
					}
					#bdc_mtmclock<?php echo $id;?> polygon{
					fill: <?php echo $color; ?>;
					}
					#bdc_mtmclock<?php echo $id;?> rect{
					fill: <?php echo $color; ?>;
					}
				</style>
			<?php
		}
		*/
		// echo'<div style="text-align:center;">';
		// echo '<img  src="'.plugin_dir_url( __FILE__ ) . 'images/'.$clock_data['image'].'" id="bdc_mtmclock'.$id.'" class="svg" style="height:330px; ">';
		// echo'</div>';
		echo '<span style="text-align: center; color:'.$color.';">'.$clock_data.'</span>';
	}
	
	$output = ob_get_contents();
	ob_get_clean();
	return $output;
}

// add_action('wp_head', 'bdc_doomsday_clock_head');
add_shortcode('doomsday', 'bdc_doomsday_clock_shortcode');

	
	