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

//        $jq_dir = 'simple-week/vendor/jquery.mobile.custom/';
//
//        $jquery_mobile_custom_src = plugins_url( $jq_dir . 'jquery.mobile.custom.min.js');
//        $jquery_mobile_custom_handle = 'sw_jquery_mobile_custom';
//        wp_register_script( $jquery_mobile_custom_handle, $jquery_mobile_custom_src );
//        wp_enqueue_script( $jquery_mobile_custom_handle, $jquery_mobile_custom_src, array(), '1.4.5', false);
//
//        $jquery_mobile_custom_struct_src = plugins_url( $jq_dir . 'jquery.mobile.custom.structure.css');
//        $jquery_mobile_custom_struct_handle = 'sw_jquery_mobile_custom_structure';
//        wp_register_script( $jquery_mobile_custom_struct_handle, $jquery_mobile_custom_struct_src );
//        wp_enqueue_script( $jquery_mobile_custom_struct_handle, $jquery_mobile_custom_struct_src, array(), '1.4.5', false);
//
//        $jquery_mobile_custom_theme_src = plugins_url( $jq_dir . 'jquery.mobile.custom.theme.css');
//        $jquery_mobile_custom_theme_handle = 'sw_jquery_mobile_custom_theme';
//        wp_register_script( $jquery_mobile_custom_theme_handle, $jquery_mobile_custom_theme_src );
//        wp_enqueue_script( $jquery_mobile_custom_theme_handle, $jquery_mobile_custom_theme_src, array(), '1.4.5', false);

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

        add_settings_field(
            'sw_font_size',
            'Font Size',
            array($this,'sw_slider_output'),
            'sw_options_group',
            'sw_section_fonts_outer',
            array(
                'option' => 'sw_fonts',
                'field' => 'sw_field_outer_font_size',
                'input_id' => 'sw_outer_font_size_input',
                'output_id' => 'sw_outer_font_size_output',
                'min' => 11,
                'max' => 24,
                'step' => 1
            )

        );

        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////

        // CONFIG OPTIONS
        register_setting( 'sw_options_group', 'sw_config' );

        add_settings_section(
            'sw_section_config_time',
            'Time',
            array($this,'sw_config_callback'),
            'sw_options_group'
        );

        add_settings_field(
            'sw_24h_time',
            '24 Hour Time',
            array($this,'sw_checkbox_output'),
            'sw_options_group',
            'sw_section_config_time',
            array(
                'option' => 'sw_config',
                'field' => 'sw_field_24_time'
            )
        );

        // Advanced

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
                'field' => 'sw_field_google_api'
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

    function sw_checkbox_output( $args ) {
        $option = get_option( $args['option'] );
        $field = $args['field'];
        $option_field = $args['option'] . '[' . $field . ']';

        ?>
            <div class="sw_checkbox_box">
                <?php
                    $checked = $option[$field] === 'on' ? 'checked="checked"' : '';
                    printf('<input type="checkbox" name=%s" %s/>', $option_field, $checked);
                ?>
            </div>
        <?php
    }

    function sw_slider_output( $args ) {
        $option = get_option( $args['option'] );
        $field = $args['field'];
        $option_field = $args['option'] . '[' . $field . ']';

        $input_id = $args['input_id'];
        $output_id = $args['output_id'];
        $min = $args['min'];
        $max = $args['max'];
        $step = $args['step'];

        $value = isset( $option[$field] ) ? $option[$field] : $min;

        ?>

        <div class="sw_slider_box">
                <input type="range"
                       id="<?php echo $input_id; ?>"
                       min="<?php echo $min; ?>"
                       max="<?php echo $max; ?>"
                       step="<?php echo $step; ?>"
                       value="<?php echo $value; ?>" />
                <output id="<?php echo $output_id; ?>"
                        for="<?php echo $input_id; ?>"
                        value="<?php echo $value; ?>">
                        <?php echo $value . 'px'; ?>
                </output>
                <input type="text" name="<?php echo $option_field;?>">
            <script>
                $ = jQuery.noConflict();
                $(function() {
                    var id = '#' + '<?php echo $input_id; ?>';

                    $( id ).on("change mousemove", function() {
                        $(this).next('output').html($(this).val() + 'px');
                        $(this).nextAll('input[type="text"][name^="sw"]').first().val($(this).val());
                    });
                });
            </script>
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