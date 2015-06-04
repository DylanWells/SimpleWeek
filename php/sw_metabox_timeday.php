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

function sw_timeday_metabox_output() {
	global $post;

	$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

	// Use nonce for verification
	wp_nonce_field( 'my_sw_timeday_metabox_nonce', 'sw_timeday_metabox_nonce' );

	$instances = 1;

	?><div id="sw_timeday_inner"><?php

	for ( $i = 0; $i < $instances; $i++ ) {
		printf( '<div id="sw_timeday_instance_%s" class="widefat"  style="text-align:center;">', $i );

			printf( '<div id="sw_time_instance_%s" class="widefat" style="margin:10px;">', $i );
				printf( '<select name="sw_hour_%s">', $i );
				for ( $hour = 1; $hour <= 12; $hour++ ) {
					$print_hour = $hour < 10 ? '0' . $hour : $hour;
					printf( '<option value="%s" %s>%s</option>', $print_hour, $print_hour, $print_hour );
				}
				echo '</select>';

				printf( '<select name="sw_minute_%s">', $i );
				for ( $minute = 0; $minute < 60; $minute += 5 ) {
					$print_minute = $minute < 10 ? '0' . $minute : $minute;
					printf( '<option value="%s" %s>%s</option>', $print_minute, $print_minute, $print_minute );
				}
				echo '</select>';

				printf( '<select name="sw_minute_%s">', $i );
				echo '<option value="AM" AM>AM</option>';
				echo '<option value="PM" PM>PM</option>';
				echo '</select>';
			echo '</div>';

			printf( '<div id="sw_day_instance_%s" class="widefat" style="margin:10px;">', $i );
			foreach( $days as $day ) {
				printf( '<span style="display:block;padding:7px;margin-top:10px;"><input type="checkbox" name="sw_%s_%s"><label style="position:relative;bottom:2px" for="sw_%s_%s">%s</label></span>',
					$day, $i, $day, $i, $day );
			}

			echo '</div>';

			echo '<button class="button button-large button-primary">+</button>';
		echo '</div>';
	}

	?></div><?php
}

