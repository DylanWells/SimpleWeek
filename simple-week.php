<?php

/*
Plugin Name: SimpleWeek
Plugin URI: http://dylandoes.com/simple-week`
Description:
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

add_action( 'admin_menu', function() {
	SW_Init::sw_add_admin_menu();
});

add_action('admin_init', function() {
	$simple_week = new SW_Init();
	$simple_week->sw_settings_init();
});

?>