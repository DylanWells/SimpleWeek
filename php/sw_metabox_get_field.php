<?php
/**
 * function to return a custom field value.
 */
function sw_get_custom_field( $value ) {
	global $post;

	$custom_field = get_post_meta( $post->ID, $value, true );
	if ( !empty( $custom_field ) )
		return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

	return false;
}
