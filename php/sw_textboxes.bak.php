<?php
/**
 * function to return a custom field value.
 */
function wpshed_get_custom_field( $value ) {
	global $post;

	$custom_field = get_post_meta( $post->ID, $value, true );
	if ( !empty( $custom_field ) )
		return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

	return false;
}


/**
 * Register the Meta box
 */
function wpshed_add_custom_meta_box() {
	add_meta_box( 'wpshed-meta-box', __( 'Metabox Example', 'wpshed' ), 'wpshed_meta_box_output', 'sw_category', 'normal', 'core' );
}
add_action( 'add_meta_boxes', 'wpshed_add_custom_meta_box' );


/**
 * Output the Meta box
 */
function wpshed_meta_box_output( $post ) {
	// create a nonce field
	wp_nonce_field( 'my_wpshed_meta_box_nonce', 'wpshed_meta_box_nonce' ); ?>

	<p>
		<label for="wpshed_textfield"><?php _e( 'Textfield', 'wpshed' ); ?>:</label>
		<input type="text" name="wpshed_textfield" id="wpshed_textfield" value="<?php echo wpshed_get_custom_field( 'wpshed_textfield' ); ?>" size="50" />
	</p>

	<p>
		<label for="wpshed_textarea"><?php _e( 'Textarea', 'wpshed' ); ?>:</label><br />
		<textarea name="wpshed_textarea" id="wpshed_textarea" cols="60" rows="4"><?php echo wpshed_get_custom_field( 'wpshed_textarea' ); ?></textarea>
	</p>

<?php
}

function wpshed_meta_box_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['wpshed_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wpshed_meta_box_nonce'], 'my_wpshed_meta_box_nonce' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post', get_the_id() ) ) return;

	// Save the textfield
	if( isset( $_POST['wpshed_textfield'] ) )
		update_post_meta( $post_id, 'wpshed_textfield', esc_attr( $_POST['wpshed_textfield'] ) );

	// Save the textarea
	if( isset( $_POST['wpshed_textarea'] ) )
		update_post_meta( $post_id, 'wpshed_textarea', esc_attr( $_POST['wpshed_textarea'] ) );
}
add_action( 'save_post', 'wpshed_meta_box_save' );