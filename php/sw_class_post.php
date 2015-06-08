<?php


class SW_ClassPost {

	public function __construct( ) {
		$this->sw_register_post_type();
		add_action( 'admin_head', array( 'SW_ClassPost','sw_post_class_admin_enqueue' ) );
	}

	public function sw_post_class_admin_enqueue( ) {

		// Admin styling
		$admin_style_src = plugins_url( 'simple-week/css/sw_post_class_admin_styles.css' );
		$admin_style_handle = 'sw_post_class_admin_styles';
		wp_register_script( $admin_style_handle, $admin_style_src );
		wp_enqueue_style( $admin_style_handle, $admin_style_src, array(), false, false );

		// Admin scripts
		$add_ins_src = plugins_url( 'simple-week/js/sw_post_class_add_instance.js' );
		$add_ins_handle = 'sw_post_class_script_add_instance';
		wp_register_script( $add_ins_handle, $add_ins_src );
		wp_enqueue_script( $add_ins_handle, $add_ins_src, array(), false, false );

	}

	public static function sw_add_submenu( $parent ) {

		add_submenu_page( $parent, 'SimpleWeek Classes', 'Classes',
			'manage_options', 'edit.php?post_type=sw_class', NULL );
		add_submenu_page( $parent, 'Add SimpleWeek Class', 'Add Class',
			'manage_options', 'post-new.php?post_type=sw_class', NULL );

	}

	public function sw_register_post_type( ) {
		$labels = array(
			'name' => 'Classes',
			'singular_name' => 'Class',
			'add_new' => 'Add New Class',
			'add_new_item' => 'Add New Class',
			'edit_item' => 'Edit Item',
			'new_item' => 'Add New Item',
			'view_item' => 'View Class',
			'search_items' => 'Search Classes',
			'not_found' => 'No Classes Found',
			'not_found_in_trash' => 'No Classes Found in Trash'
		);

		$args = array(
			'labels' => $labels,
			'query_var' => 'sw_class',
			'rewrite' => array( 'slug' => 'sw_classes/'),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'has_archive'        => true,
			'show_in_menu'       => false,
			'supports' => array( 'title' )
		);

		register_post_type('sw_class', $args);
	}
}

add_action('init', function() {
	new SW_ClassPost();
});

