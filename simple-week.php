<?php

/*
Plugin Name: SimpleWeek
Plugin URI: http://dylandoes.com/simple-week`
Description: Hmm...
Version: 0.1
Author: Dylan Wells
Author URI: http://dylandoes.com
*/

define( 'SIMPLEWEEK_PATH', dirname(__FILE__) );


// Post types
include SIMPLEWEEK_PATH . '/php/' . 'sw_category_post.php';
include SIMPLEWEEK_PATH . '/php/' . 'sw_class_post.php';

// Metaboxes
include SIMPLEWEEK_PATH . '/php/' . 'sw_color_picker.php';
include SIMPLEWEEK_PATH . '/php/' . 'sw_textboxes.php';
include SIMPLEWEEK_PATH . '/php/' . 'sw_metabox_category.php';
include SIMPLEWEEK_PATH . '/php/' . 'sw_metabox_timeday.php';


class SW_Init {

	public function __construct() {

	}

	public static function sw_add_admin_menu() {
		add_menu_page( 'SimpleWeek', 'SimpleWeek', 'manage_options',
			__FILE__, array('SW_Init','sw_options_page'), 'dashicons-calendar', 65 );
		SW_CategoryPost::sw_add_submenu(__FILE__);
		SW_ClassPost::sw_add_submenu(__FILE__);
	}

	public function sw_settings_init(  ) {

		register_setting( 'sw_plugin_options', 'sw_settings' );

		add_settings_section(
			'sw_plugin_options_section',
			'Main Settings',
			array($this,'sw_settings_section_callback'),
			'sw_plugin_options'
		);

		add_settings_field(
			'sw_text_field_0',
			'Text Field',
			array($this,'sw_text_field_0_render'),
			'sw_plugin_options',
			'sw_plugin_options_section'
		);

	}

	function sw_text_field_0_render(  ) {

		$options = get_option( 'sw_settings' );
		?>
		<input id="sw_text" type='text' name='sw_settings[sw_text_field_0]' value='<?php echo $options['sw_text_field_0']; ?>'>
	<?php

	}

	function sw_settings_section_callback() {

	}


	function sw_options_page(  ) {

		?>
		<form action='options.php' method='post' enctype="multipart/form-data">

			<h2>SimpleWeek</h2>

			<?php
			settings_fields( 'sw_plugin_options' );
			do_settings_sections( 'sw_plugin_options' );
			submit_button();
			?>

		</form>
	<?php

	}
}


function sw_increment_instance() {

	global $wpdb;

	$sw_instances = $_POST['sw_instances'];
	$sw_post_id = $_POST['sw_post_id'];

	update_post_meta( $sw_post_id, '_instances', $sw_instances );

	echo $sw_instances . ' [ID = ' . $sw_post_id . ']';

	wp_die();

}

add_action( 'wp_ajax_nopriv_sw_increment_instance', 'sw_increment_instance' );
add_action( 'wp_ajax_sw_increment_instance', 'sw_increment_instance' );

function sw_deleted_instances() {

	global $wpdb;

	$sw_deleted = $_POST['sw_deleted'];
	$sw_post_id = $_POST['sw_post_id'];
	$sw_delete_arr = get_post_meta( $sw_post_id, '_deleted', true);
	$sw_deleted_string = '';

	$num_instances = intval( get_post_meta( $sw_post_id, '_instances', true) );
	$num_instances = $num_instances === 0 ? 0 : $num_instances - 1;
	update_post_meta( $sw_post_id, '_instances', $num_instances);

	array_push( $sw_delete_arr, $sw_deleted );
	update_post_meta( $sw_post_id, '_deleted', $sw_delete_arr );

	foreach( $sw_delete_arr as $deleted )
		$sw_deleted_string .= $deleted . ' ';

	update_post_meta( $sw_post_id, '_deleted_string', $sw_deleted_string );

	echo $sw_deleted_string;

	wp_die();
}
add_action( 'wp_ajax_nopriv_sw_deleted_instances', 'sw_deleted_instances' );
add_action( 'wp_ajax_sw_deleted_instances', 'sw_deleted_instances' );

function sw_timeday_delete_meta( $index, $from_post_id ) {

	?>
	<script>
		console.log('sw_timeday_delete_meta: INDEX = ' + '<?php echo $index ?>');
	</script>
	<?php

	$hour_field = 'sw_hour_' . $index;
	$minute_field = 'sw_minute_' . $index;
	$ampm_field = 'sw_ampm_' . $index;
	$time_fields = [ $hour_field, $minute_field, $ampm_field ];

	$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

	foreach($time_fields as $time_field)
		delete_post_meta( $from_post_id, $time_field );

	foreach( $days as $day ) {
		$day = 'sw_' . $day . '_' . $index;
		delete_post_meta( $from_post_id, $day );
	}




}

add_action( 'admin_menu', function() {
	SW_Init::sw_add_admin_menu();
});

add_action('admin_init', function() {
	$simple_week = new SW_Init();
	$simple_week->sw_settings_init();
});

?>