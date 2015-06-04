<?php

define( 'SIMPLEWEEK_PATH', dirname(__FILE__) );
include SIMPLEWEEK_PATH . '/php/' . 'sw_metabox_get_field.php';

/**
 * Register the Meta box
 */
function sw_add_custom_meta_box() {
	add_meta_box( 'sw-key-descrip-meta-box', __( 'Key Description', 'simple-week' ), 'sw_meta_box_output', 'sw_category', 'normal', 'core' );
}
add_action( 'add_meta_boxes', 'sw_add_custom_meta_box' );


/**
 * Output the Meta box
 */
function sw_meta_box_output( $post ) {
	// create a nonce field
	wp_nonce_field( 'my_sw_meta_box_nonce', 'sw_meta_box_nonce' ); ?>

	<p>
		<label for="sw_textfield"><?php _e( 'Text to accompany title in the schedule key:', 'simple-week' ); ?></label><br>
		<input type="text" name="sw_textfield" id="sw_textfield" value="<?php echo sw_get_custom_field( 'sw_textfield' ); ?>" size="50" />
	</p>

<?php
}

function sw_meta_box_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['sw_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['sw_meta_box_nonce'], 'my_sw_meta_box_nonce' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post', get_the_id() ) ) return;

	// Save the textfield
	if( isset( $_POST['sw_textfield'] ) )
		update_post_meta( $post_id, 'sw_textfield', esc_attr( $_POST['sw_textfield'] ) );

}
add_action( 'save_post', 'sw_meta_box_save' );