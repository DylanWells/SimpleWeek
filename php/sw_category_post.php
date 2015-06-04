<?php


class SW_CategoryPost {

	public function __construct() {
		$this->sw_register_post_type();
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

