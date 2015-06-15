<?php

// Thank you: http://javatechig.com/wordpress/how-to-implement-color-picker-with-wordpress

function sw_add_color_picker_metabox() {
	add_meta_box('sw_color-picker-metabox', 'Category Color', 'sw_color_picker_output', 'sw_category', 'normal', 'high');
}

add_action( 'add_meta_boxes', 'sw_add_color_picker_metabox' );

function sw_color_picker_output( $post ) {
	wp_nonce_field( 'my_sw_color_picker_nonce', 'sw_color_picker_nonce' );
	$color = get_post_meta( $post->ID, 'sw_cat_color', true );

	?>

	<div class="sw_color_picker_box">
	<p>
	<label>Select Category Color: </label><br>
	<input class="color-field" type="text" name="sw_cat_color" value="<?php echo '#'.$color; ?>"/>
	</p>
	<div class="clear"></div>
	</div>

	<script>
		(function( $ ) {
			// Add Color Picker to all inputs that have 'color-field' class
			$(function() {
				$('.color-field').wpColorPicker( { hide: true });
			});
		})( jQuery );
	</script>

	<?php
}

function sw_color_picker_save( $post_id ) {
	if ( !isset( $_POST['sw_color_picker_nonce'] ) ) {
		return;
	}

	if ( !wp_verify_nonce( $_POST['sw_color_picker_nonce'], 'my_sw_color_picker_nonce' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( !current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$sw_cat_color = ( isset( $_POST['sw_cat_color'] ) ? sanitize_html_class( $_POST['sw_cat_color'] ) : '' );
	update_post_meta( $post_id, 'sw_cat_color', $sw_cat_color );
}


add_action( 'save_post', 'sw_color_picker_save' );

function wpse_80236_Colorpicker(){
	wp_enqueue_style( 'wp-color-picker');
	//
	wp_enqueue_script( 'wp-color-picker');
}
add_action('admin_enqueue_scripts', 'wpse_80236_Colorpicker');