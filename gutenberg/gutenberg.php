<?php
function simpleclinic_loadblocks() {
  wp_enqueue_script(
    'iss-providers',
    plugin_dir_url(__FILE__) . 'js/providerblock.js',
    array('wp-blocks','wp-editor'),
    true
  );
}
add_action('enqueue_block_editor_assets', 'simpleclinic_loadblocks');

function simpleclinic_registerblocks() {
    register_block_type( 'simpleclinic/providers', [
      'render_callback' => 'simpleclinic_providers_block_dynamic_render_callback'
    ]
  );

  register_block_type( 'simpleclinic/specialties', [
    'render_callback' => 'simpleclinic_specialties_block_dynamic_render_callback'
  ]
);
}
add_action('init', 'simpleclinic_registerblocks');

function simpleclinic_enqueueblockstyles() {
   wp_enqueue_style( 'iss-medical-office-css', plugin_dir_url(__FILE__) . 'css/medicaloffice.css', array() );
}

add_action('wp_enqueue_scripts', 'simpleclinic_enqueueblockstyles');


function simpleclinic_providers_block_dynamic_render_callback( $block_attributes, $content ) {
  $providers_per_row = 4;
  if (isset($block_attributes['providers_per_row'])) {
    $providers_per_row = $block_attributes['providers_per_row'];
  }

  $align_center = false;
  if (isset($block_attributes['align_center'])) {
    $align_center = $block_attributes['align_center'];
  }

  $includesuffix = false;
  if (isset($block_attributes['include_suffix'])) {
    $includesuffix = $block_attributes['include_suffix'];
  }
  $args = array(
      'post_type'        => 'sc_provider',
      'posts_per_page'   => -1,
  );

  $posts = get_posts( $args );
$posts = simpleclinic_order_practitioners_alphabetically($posts);
  $content = '<div class="simpleclinic_providers per-row-'.$providers_per_row;

  if ($align_center) {
    $content .= ' center';
  }

  $content .='">';
  $size = 'simpleclinic_provider-square';

  foreach( $posts as $post ) {
    $thumbnail_id = get_post_meta( $post->ID, 'pracphoto', true );
    $attachment_id = $thumbnail_id;

    if (!$attachment_id) {
      $thumbnail_id = get_post_meta( $post->ID, 'pracphoto2', true );
      $attachment_id = $thumbnail_id;
    }

    $image_markup_overall = '';
    $image_markup = '';

    if ( $attachment_id ) :
      $attachment_image = wp_get_attachment_image_src( $attachment_id, $size );
      $attachment_data['alt'] = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
      $attachment_data['title'] = get_post_meta( $attachment_id, '_wp_attachment_image_title', true );

       if ( is_array( $attachment_data ) ) :

        $image_markup_overall .=  '<div class="provider-image-wrapper">
            <a href="'.get_the_permalink($post->ID).'" aria-label="Headshot of '. get_the_title().'">';
              $image_markup = '<img src="' . $attachment_image[0] . '" alt="' . $attachment_data['alt'] . '" class="wp-image-' . $attachment_id . '" role="presentation"/>';

              if ( function_exists( 'wp_image_add_srcset_and_sizes' ) ) :
               $image_markup_overall .= wp_image_add_srcset_and_sizes( $image_markup, wp_get_attachment_metadata($attachment_id), $attachment_id ); // WPCS: XSS ok.
              else :
              $image_markup_overall .= $image_markup; // WPCS: XSS ok.
              endif;
            $image_markup_overall .= '</a></div>';
          endif;
        endif;

     $content .= '<article id="provider-'.get_the_ID().'" class="provider-'.get_the_ID().' provider type-provider status-publish">'.$image_markup_overall.'
          <h3 class="entry-title provider-title"><a href="'.get_the_permalink($post->ID).'">'. $post->post_title;

          $suffix = get_post_meta( $post->ID, 'suffix', true );
          if ($includesuffix && $suffix) {
            $content .='<span class="suffix">, '.$suffix.'</span>';
          }

          $content .='</a></h3>
          <p class="provider-job">'.get_post_meta( $post->ID, 'jobtitle', true ).'</p></article>';
  }
  $content .= '<div class="clear"></div></div>'; //class="simpleclinic_providers"
  wp_reset_postdata();

return $content;
}

function simpleclinic_specialties_block_dynamic_render_callback($block_attributes, $content) {
  $per_row = 4;
  if (isset($block_attributes['specialties_per_row'])) {
    $per_row = $block_attributes['specialties_per_row'];
  }

  $align_center = false;
  if (isset($block_attributes['align_center'])) {
    $align_center = $block_attributes['align_center'];
  }

  $hideempty = true;
  if (isset($block_attributes['hide_empty'])) {
    $hideempty = $block_attributes['hide_empty'];
  }

  $showimages = true;
  if (isset($block_attributes['show_image'])) {
    $showimages = $block_attributes['show_image'];
  }

  $terms = get_terms('sc_specialty', array(
      'hide_empty' => $hideempty
  ) );

  $image_size = 'simpleclinic_provider-square';

  $content = '<div class="simpleclinic_specialties per-row-'.$per_row;

  if ($align_center) {
    $content .= ' center';
  }

  $content .='">';
    if (count($terms)) {
      foreach ($terms as $term) {

        unset($imageID);

        $content .='<div class="taxonomy simpleclinic_specialty simpleclinic_specialty-'.$term->term_id.'">';

        if (function_exists('z_taxonomy_image_url')) {
            $image_url = z_taxonomy_image_url($term->term_id);
            $imageID =  simpleclinic_get_image_id_by_url($image_url);


            if (isset($imageID) && $imageID) {
              $attachment_image   = wp_get_attachment_image_src( $imageID, $image_size );
              $attachment_img_tag = wp_get_attachment_image($imageID, $image_size );

              $attachment_img_tag_custom = '<img src="' . $attachment_image[0] . '" alt="' . get_post_meta( $imageID, '_wp_attachment_image_alt', true ) . '" />';

              $full_image = wp_get_attachment_image_src( $imageID, 'full' );
              $attachment_data = wp_get_attachment_metadata($imageID);
              $attachment = get_post($imageID);
              $content .= '<div class="specialty-image-wrapper"><a href="' . get_term_link($term->term_id) . '">' . $attachment_img_tag_custom . '</a></div>';
            //  $slideshow = '<div ' . FusionBuilder::attributes( 'modalisitiesoverview-shortcode-slideshow' ) . '><ul ' . FusionBuilder::attributes( 'slides' ) . '>' . $slides . '</ul></div>';
          } elseif($image_url) {
            $content .= '<div class="specialty-image-wrapper"><a href="' . get_term_link($term->term_id) . '"><img src="' . $image_url . '"></a></div>';
          }
        }

        $content .= '<a href="'.get_term_link($term->term_id).'"><h3 class="taxonomy simpleclinic_specialty simpleclinic_specialty-'.$term->term_id.'">'.$term->name.'</h3></a>';
        $content .='<div class="clear"></div></div>';
      }
    }

    $content .= '</div>';

    return $content;
}
