<?php


function sw_add_classview_metabox( $post ) {

    add_meta_box(
        'sw-classview-metabox',
        __( 'Classes', 'simple-week' ),
        'sw_classview_metabox_output',
        'sw_category',
        'normal',
        'low'
    );
}
add_action( 'add_meta_boxes', 'sw_add_classview_metabox' );


/* Displays the meta box. */
function sw_classview_metabox_output( $post ) {

    wp_nonce_field( 'my_sw_classview_metabox_nonce', 'sw_classview_metabox_nonce' );

    $classes = get_posts(
        array(
            'post_type'   => 'sw_class',
            'orderby'     => 'title',
            'order'       => 'ASC',
            'numberposts' => -1,
            'meta_key'    => 'sw_category',
            'meta_value'  => $post->ID
        )
    );

    if ( !empty( $classes ) ) {

        echo '<table class="sw_active_classes">';
        echo '<col class="sw_col_active"><col class="sw_col_title">';
        echo '<tr><th>Active</th><th>Class</th><tr>';

        foreach ( $classes as $class ) {
            $class_id = $class->ID;
            add_post_meta( $post->ID, 'sw_cat_class_active_' . $class_id, 'on', true );

            $class_title = $class->post_title;
            $class_meta = 'sw_cat_class_active_' . $class_id;
            $class_active = get_post_meta( $post->ID, $class_meta, true);

            echo '<tr class="sw_trow">';
            printf( '<td class="sw_tcell_active"><input type="checkbox" name="%s" %s></td>',
                $class_meta, $class_active === 'on' ? 'checked="checked"' : '');
            printf( '<td class="sw_tcell_title"><label for="%s">%s</label></td>', $class_meta, $class_title );
            echo '</tr>';
        }

        echo '</table>';
    }
    else {
        ?>
            <div id="sw_no_classes">
                <span>No classes have been added to this category.</span>
            </div>
        <?php
    }
}

function sw_classview_metabox_save( $post_id ) {
    // Stop the script when doing autosave
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // Verify the nonce. If insn't there, stop the script
    if( !isset( $_POST['sw_classview_metabox_nonce'] ) || !wp_verify_nonce( $_POST['sw_classview_metabox_nonce'], 'my_sw_classview_metabox_nonce' ) ) return;

    // Stop the script if the user does not have edit permissions
    if( !current_user_can( 'edit_post', get_the_id() ) ) return;

    $classes = get_posts(
        array(
            'post_type'   => 'sw_class',
            'orderby'     => 'title',
            'order'       => 'ASC',
            'numberposts' => -1,
            'meta_key'    => 'sw_category',
            'meta_value'  => $post_id
        )
    );


    foreach( $classes as $class ) {
        $id = $class->ID;
        update_post_meta( $post_id, 'sw_cat_class_active_' . $id, esc_attr( $_POST['sw_cat_class_active_' . $id] )  );
    }

    // Save the textfield
    //if( isset( $_POST['sw_category'] ) )
        //update_post_meta( $post_id, 'sw_category', esc_attr( $_POST['sw_category'] ) );

}
add_action( 'save_post', 'sw_classview_metabox_save' );