<?php


class SW_CategoryPost {

	public function __construct() {
		$this->sw_register_post_type();
		add_action( 'admin_enqueue_scripts', array( 'SW_CategoryPost','sw_post_category_admin_enqueue' ) );
	}

	public function sw_post_category_admin_enqueue( ) {

		// Admin styling
		$admin_style_src = plugins_url( 'simple-week/css/sw_post_category_admin_styles.css' );
		$admin_style_handle = 'sw_post_category_admin_styles';
		wp_register_style( $admin_style_handle, $admin_style_src );
		wp_enqueue_style( $admin_style_handle, $admin_style_src, array(), false, false );

		// Admin scripts
//		$add_ins_src = plugins_url( 'simple-week/js/sw_post_class_add_instance.js' );
//		$add_ins_handle = 'sw_post_class_script_add_instance';
//		wp_register_script( $add_ins_handle, $add_ins_src );
//		wp_enqueue_script( $add_ins_handle, $add_ins_src, array(), false, false );

	}

	public static function sw_add_submenu($parent) {
		add_submenu_page( $parent, 'SimpleWeek Categories', 'Categories',
			'manage_options', 'edit.php?post_type=sw_category', NULL );
		add_submenu_page( $parent, 'Add SimpleWeek Category', 'Add Category',
			'manage_options', 'post-new.php?post_type=sw_category', NULL );
	}

	public function sw_register_post_type() {
		$labels = array(
			'name' => 'Categories',
			'singular_name' => 'Category',
			'add_new' => 'Add New Category',
			'add_new_item' => 'Add New Category',
			'edit_item' => 'Edit Item',
			'new_item' => 'Add New Item',
			'view_item' => 'View Category',
			'search_items' => 'Search Categories',
			'not_found' => 'No Categories Found',
			'not_found_in_trash' => 'No Categories Found in Trash'
		);

		$args = array(
			'labels' => $labels,
			'query_var' => 'sw_categories',
			'rewrite' => array( 'slug' => 'sw_categories/'),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'has_archive'        => true,
			'show_in_menu'       => false,
			'supports' => array( 'title' )
		);

		register_post_type('sw_category', $args);
	}
}

add_action('init', function() {
	new SW_CategoryPost();
});

