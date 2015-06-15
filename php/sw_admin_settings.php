<?php


class SW_SettingsPage {

    public function __construct() {
        $this->sw_admin_settings_init();
        add_action( 'admin_enqueue_scripts', array( 'SW_SettingsPage','sw_settings_page_admin_enqueue' ) );

    }

    public static function sw_add_settings_page( $parent ) {
        add_submenu_page( $parent, 'SimpleWeek Settings', 'Settings',
            'manage_options', 'sw_settings', array( __CLASS__, 'sw_settings_page') );
    }

    public function sw_settings_page_admin_enqueue( ) {
        $admin_style_src = plugins_url( 'simple-week/css/sw_admin_settings_styles.css' );
        $admin_style_handle = 'sw_admin_settings_styles';
        wp_register_style( $admin_style_handle, $admin_style_src );
        wp_enqueue_style( $admin_style_handle, $admin_style_src, array(), false, false );
    }

    public function sw_admin_settings_init(  ) {

        register_setting( 'sw_options_group', 'sw_appearance' );

        // COLOR SETTINGS
        add_settings_section(
            'sw_section_colors_structure',
            'Color Settings',
            array($this,'sw_colors_callback'),
            'sw_options_group'
        );

        add_settings_field(
            'sw_field_color_frame',
            'Frame Color',
            array($this,'sw_color_picker_output'),
            'sw_options_group',
            'sw_section_colors_structure',
            array(
                'option' => 'sw_appearance',
                'field' => 'sw_field_color_frame')
        );

        add_settings_field(
            'sw_field_color_border',
            'Background Color',
            array($this,'sw_color_picker_output'),
            'sw_options_group',
            'sw_section_colors_structure',
            array(
                'option' => 'sw_appearance',
                'field' => 'sw_field_color_border')
        );

        add_settings_field(
            'sw_field_color_background',
            'Border Color',
            array($this,'sw_color_picker_output'),
            'sw_options_group',
            'sw_section_colors_structure',
            array(
                'option' => 'sw_appearance',
                'field' => 'sw_field_color_background')
        );

        // FONT COLORS
        add_settings_section(
            'sw_section_colors_font',
            'Font Settings',
            array($this,'sw_colors_callback'),
            'sw_options_group'
        );

        add_settings_field(
            'sw_field_color_background',
            'Font Color',
            array($this,'sw_color_picker_output'),
            'sw_options_group',
            'sw_section_colors_font',
            array(
                'option' => 'sw_appearance',
                'field' => 'sw_field_font_color'
            )
        );

        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////

        // FONTS OPTIONS
        register_setting( 'sw_options_group', 'sw_fonts' );

        add_settings_section(
            'sw_section_fonts_outer',
            'Outer',
            array($this,'sw_fonts_callback'),
            'sw_options_group'
        );

        add_settings_field(
            'sw_outer_font',
            'Font',
            array($this,'sw_dropdown_output'),
            'sw_options_group',
            'sw_section_fonts_outer',
            array(
                'option' => 'sw_fonts',
                'field' => 'sw_field_outer_font',
                'selections' => array(
                    'Arial', 'Helvetica', 'Times New Roman', 'Courier New'
                )
            )
        );

        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////

        // CONFIG OPTIONS
        register_setting( 'sw_options_group', 'sw_config' );

        add_settings_section(
            'sw_section_config_advanced',
            'Advanced',
            array($this,'sw_config_callback'),
            'sw_options_group'
        );

        add_settings_field(
            'sw_google_fonts_api_key',
            'Google Fonts API Key',
            array($this,'sw_textarea_output'),
            'sw_options_group',
            'sw_section_config_advanced',
            array(
                'option' => 'sw_config',
                'field' => 'sw_field_color_frame'
            )
        );
    }
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    // CALLBACKS
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////

    function sw_colors_callback( $arg ) {
        echo '<span id="' . $arg['id'] . '"></span>';
    }

    function sw_config_callback( $arg ) {
        echo '<span id="' . $arg['id'] . '"></span>';
    }

    function sw_fonts_callback( $arg ) {
        echo '<span id="' . $arg['id'] . '"></span>';
    }

    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    // FORM ELEMENTS
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    function sw_color_picker_output( $args ) {
        $option = get_option( $args['option'] );
        $field = $args['field'];
        $option_field = $args['option'] . '[' . $field . ']';

        ?>

        <div class="sw_color_picker_box">
            <p>
                <input class="color-field" type="text"
                       name="<?php echo $option_field; ?>"
                       value="<?php echo $option[$field]; ?>"/>
            </p>
            <div class="clear"></div>
        </div>

    <?php
    }

    function sw_textarea_output( $args ) {
        $option = get_option( $args['option'] );
        $field = $args['field'];
        $option_field = $args['option'] . '[' . $field . ']';

        ?>
        <div class="sw_textarea_box">
			<textarea name="<?php echo $option_field; ?>"
                      size="50"><?php echo $option[$field]; ?></textarea>
        </div>
    <?php
    }

    function sw_dropdown_output( $args ) {
        $option = get_option( $args['option'] );
        $field = $args['field'];
        $option_field = $args['option'] . '[' . $field . ']';

        ?>
        <div class="sw_select_box">
            <select name="<?php echo $option_field; ?>">
                <?php
                    $selected = $option[$field];
                    foreach( $args['selections'] as $selection ) {
                        $is_selected = $selected === $selection ? 'selected="selected"' : '';
                        printf( '<option value="%s" %s %s>%s</option>',
                            $selection, $is_selected, $selection, $selection );
                    }
                ?>
            </select>
        </div>
        <?php
    }
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    // FORM OUTPUT
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////

    function sw_settings_page(  ) {
        ?>

        <div class="output">
            <form id="sw_settings_form" data-active="sw_tab_colors" action='options.php' method='post' enctype="multipart/form-data">

                <div class="sw_settings_top">
                    <h2 class="sw_settings_header">SimpleWeek Settings</h2>
                    <?php submit_button(); ?>
                </div>

                <h2 id="sw_tab_wrapper" class="nav-tab-wrapper">
                    <a href="#" id="sw_tab_colors" class="nav-tab nav-tab-active">Colors</a>
                    <a href="#" id="sw_tab_fonts" class="nav-tab">Fonts</a>
                    <a href="#" id="sw_tab_options" class="nav-tab">Options</a>
                </h2>

                <?php
                    settings_fields( 'sw_options_group' );
                    do_settings_sections( 'sw_options_group' );
                ?>

            </form>
        </div>

        <script>
            (function( $ ) {
                // Add Color Picker to all inputs that have 'color-field' class
                $(function() {
                    $('.color-field').wpColorPicker({ hide: true });


                    $('.nav-tab').on('click', function() {
                        if( !$(this).hasClass('nav-tab-active') ) {
                            var was_selected =  $('.nav-tab-active').first();
                            was_selected.removeClass('nav-tab-active');

                            var now_selected = $(this);
                            now_selected.addClass('nav-tab-active');
                        }
                        console.log( $(this).attr('id') );
                        $('#sw_settings_form').attr('data-active', $(this).attr('id') );
                    });

                });
            })( jQuery );
        </script>

    <?php
    }
}

add_action('admin_init', function() {
    new SW_SettingsPage();
});

?>