<?php
function simpleclinic_add_settings_page() {
    add_options_page( 'Simple Clinic Settings', 'Simple Clinic Settings', 'manage_options', 'simpleclinic_settings', 'simpleclinic_render_plugin_settings_page' );
}
add_action( 'admin_menu', 'simpleclinic_add_settings_page' );
add_action( 'admin_init', 'simpleclinic_settings_init' );


function simpleclinic_settings_init(  ) {
    register_setting( 'issMedicalOfficePlugin', 'simpleclinic_settings' );
    add_settings_section(
        'simpleclinic_issMedicalOfficePlugin_section',
        __( 'Options', 'wordpress' ),
        'simpleclinic_settings_section_callback',
        'issMedicalOfficePlugin'
    );

    add_settings_field(
        'simpleclinic_select_pracprov',
        __( 'Person Terminology', 'wordpress' ),
        'simpleclinic_select_pracprov_render',
        'issMedicalOfficePlugin',
        'simpleclinic_issMedicalOfficePlugin_section'
    );

    add_settings_field(
        'simpleclinic_select_specmod',
        __( 'Field Terminology', 'wordpress' ),
        'simpleclinic_select_specmod_render',
        'issMedicalOfficePlugin',
        'simpleclinic_issMedicalOfficePlugin_section'
    );

    add_settings_field(
        'simpleclinic_addphoto',
        __( 'Full size photo on provider page', 'wordpress' ),
        'simpleclinic_togglephoto_render',
        'issMedicalOfficePlugin',
        'simpleclinic_issMedicalOfficePlugin_section'
    );

    add_settings_field(
      'simpleclinic_turn_off_alphabetical_order',
      __( 'Turn off ordering practitioners by last name (useful if you want to use a custom post order plugin)', 'wordpress' ),
      'simpleclinic_turn_off_alphabetical_order_render',
      'issMedicalOfficePlugin',
      'simpleclinic_issMedicalOfficePlugin_section'
    );

    add_settings_field(
      'simpleclinic_imagewidth',
      __( 'Choose a width in pixels for archive page practitioner photos', 'wordpress' ),
      'simpleclinic_choose_photo_width',
      'issMedicalOfficePlugin',
      'simpleclinic_issMedicalOfficePlugin_section'
    );

    add_settings_field(
      'simpleclinic_imageheight',
      __( 'Choose a height in pixels for archive page practitioner photos', 'wordpress' ),
      'simpleclinic_choose_photo_height',
      'issMedicalOfficePlugin',
      'simpleclinic_issMedicalOfficePlugin_section'
    );

    add_settings_field(
      'simpleclinic_crop',
      __( 'Crop the provider images?', 'wordpress' ),
      'simpleclinic_crop_render',
      'issMedicalOfficePlugin',
      'simpleclinic_issMedicalOfficePlugin_section'
    );



}

function simpleclinic_text_field_0_render(  ) {
    $options = get_option( 'simpleclinic_settings' );
    ?>
    <input type='text' name='simpleclinic_settings[simpleclinic_text_field_0]' value='<?php echo $options['simpleclinic_text_field_0']; ?>'>
    <?php
}

function simpleclinic_select_pracprov_render(  ) {
    $options = get_option( 'simpleclinic_settings' );
    ?>
    <select name='simpleclinic_settings[simpleclinic_select_pracprov]'>
        <option value='provider' <?php selected( $options['simpleclinic_select_pracprov'], 'provider' ); ?>>Provider</option>
        <option value='practitioner' <?php selected( $options['simpleclinic_select_pracprov'], 'practitioner' ); ?>>Practitioner</option>
    </select>
<?php
}

function simpleclinic_select_specmod_render(  ) {
    $options = get_option( 'simpleclinic_settings' );
    ?>
    <select name='simpleclinic_settings[simpleclinic_select_specmod]'>
        <option value='specialty' <?php selected( $options['simpleclinic_select_specmod'], 'specialty' ); ?>>Specialty</option>
        <option value='modality' <?php selected( $options['simpleclinic_select_specmod'], 'modality' ); ?>>Modality</option>
    </select>
<?php
}

function simpleclinic_togglephoto_render() {
  $options = get_option( 'simpleclinic_settings' );
  ?>
  <select name='simpleclinic_settings[simpleclinic_addphoto]'>
      <option value='show' <?php selected( $options['simpleclinic_addphoto'], 'show' ); ?>>Show</option>
      <option value='hide' <?php selected( $options['simpleclinic_addphoto'], 'hide' ); ?>>Hide</option>
  </select>
<?php
}

function simpleclinic_choose_photo_width() {
  $options = get_option( 'simpleclinic_settings' );
  ?>
  <input type="number" value="<?php echo $options['simpleclinic_imagewidth']; ?>" name='simpleclinic_settings[simpleclinic_imagewidth]'>
<?php
}

function simpleclinic_choose_photo_height() {
  $options = get_option( 'simpleclinic_settings' );
  ?>
  <input type="number" value="<?php echo $options['simpleclinic_imageheight']; ?>" name='simpleclinic_settings[simpleclinic_imageheight]'>
<?php
}

function simpleclinic_crop_render() {
  $options = get_option( 'simpleclinic_settings' );
  ?>
  <select name='simpleclinic_settings[simpleclinic_crop]'>
      <option value='crop' <?php selected( $options['simpleclinic_crop'], 'crop' ); ?>>Crop</option>
      <option value='nocrop' <?php selected( $options['simpleclinic_crop'], 'nocrop' ); ?>>Don't crop</option>
  </select>
<?php
}

function simpleclinic_turn_off_alphabetical_order_render() {
  $options = get_option( 'simpleclinic_settings' );
  ?>
  <input type="checkbox" value="1" name='simpleclinic_settings[simpleclinic_turn_off_alphabetical_order]'  <?php checked( $options['simpleclinic_turn_off_alphabetical_order'], '1' ); ?>>
<?php
}

function simpleclinic_settings_section_callback(  ) {
    echo __( 'You can change some things about the way the plugin works here. You may need to refresh your permalinks after making these changes.', 'wordpress' );
}

function simpleclinic_render_plugin_settings_page(  ) {
    ?>
    <form action='options.php' method='post'>

        <h2>Simple Clinic Plugin Settings</h2>

        <?php
        settings_fields( 'issMedicalOfficePlugin' );
        do_settings_sections( 'issMedicalOfficePlugin' );
        submit_button();
        ?>

    </form>
    <hr style="margin-top: 20px;">
    <p>Was this resource helpful to you? Buy me a coffee so I can keep producing useful tools & plugins like this one! :)</p>
    <script type='text/javascript' src='https://ko-fi.com/widgets/widget_2.js'></script><script type='text/javascript'>kofiwidget2.init('Buy me a coffee', '#6bc3d5', 'U6U31XPQI');kofiwidget2.draw();</script>
    <?php
}
