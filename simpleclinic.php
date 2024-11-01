<?php
   /*
   Plugin Name: Simple Clinic
   Description: Easily create a website for a medical office with many different types of care under one roof. Adds providers / specialties and custom blocks.
   Version: 1.0.3
   Author: PIXELovely
   Author URI: https://infinitesynergysolutions.com/
   License: GPL2
   */

 add_action('init', 'simpleclinic_themeupdates');
 function simpleclinic_themeupdates() {
   $options = get_option( 'simpleclinic_settings' );

    if (isset($options['simpleclinic_select_pracprov']) && $options['simpleclinic_select_pracprov'] == 'practitioner') {
      $practionerlabels = array(
				'name' => __( 'Practitioners' ),
				'singular_name' => __( 'Practitioner' ),
				'all_items' => __('All Practitioners'),
				'edit_item' => __('Edit Practitioner'),
				'add_new_item' =>  __('Add New Practitioner'),
				'new_item' => __('New Practitioner'),
				'not_found' =>__('No practitioners found'),
				'search_items' =>__('Search practitioners')
			);
      $practionerslug = 'practitioner';
    } else {
      $practionerslug = 'provider';
      $practionerlabels = array(
        'name' => __( 'Providers' ),
        'singular_name' => __( 'Provider' ),
        'all_items' => __('All Providers'),
        'edit_item' => __('Edit Provider'),
        'add_new_item' =>  __('Add New Provider'),
        'new_item' => __('New Provider'),
        'not_found' =>__('No providers found'),
        'search_items' =>__('Search providers')
      );
    }


   if (isset($options['simpleclinic_select_specmod']) && $options['simpleclinic_select_specmod'] == 'modality') {
      $taxonomylabels = array(
        'name' => _x( 'Modalities', 'taxonomy general name' ),
        'singular_name' => _x( 'Modality', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Modalities' ),
        'popular_items' => __( 'Popular Modalities' ),
        'all_items' => __( 'All Modalities' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Modality' ),
        'update_item' => __( 'Update Modality' ),
        'add_new_item' => __( 'Add New Modality' ),
        'new_item_name' => __( 'New Modality Name' ),
        'separate_items_with_commas' => __( 'Separate modalities with commas' ),
        'add_or_remove_items' => __( 'Add or remove modalities' ),
        'choose_from_most_used' => __( 'Choose from the most used modalities' ),
        'menu_name' => __( 'Modalities' ),
      );
      $taxonomyslug = 'modality';
  } else {
      $taxonomylabels = array(
        'name' => _x( 'Specialties', 'taxonomy general name' ),
        'singular_name' => _x( 'Specialty', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Specialties' ),
        'popular_items' => __( 'Popular Specialties' ),
        'all_items' => __( 'All Specialties' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Specialty' ),
        'update_item' => __( 'Update Specialty' ),
        'add_new_item' => __( 'Add New Specialty' ),
        'new_item_name' => __( 'New Specialty Name' ),
        'separate_items_with_commas' => __( 'Separate specialties with commas' ),
        'add_or_remove_items' => __( 'Add or remove specialties' ),
        'choose_from_most_used' => __( 'Choose from the most used specialties' ),
        'menu_name' => __( 'Specialties' ),
      );
      $taxonomyslug = 'specialty';
  }
 	register_post_type( 'sc_provider',
 		array(
 			'labels' => $practionerlabels,
   		'public' => true,
   		'has_archive' => true,
   		'rewrite' => array('slug' => $practionerslug),
   		'supports' => array('title', 'editor', 'excerpt'),
   		'exclude_from_search' => false,
   		'show_ui' => true,
       // This is where we add taxonomies to our CPT
     'taxonomies'          => array( 'sc_specialty' ),
 		)
 	);

   register_taxonomy('sc_specialty',array('sc_provider'), array(
     'hierarchical' => true,
     'labels' => $taxonomylabels,
     'show_ui' => true,
     'show_admin_column' => true,
     'query_var' => true,
     'rewrite' => array( 'slug' => $taxonomyslug ),
   ));
	 }

	 /* Page tweaks -- place event inputs on page */
	 add_action('edit_form_after_editor', 'simpleclinic_add_medicaloffices_page_editor');

	 $ISSmedical_provider_inputs = array(
	 	array (
	 		"type" => "text",
	 		"optionname" => "jobtitle",
	 		"name" => "Job title",
	 		"description" => "ex. \"Dermatologist\"  or \"Pediatrician\""
	 	),
	 	array (
	 		"type" => "text",
	 		"optionname" => "suffix",
	 		"name" => "Professional sufix",
	 		"description" => "ex. \"MD\"."
	 	),
	 	array (
	 		"type" => "imageuploader",
	 		"optionname" => "pracphoto",
	 		"name" => "Headshot",
	 		"description" => 'We recommend a square photo, roughly 500x500px with a dpi of 72.'
	 	),
	   array (
	     "type" => "imageuploader",
	     "optionname" => "pracphoto2",
	     "name" => "Full size photo",
	 		"description" => 'You can use any shape of photo here, so long as the file size isn\'t too massive.'
	   )
	 );


	 function simpleclinic_add_medicaloffices_page_editor() {
	 	global $post;

	 	//Get the appropriate set of inputs for the post type
	 	if (get_post_type($post->ID) == "sc_provider") {
	 		global $ISSmedical_provider_inputs;
	 		$loopthrough = $ISSmedical_provider_inputs;
	 	} else {
	 		$loopthrough = array();
	 	}

	 	if (count($loopthrough) > 0) {
	 		// Noncename needed to verify where the data originated
	 		echo '<input type="hidden" name="pagemeta_noncename" id="pagemeta_noncename" value="' .
	 				wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	 		// Get the meta data if its already been entered

	 		$i = 0;
	 		$fullColumnInputTypes = array("header", "subheader", "instructions");

	 		?>
	 		<script>
	 		jQuery(document).ready(function($){
	 			  var _custom_media = true,
	 			      _orig_send_attachment = wp.media.editor.send.attachment;
	 			  $('.uploader .button').click(function(e) {
	 			    var send_attachment_bkp = wp.media.editor.send.attachment;
	 			    var button = $(this);
	 			    var id = button.attr('id').replace('_button', '');
	 			    _custom_media = true;
	 			    wp.media.editor.send.attachment = function(props, attachment){
	 			      if ( _custom_media ) {
	 				    //Place the attachment ID into the text box
	 			        $("#"+id).val(attachment.id);

	 			        //Remove any previous demo images
	 			        $("#"+id).parent().find('.demoimage').remove();

	 			        //Add a demo image
	 			        $("#"+id).parent().append('<img src="'+attachment.url+'" class="demoimage" style="max-width: 300px; max-height: 150px;display: block;">');

	 			      } else {
	 			        return _orig_send_attachment.apply( this, [props, attachment] );
	 			      };
	 			    }
	 			    wp.media.editor.open(button);
	 			    return false;
	 			  });
	 			  $('.add_media').on('click', function(){
	 			    _custom_media = false;
	 			  });
	 			});
	 		</script>

	 		<?php
	 		echo "<table style='width: 100%;'>";
	 		foreach ($loopthrough as $option) {
	 			$currentValue = get_post_meta ($post->ID, $option['optionname'], true);

	 			echo "<tr>";
	 			if (in_array($option["type"], $fullColumnInputTypes)) {
	 				echo "<td colspan='2'>";
	 			} else {
	 				echo "<td style='width: 20%; vertical-align: top; font-weight: bold; text-align: right; padding-right: 10px;'>";
	 				echo "<label for='".esc_attr($option['optionname'])."'>".esc_html($option['name'])."</label>";
	 				echo "</td><td style='width: 75%; padding-bottom: 15px;'>";
	 			}

	 			switch ($option['type']) {
	 				case "header":
	 					echo "<h2 style='font-weight: bold;'>".esc_html($option['name'])."</h2>";
	 					//echo "<H3 style='clear: both; cursor: default;'>".esc_html($option['name'])."</h3>";
	 					break;
	 				case "subheader":
	 					echo "<h3 style='font-weight: bold;'>".esc_html($option['name'])."</h3>";
	 					break;
	 				case "instructions":
	 					echo "<p>".esc_html($option['name'])."</p>";
	 					break;
	 				case "text":
	 					echo "<input type='text' name='".esc_attr($option['optionname'])."' id='".esc_attr($option['optionname'])."' value='".htmlspecialchars($currentValue, ENT_QUOTES)."' style='width: 80%'>";
	 					break;
	 				case "textarea":
	 					echo "<textarea name='".esc_attr($option['optionname'])."' id='".esc_attr($option['optionname'])."' style='width: 80%; height: 150px;'>".htmlspecialchars($currentValue)."</textarea>";
	 					break;
	 				case "imageuploader":
	 					echo "<div class='uploader'>
	 					    	<input type='hidden' name='".esc_attr($option['optionname'])."' value='".htmlspecialchars($currentValue)."' id='".esc_attr($option['optionname'])."' />
	 	  						<input class='button' name='_unique_name_button' id='".esc_attr($option['optionname'])."_button' value='Upload' />";
	 	  						if (strlen($currentValue) > 0) {
	 	  							echo "<img src='".wp_get_attachment_url( $currentValue )."' class='demoimage' style='max-width: 300px; max-height: 150px;display: block;'>";
	 	  						}
	 					    	echo "</div>";
	 					    	if (strlen($currentValue) > 0) {
	 					    		echo "<p><label for='".esc_attr($option['optionname'])."_remove'>Remove image?</label> <input type='checkbox' name='".esc_attr($option['optionname'])."_remove' id='".esc_attr($option['optionname'])."_remove' value='1'></p>";
	 					    	}
	 					   break;
	 				case "checkbox":
	 					 	echo "<input type='checkbox' name='".esc_attr($option['optionname'])."' id='".esc_attr($option['optionname'])."' value='1'";
	 					 	if ($currentValue == 1) {
	 					 		echo " checked";
	 					 	}
	 					 	echo "></p>";
	 					break;
	 				case "wpeditor":
	 					wp_editor( $currentValue, $option['optionname']);
	 					break;
	 				case "dropdown":
	 						echo "<select name='".esc_attr($option['optionname'])."' id='".esc_attr($option['optionname'])."'>";
	 							foreach ($option['options'] as $selectvalue => $selectname) {
	 								echo "<option value='".esc_attr($selectvalue)."'";
	 									if ($currentValue == $selectvalue) {
	 								 		echo " selected";
	 								 	}
	 								echo ">".esc_html($selectname)."</option>";
	 							}
	 						echo "</select>";
	 					break;
	 			}
	 			if (isset($option['description']) && $option['description'] != "") {
	 							echo "<p style='clear: both; font-size: .9em; font-style: italic; margin: 0px;'>".esc_html($option['description'])."</p>";
	 						}
	 			echo "<br />";
	 			echo "</td></tr>";
	 			$i++;
	 		}
	 		echo "</table>";
	 		?>

	 		<div style='clear: left'></div>
	 <?php
	 	}
	 }

	 function save_simpleclinic_provider_stuff($post_id, $post) {

	 	if (get_post_type($post->ID) == "sc_provider") {
	 		global $ISSmedical_provider_inputs;
	 		$loopthrough = $ISSmedical_provider_inputs;
	 	} else {
	 		$loopthrough = array();
	 	}

	 	if (count($loopthrough) > 0 ) {
	 		// verify this came from the our screen and with proper authorization,
	 		// because save_post can be triggered at other times
	 		if ( !wp_verify_nonce( $_POST['pagemeta_noncename'], plugin_basename(__FILE__) )) {
	 			return $post->ID;
	 		}

	 		// Is the user allowed to edit the post or page?
	 		if ( !current_user_can( 'edit_post', $post->ID ))
	 		return $post->ID;

	 		// OK, we're authenticated: we need to find and save the data
	 		// We'll put it into an array to make it easier to loop though.

	 		foreach ($loopthrough as $option) {
	 			$currentValue = $_POST[$option['optionname']];
	 			switch ($option['type']) {
	 				case "text":
	 					$provider_meta[$option['optionname']] = sanitize_text_field($currentValue);
	 					break;
	 				case "textarea":
	 					$provider_meta[$option['optionname']] = sanitize_textarea_field($currentValue);
	 					break;
	 				case "imageuploader":
	 					//If they've entered a new value for the image, save over the old one
	 					if (!empty($currentValue) && $currentValue > 0) {
	 						$provider_meta[$option['optionname']] = sanitize_text_field($currentValue);
	 					} else {
	 						$provider_meta[$option['optionname']] = get_post_meta($post->ID, $option['optionname'], FALSE);
	 					}

	 					if ($_POST[$option['optionname']."_remove"] == 1) {
	 						$provider_meta[$option['optionname']] = null;
	 					}

	 					break;
	 				case "checkbox":
	 					if ($currentValue != 1) {$currentValue =0;}
	 					$provider_meta[$option['optionname']] = $currentValue;
	 					break;
	 				case "wpeditor":
	 					$provider_meta[$option['optionname']] = wp_kses($currentValue);
	 					break;
	 				case "dropdown":
	 					if (array_key_exists($currentValue, $option['options'])) {
	 						$provider_meta[$option['optionname']] = $currentValue;
	 					}
	 					break;
	 			}
	 		}

	 		// Add values of $events_meta as custom fields

	 		foreach ($provider_meta as $key => $value) { // Cycle through the $provider_meta array!
	 		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
	 		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
	 		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
	 		update_post_meta($post->ID, $key, $value);
	 		} else { // If the custom field doesn't have a value
	 		add_post_meta($post->ID, $key, $value);
	 		}
	 		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	 		}
	 	}
	 }

	 add_action('save_post', 'save_simpleclinic_provider_stuff', 1, 2); // save the custom fields

function simpleclinic_get_image_id_by_url($image_url) {

  //Remove any timestamp that was attached
  if (strpos($image_url, '?time=')) {
    $imagearray = explode('?time=', $image_url);
    $image_url = $imagearray[0];
  }

	$url = preg_replace('/-\d+x\d+$/', '', substr($image_url, 0, -4));
	$urlnew = $url.".".substr($image_url, -3,3);

  //Knock off the http or https
  $urlnew = preg_replace('/http:\/\//', '', $urlnew);
  $urlnew = preg_replace('/https:\/\//', '', $urlnew);

	global $wpdb;
  $preparedquery = $wpdb->prepare( "SELECT ID FROM `{$wpdb->base_prefix}posts` WHERE guid LIKE %s;", '%' . $wpdb->esc_like($urlnew));

	$attachment = $wpdb->get_col($preparedquery);

  if (is_array($attachment) && count($attachment) > 0) {
    return $attachment[0];
  }

  //If we didn't find it, it may be because of a caching issue, like on GoDaddy where the actual site URL is replaced by a string like secureservercdn.net/000.000.000.000/123.456.myftpupload.com/
  $urlarray = explode('wp-content', $urlnew);
  $urlnew = 'wp-content'.$urlarray[1];

  $preparedquery = $wpdb->prepare( "SELECT ID FROM `{$wpdb->base_prefix}posts` WHERE guid LIKE %s;", '%' . $wpdb->esc_like($urlnew));

  $attachment = $wpdb->get_col($preparedquery);
  if (is_array($attachment) && count($attachment) > 0) {
    return $attachment[0];
  }

  //If we didn't find it, it may be because this image was edited and a -e(timestamp) has been appended to the main filename. We will need to search again without that extra stuff.
  $url = preg_replace('/-e[0-9]+/', '', substr($image_url, 0, -4));
  $notimeurl = $url.".".substr($image_url, -3,3);

if ($notimeurl != ".") {
  global $wpdb;
  //Knock off the http or https
  $notimeurl = preg_replace('/http:\/\//', '', $notimeurl);
  $notimeurl = preg_replace('/https:\/\//', '', $notimeurl);

  $preparedquery = $wpdb->prepare( "SELECT ID FROM `{$wpdb->base_prefix}posts` WHERE guid LIKE %s;", '%' . $wpdb->esc_like($notimeurl));

  $attachment = $wpdb->get_col($preparedquery);
  if (is_array($attachment) && count($attachment) > 0) {
    return $attachment[0];
  }
}

  return null;
}




add_action( 'after_setup_theme', 'simpleclinic_image_setup' );
function simpleclinic_image_setup() {
  $options = get_option( 'simpleclinic_settings' );
  $height = 500;
  $width = 500;
  $crop = true;

   if (isset($options['simpleclinic_imagewidth']) && is_numeric($options['simpleclinic_imagewidth'])) {
     $width = $options['simpleclinic_imagewidth'];
   }

   if (isset($options['simpleclinic_imageheight']) && is_numeric($options['simpleclinic_imageheight'])) {
     $height = $options['simpleclinic_imageheight'];
   }

   if (isset($options['simpleclinic_crop']) && $options['simpleclinic_crop'] == 'nocrop') {
     $crop = false;
   }

    add_image_size( 'simpleclinic_provider-square', $width, $height, $crop );
}


require_once( plugin_dir_path( __FILE__ ) . 'avada/fusionbuilder.php');


require_once( plugin_dir_path( __FILE__ ) . 'settings.php');
require_once( plugin_dir_path( __FILE__ ) . 'gutenberg/gutenberg.php');


add_filter( 'the_content', 'simpleclinic_filter_the_content_in_the_main_loop', 1 );

function simpleclinic_filter_the_content_in_the_main_loop( $content ) {
  $options = get_option( 'simpleclinic_settings' );
     if ( is_singular('sc_provider') && in_the_loop() && (!isset($options['simpleclinic_addphoto']) || $options['simpleclinic_addphoto'] == 'show')) {
       $image_markup_overall = '';
       $thumbnail_id = get_post_meta( get_the_ID(), 'pracphoto2', true );
       $attachment_id = $thumbnail_id;

       if (!$attachment_id) {
         $thumbnail_id = get_post_meta( get_the_ID(), 'pracphoto', true );
         $attachment_id = $thumbnail_id;
       }

       $size = 'medium';

       $image_markup_overall = '';
       $image_markup = '';

       if ( $attachment_id ) :
         $attachment_image = wp_get_attachment_image_src( $attachment_id, $size );
         $attachment_data['alt'] = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
         $attachment_data['title'] = get_post_meta( $attachment_id, '_wp_attachment_image_title', true );

          if ( is_array( $attachment_data ) ) :

           $image_markup_overall .=  '<div class="provider-image-wrapper">';
                 $image_markup = '<img src="' . $attachment_image[0] . '" alt="' . $attachment_data['alt'] . '" class="wp-image-' . $attachment_id . ' alignright size-'.$size.'" />';

                 if ( function_exists( 'wp_image_add_srcset_and_sizes' ) ) :
                  $image_markup_overall .= wp_image_add_srcset_and_sizes( $image_markup, wp_get_attachment_metadata($attachment_id), $attachment_id ); // WPCS: XSS ok.
                 else :
                 $image_markup_overall .= $image_markup; // WPCS: XSS ok.
                 endif;
               $image_markup_overall .= '</div>';
             endif;
           endif;

        return $image_markup_overall. $content;
    }
    return $content;
}

function simpleclinic_order_practitioners_alphabetically($posts) {

  $options = get_option( 'simpleclinic_settings' );

  if (!isset($options['simpleclinic_turn_off_alphabetical_order']) || !$options['simpleclinic_turn_off_alphabetical_order']) {
    // Order by second word in title, deal with edge cases
    $lastname = array();
    foreach( $posts as $key => $post ) {
        $word = explode( ' ', $post->post_title );
        $name = null;

        end($word);         // move the internal pointer to the end of the array
        $key2 = key($word);

        $wordsToSkip = array('jr', 'sr', 'jr.', 'sr.', 'i', 'ii', 'iii');

       if( in_array($word[$key2], $wordsToSkip) ) {
            // Third word is 'Sr.', so use 2nd word
            $key2-1;
            $name = $word[$key2];
        } else {
            $name = $word[$key2];
        }

        $lastname[$key] = $name;
    }
    array_multisort( $lastname, SORT_ASC, $posts );
  }
  return $posts;
}
