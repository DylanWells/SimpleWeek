<?php

define( 'SIMPLEWEEK_PATH', dirname ( dirname(__FILE__) ) );
include SIMPLEWEEK_PATH . '/php/' . 'sw_metabox_get_field.php';

/**
 * Register the Meta box
 */
function sw_add_custom_meta_box() {
	add_meta_box( 'sw-key-descrip-meta-box', __( 'Key Description', 'simple-week' ), 'sw_textfield_output', 'sw_category', 'normal', 'core' );
	add_meta_box( 'sw-class-descrip-meta-box', __( 'Class Description', 'simple-week' ), 'sw_textarea_output', 'sw_class', 'normal', 'core' );
}

add_action( 'add_meta_boxes', 'sw_add_custom_meta_box' );


/**
 * Output the Meta box
 */
function sw_textfield_output( ) {
	// create a nonce field
	global $post;

	wp_nonce_field( 'my_sw_meta_box_nonce', 'sw_meta_box_nonce' );

	add_post_meta( $post->ID, 'sw_key_descrip_enabled', 'off', true );

	?>

		<div id="sw_key_descrip_box">
			<label for="sw_textfield">
				<?php _e( 'Text next to title in the schedule key<span style="white-space:nowrap">&thinsp;</span>:', 'simple-week' ); ?>
			</label>
			<br>
			<input type="text" name="sw_textfield" id="sw_textfield" value="<?php echo sw_get_custom_field( 'sw_textfield' ); ?>" size="50" />
			<span id="sw_key_descrip_enabled_box">
				<?php
				$sw_key_active = get_post_meta( $post->ID, 'sw_key_descrip_enabled', true );
				printf('<input type="checkbox" name="sw_key_descrip_enabled" %s/>',
					$sw_key_active === 'on' ? 'checked="checked"' : '');
				?>
				Enabled
			</span>
		</div>

	<?php
}

function sw_textarea_output( $post ) {
	// create a nonce field
	wp_nonce_field( 'my_sw_meta_box_nonce', 'sw_meta_box_nonce' );
	?>
		<p>
			<label for="sw_textarea">
				<?php _e( 'Text in class information pop-up<span style="white-space:nowrap">&thinsp;</span>:', 'simple-week' ); ?>
			</label>
			<br>
			<textarea name="sw_textarea" id="sw_class_descrip"
			 size="50"><?php echo sw_get_custom_field( 'sw_textarea' ); ?></textarea>
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

	if( isset( $_POST['sw_textarea'] ) )
		update_post_meta( $post_id, 'sw_textarea', esc_attr( $_POST['sw_textarea'] ) );

	update_post_meta( $post_id, 'sw_key_descrip_enabled', esc_attr( $_POST['sw_key_descrip_enabled'] ) );
}

add_action( 'save_post', 'sw_meta_box_save' );