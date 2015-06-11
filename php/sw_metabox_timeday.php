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

	wp_nonce_field( 'my_sw_timeday_metabox_nonce', 'sw_timeday_metabox_nonce' );

	add_post_meta( $post->ID, '_instances', 0, true );
	$instances =  intval( get_post_meta( $post->ID, '_instances', true) );
	update_post_meta( $post->ID, '_deleted', Array(), true );

	?>
		<div id="sw_timeday_outer">
	<?php

	printf('<div id="sw_timeday_inner" data-instances="%s">', $instances);

	for ( $i = 0; $i <= $instances; $i++ ) {

		$hour_metakey = 'sw_hour_' . $i;
		$minute_metakey = 'sw_minute_' . $i;
		$ampm_metakey = 'sw_ampm_' . $i;

		$set_active = get_post_meta( $post->ID, 'sw_instance_active_' . $i, true);

		$set_hour = get_post_meta( $post->ID, $hour_metakey, true);
		$set_minute = get_post_meta( $post->ID, $minute_metakey, true);
		$set_ampm = get_post_meta( $post->ID, $ampm_metakey, true);

		printf( '<div id="sw_timeday_instance_%s" data-index="%s" %s>', $i, $i,
				$i === 0 ? 'class="sw_blank_instance"' : '');
		?>

		<div class="sw_timeday_instance_topbtns">
			<?php
			printf('<span class="sw_active_box">
						<input type="checkbox" name="sw_instance_active_%s" %s>
						<label for="sw_instance_active_%s">Active</label>
					</span>',$i, $set_active === 'on' || $i === 0 ? 'checked="checked"' : '', $i);
			printf('<a id="sw_timeday_delete_btn_%s" data-index="%s"
				class="button button-large button-primary sw_ins_delete_btn">-</a>', $i, $i);
			?>
		</div>

		<?php
			printf( '<div id="sw_time_instance_%s">', $i );

				printf( '<select name="sw_hour_%s">', $i );
				for ( $hour = 1; $hour <= 12; $hour++ ) {
					$print_hour = $hour < 10 ? '0' . $hour : $hour;
					$selected = intval($print_hour) === intval($set_hour) ? 'selected="selected"' : '';
					printf( '<option value="%s" %s %s>%s</option>',
						$print_hour, $selected, $print_hour, $print_hour );
				}
				echo '</select>';

				printf( '<select name="sw_minute_%s">', $i );
				for ( $minute = 0; $minute < 60; $minute += 5 ) {
					$print_minute = $minute < 10 ? '0' . $minute : $minute;
					$selected = intval($print_minute) === intval($set_minute) ? 'selected="selected"' : '';
					printf( '<option value="%s" %s %s>%s</option>',
						$print_minute, $selected, $print_minute, $print_minute );
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
			echo '</ul><hr class="sw_instance_divider"></div>';

		echo '</div>';
	}

	////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////

	?></div>
	<a id="sw_add_instance_btn" class="button button-large button-primary" onclick="">+</a>

		<script>

			function sw_add_instance() {
				var $ =jQuery.noConflict();
				$(document).ready(function() {
					var _ins_container = $('div#sw_timeday_inner'),
						old_ins = parseInt( _ins_container.attr('data-instances') ),
						_ins = old_ins + 1,
						clone_id = 'sw_timeday_instance_'+_ins;

					var clone = $( 'div#sw_timeday_instance_0' ).clone().removeClass('sw_blank_instance');

						clone.attr('id',clone_id).attr('data-index',_ins)
						.find('[id^="sw_"],[name^="sw_"],label[for^="sw_"]').each( function() {

						if( $(this).attr('id') ) {
							var old_id = $(this).attr('id'),
								new_id = old_id.slice(0,old_id.length-1) + _ins;
							$(this).attr('id',new_id);

							if( $(this).is('a[id^="sw_timeday_delete_btn"]') ) {
								$(this).attr( 'data-index', _ins )
							}
						} else if( $(this).attr('name') ) {
							var old_name = $(this).attr('name'),
								new_name = old_name.toString().slice(0,old_name.length-1) + _ins;
							$(this).attr('name',new_name);

							if( $(this).is('select') ) {
								$(this)[0].selectedIndex = -1;
							} else if ( $(this).is('input[type="checkbox"]:checked') &&
										!$(this).is('[name^="sw_instance_active_"]') ) {
								$(this).click();
							}
						} else {
							var old_for = $(this).attr('for'),
								new_for = old_for.toString().slice(0,old_for.length-1) + _ins;
							$(this).attr('for',new_for);
						}
					});

					_ins_container.append(clone).attr('data-instances',_ins);

					var data = {
						'action': 'sw_increment_instance',
						'sw_instances': _ins,
						'sw_post_id': <?php global $post; echo $post->ID; ?>
					};

					$.post( '<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
						//console.log('AJAX (sw_increment_instance) - Response: ' + response);
					});
				});
			}

			function sw_delete_instance( instance_id ) {
				console.log('delete: enter');

				var $ =jQuery.noConflict(),
					ins_container = $('div#sw_timeday_inner'),
					old_num_ins = parseInt( ins_container.attr('data-instances') ),
					delete_index = parseInt( $( instance_id ).attr('data-index') ),
					swap_index = delete_index === old_num_ins ? delete_index : delete_index + 1;

				if( delete_index === 0 ) return;

				$(instance_id).remove();

				for(var i = swap_index; i <= old_num_ins; i++ ) {

					var from = i,
						to = i - 1,
						from_len = from.toString().length;

					$( 'div#sw_timeday_instance_' + from )
					.attr( 'id','sw_timeday_instance_' + to ).attr( 'data-index', to )
					.find('[id^="sw_"],[name^="sw_"],label[for^="sw_"]')
					.each(function() {

						if( $(this).attr('id') ) {
							var old_id = $( this ).attr( 'id' ),
								new_id = old_id.slice (0, old_id.length-from_len ) + to;
							$( this ).attr( 'id',new_id );

							if( $(this).is('a[id^="sw_timeday_delete_btn"]') ) {
								$(this).attr( 'data-index', to )
							}
						} else if( $(this).attr('name') ) {
							var old_name = $(this).attr('name'),
								new_name = old_name.toString().slice( 0, old_name.length-from_len ) + to;

							$(this).attr('name',new_name);
						} else {
							var old_for = $(this).attr('for'),
								new_for = old_for.toString().slice( 0, old_for.length-from_len ) + to;

							$(this).attr('for',new_for);
						}
					});

					var data = {
						'action': 'sw_deleted_instances',
						'sw_post_id': <?php global $post; echo $post->ID; ?>,
						'sw_deleted': delete_index
					};

					$.post( '<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
						//console.log('AJAX (sw_deleted_instances) - Response: ' + response + '\n' +
						//		'Instances: ' + ( old_num_ins - 1 ) );
					});
				}

				ins_container.attr( 'data-instances', old_num_ins === 0 ? 0 : old_num_ins - 1 );

				return old_num_ins === 0 ? 0 : old_num_ins - 1;
			}

			jQuery(document).ready(function() {
				var $ =jQuery.noConflict();

				$( 'a#sw_add_instance_btn' ).bind( 'click', sw_add_instance );

				$( 'a.sw_ins_delete_btn' ).live( 'click', function() {
					console.log('delete: click');
					var index = $(this).data( 'index'),
						old_num_ins = $( 'div#sw_timeday_inner' ).attr( 'data-instances'),
						new_num_ins = sw_delete_instance( '#sw_timeday_instance_' + index );
				});
			});

		</script>
	<?php

	echo '</div>';

}

////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////

function sw_timeday_metabox_save( ) {
	global $post;

	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['sw_timeday_metabox_nonce'] ) ||
		!wp_verify_nonce( $_POST['sw_timeday_metabox_nonce'], 'my_sw_timeday_metabox_nonce' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post', get_the_id() ) ) return;

	$num_instances = intval( get_post_meta( $post->ID, '_instances', true ) );

	$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

	for($i=1; $i<=$num_instances; $i++) {

		$active_field = 'sw_instance_active_' . $i;
		update_post_meta( $post->ID, $active_field, esc_attr( $_POST[ $active_field ] ) );

		$hour_field = 'sw_hour_' . $i;
		$minute_field = 'sw_minute_' . $i;
		$ampm_field = 'sw_ampm_' . $i;
		$time_fields = [ $hour_field, $minute_field, $ampm_field ];

		foreach( $time_fields as $time_field )
			if( isset( $_POST[ $time_field ] ) )
				update_post_meta( $post->ID, $time_field, esc_attr( $_POST[ $time_field ] ) );

		foreach( $days as $day ) {
			$day = 'sw_' . $day . '_' . $i;
			update_post_meta( $post->ID, $day, esc_attr( $_POST[ $day ] ) );
		}
	}

	delete_post_meta( $post->ID, '_deleted' );
	delete_post_meta( $post->ID, '_deleted_string' );
}

add_action( 'save_post', 'sw_timeday_metabox_save' );