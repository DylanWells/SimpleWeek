<?php


function sw_add_category_metabox( $post ) {

	add_meta_box(
		'sw-category-metabox',
		__( 'Category', 'simple-week' ),
		'sw_category_metabox_output',
		'sw_class',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sw_add_category_metabox' );


/* Displays the meta box. */
function sw_category_metabox_output( $post ) {

	wp_nonce_field( 'my_sw_category_metabox_nonce', 'sw_category_metabox_nonce' );

	$parents = get_posts(
		array(
			'post_type'   => 'sw_category',
			'orderby'     => 'title',
			'order'       => 'ASC',
			'numberposts' => -1
		)
	);

	if ( !empty( $parents ) ) {
		$selected_title = get_the_title( get_post_meta( $post->ID, 'sw_category', true ) );

		echo '<select id="sw_category" name="sw_category" class="widefat">'; // !Important! Don't change the 'parent_id' name attribute.

		foreach ( $parents as $parent ) {
			$selected = $selected_title === $parent->post_title ? 'selected="selected"' : '';

			printf( '<option ' . $selected . 'value="%s" %s>%s</option>',
				esc_attr( $parent->ID ),
				selected( $parent->ID, $post->post_parent, false ),
				esc_html( $parent->post_title ) );
		}

		echo '</select>';
	}
}

function sw_category_metabox_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['sw_category_metabox_nonce'] ) || !wp_verify_nonce( $_POST['sw_category_metabox_nonce'], 'my_sw_category_metabox_nonce' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post', get_the_id() ) ) return;

	// Save the textfield
	if( isset( $_POST['sw_category'] ) )
		update_post_meta( $post_id, 'sw_category', esc_attr( $_POST['sw_category'] ) );

}
add_action( 'save_post', 'sw_category_metabox_save' );