<?php

function sw_add_timeday_metabox( $post ) {

	add_meta_box(
		'sw-timeday-metabox',
		__( 'Schedule', 'simple-week' ),
		'sw_timeday_metabox_output',
		'sw_class',
		'normal',
		'core'
	);
}
add_action( 'add_meta_boxes', 'sw_add_timeday_metabox' );

////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////

function sw_timeday_metabox_output() {
	global $post;
	$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

	// Use nonce for verification
	wp_nonce_field( 'my_sw_timeday_metabox_nonce', 'sw_timeday_metabox_nonce' );

	//add_post_meta( $post->ID, '_instances', 1, true );

	//intval( get_post_meta( $post->ID, '_instances', true) );

	if ( ! add_post_meta( $post->ID, '_instances', 1, true ) ) {
		update_post_meta( $post->ID, '_instances', ( get_post_meta( $post->ID, '_instances', true) ) );
	}
	$instances = intval( get_post_meta( $post->ID, '_instances', true) );

	?>
		<div id="sw_timeday_outer">
		<script> console.log('<?php echo intval( get_post_meta( $post->ID, '_instances', true) ) ?>'); </script>
	<?php

	printf('<div id="sw_timeday_inner" data-instances="%s">', $instances);

	for ( $i = 1; $i <= $instances; $i++ ) {
		$hour_metakey = 'sw_hour_' . $i;
		$minute_metakey = 'sw_minute_' . $i;
		$ampm_metakey = 'sw_ampm_' . $i;
		$set_hour = get_post_meta( $post->ID, $hour_metakey, true);
		$set_minute = get_post_meta( $post->ID, $minute_metakey, true);
		$set_ampm = get_post_meta( $post->ID, $ampm_metakey, true);

		printf( '<div id="sw_timeday_instance_%s">', $i);

			printf( '<div id="sw_time_instance_%s">', $i );

				printf( '<select name="sw_hour_%s">', $i );
				for ( $hour = 1; $hour <= 12; $hour++ ) {
					$print_hour = $hour < 10 ? '0' . $hour : $hour;
					$selected = intval($print_hour) === intval($set_hour) ? 'selected="selected"' : '';
					printf( '<option value="%s" %s %s>%s</option>', $print_hour, $selected, $print_hour, $print_hour );
				}
				echo '</select>';

				printf( '<select name="sw_minute_%s">', $i );
				for ( $minute = 0; $minute < 60; $minute += 5 ) {
					$print_minute = $minute < 10 ? '0' . $minute : $minute;
					$selected = intval($print_minute) === intval($set_minute) ? 'selected="selected"' : '';
					printf( '<option value="%s" %s %s>%s</option>', $print_minute, $selected, $print_minute, $print_minute );
				}
				echo '</select>';

				printf( '<select name="sw_ampm_%s">', $i );
				if( $set_ampm === "AM" ) {
					echo '<option value="AM" selected="selected" AM>AM</option>';
					echo '<option value="PM" PM>PM</option>';
				}
				else {
					echo '<option value="AM" AM>AM</option>';
					echo '<option value="PM" selected="selected" PM>PM</option>';
				}
				echo '</select>';

			echo '</div>';

			printf( '<div id="sw_day_instance_%s"><ul>', $i );
			foreach( $days as $day ) {
				$day_metakey = 'sw_' . $day . '_' . $i;
				$set_day = get_post_meta( $post->ID, $day_metakey, true);
				$checked = $set_day === 'on' ? 'checked="checked"' : '';
				printf( '<li><span>
							<input type="checkbox" %s name="sw_%s_%s">
							<label for="sw_%s_%s">%s</label>
						</span></li>',
					$checked, $day, $i, $day, $i, $day );
			}
			echo '</ul></div>';

		echo '</div>';
	}
	?></div>
		<a class="button button-large button-primary" onclick="sw_add_instance()">+</a>
		<script>
			function sw_add_instance() {
				var $ =jQuery.noConflict();
				$(document).ready(function() {
					var old_ins = <?php echo intval( get_post_meta( $post->ID, '_instances', true) ); ?>,
						_ins = old_ins + 1,
						old_ins_len = old_ins.toString().length,
						clone_id = 'sw_timeday_instance_'+_ins;
					//alert( _ins + ' ' + old_ins );

					var clone = $( 'div#sw_timeday_instance_1' ).clone();
						clone.attr('id',clone_id).find('[id^="sw_"],[name^="sw_"],label[for^="sw_"]').each( function() {
						if( $(this).attr('id') ) {
							var old_id = $(this).attr('id'),
								new_id = old_id.slice(0,old_id.length-old_ins_len) + _ins;
							$(this).attr('id',new_id);
						} else if( $(this).attr('name') ) {
							var old_name = $(this).attr('name'),
								new_name = old_name.toString().slice(0,old_name.length-old_ins_len) + _ins;
							$(this).attr('name',new_name);

							if( $(this).is('select') ) {
								$(this)[0].selectedIndex = -1;
							} else if ( $(this).is('input[type="checkbox"]:checked') ) {
								$(this).click();
							}
						} else {
							var old_for = $(this).attr('for'),
								new_for = old_for.toString().slice(0,old_for.length-old_ins_len) + _ins;
							$(this).attr('for',new_for);
						}
					});

					$('div#sw_timeday_inner').append(clone);
				});
				<?php ++$instances; update_post_meta( $post->ID, '_instances', $instances  ); ?>
				console.log('<?php echo intval( get_post_meta( $post->ID, '_instances', true) ) ?>');
			}
		</script>
	<?php
	echo '</div>';

	/*
	$metatest = get_post_meta( $post->ID );
	foreach( $metatest as $meta )
		foreach( $meta as $the_meta )
			echo '<h1>'.$the_meta.'</h1>';
	*/
}

////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////

function sw_timeday_metabox_save( $post_id ) {
	global $post;

	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['sw_timeday_metabox_nonce'] ) || !wp_verify_nonce( $_POST['sw_timeday_metabox_nonce'], 'my_sw_timeday_metabox_nonce' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post', get_the_id() ) ) return;

	// Save the textfield
	//if( isset( $_POST['sw_textfield'] ) )
	//	update_post_meta( $post_id, 'sw_textfield', esc_attr( $_POST['sw_textfield'] ) );

	//if( isset( $_POST['sw_hour_1'] ) )
	//	update_post_meta( $post_id, 'sw_hour_1', esc_attr( $_POST['sw_hour_1'] ) );

	$num_instances = intval( get_post_meta( $post->ID, '_instances', true ) );
	$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

	for($i=1; $i<=$num_instances; $i++) {
		$hour_field = 'sw_hour_' . $i;
		$minute_field = 'sw_minute_' . $i;
		$ampm_field = 'sw_ampm_' . $i;
		$time_fields = [ $hour_field, $minute_field, $ampm_field ];

		//if( isset( $_POST[$hour_field] ) )
		//	update_post_meta( $post->ID, $hour_field, esc_attr( $_POST[ $hour_field] ) );

		foreach($time_fields as $time_field) {
			if( isset( $_POST[ $time_field ] ) )
				update_post_meta( $post->ID, $time_field, esc_attr( $_POST[ $time_field ] ) );
		}

		$day_vals = [];
		foreach( $days as $day )
			array_push( $day_vals, 'sw_' . $day . '_' . $i );

		foreach( $day_vals as $val ) {
			if( isset( $_POST[ $val ] ) )
				update_post_meta( $post->ID, $val, esc_attr( $_POST[ $val ] ) );
		}
	}

}
add_action( 'save_post', 'sw_timeday_metabox_save' );