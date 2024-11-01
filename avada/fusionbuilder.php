<?php

	 add_action( 'avada_override_current_page_title_bar', 'simpleclinic_add_medical_suffix' );
	 function simpleclinic_add_medical_suffix($postID) {

	   if ( is_singular( 'sc_provider' ) ) {
	     $pagetitle = get_the_title($postID);
	     $letters_after_name = get_post_meta( $postID, 'suffix', true );

	     if ($letters_after_name) {
	       $pagetitle .= ', '.$letters_after_name;
	     }

	     $subtitle = get_post_meta( $postID, 'jobtitle', true );

	       avada_page_title_bar($pagetitle, $subtitle, '');
	   } else {
	     $post_id = $postID;
	 		if ( 'hide' !== fusion_get_option( 'page_title_bar' ) ) {
	 			$page_title_bar_contents = Fusion_Helper::fusion_get_page_title_bar_contents( $post_id );

	 			avada_page_title_bar( $page_title_bar_contents[0], $page_title_bar_contents[1], $page_title_bar_contents[2] );
	 		}
	 		do_action( 'avada_after_page_title_bar' );
	   }
	 }

   add_shortcode( 'simpleclinicproviders', 'simpleclinic_avada_show_all_providers_func' );
	 function simpleclinic_avada_show_all_providers_func( $atts  ) {
		   if (class_exists ('FusionBuilder') && class_exists ('Avada')) {

	   $atts = shortcode_atts( array(
	   		'providers_per_row' => 5,
				'include_providers'=>'',
				'exclude_providers'=>'',
	   	), $atts, 'show_all_providers_func' );

			$args = array(
 	       'post_type'        => 'sc_provider',
 	       'posts_per_page'   => -1,
 	   );


			$exclude = preg_replace('/\s+/', '', $atts['exclude_providers']);

			if (strlen($exclude) > 0) {
				$args['exclude'] = explode(',', $exclude);
			}

			$include = preg_replace('/\s+/', '', $atts['include_providers']);

			if (strlen($include) > 0) {
				$args['include'] = explode(',', $include);
			}


	   $posts = get_posts( $args );
		 $posts = simpleclinic_order_practitioners_alphabetically($posts);
	 $size = 'simpleclinic_provider-square';
	   // Loop through posts
	   $content = '
	   <div class="fusion-blog-shortcode fusion-blog-shortcode-10 fusion-blog-archive fusion-blog-layout-grid-wrapper fusion-blog-no"><style type="text/css">.fusion-blog-shortcode-10 .fusion-blog-layout-grid .fusion-post-grid{padding:20px;}.fusion-blog-shortcode-10 .fusion-posts-container{margin-left: -20px !important; margin-right:-20px !important;}</style>
	     <div class="fusion-posts-container fusion-posts-container-no fusion-no-meta-info fusion-blog-rollover fusion-blog-layout-grid fusion-blog-layout-grid-'.$atts['providers_per_row'].' isotope fusion-blog-equal-heights" data-pages="0" data-grid-col-space="40" style="margin: -20px -20px 0px; position: relative;">';


	   foreach( $posts as $post ) {

	     $thumbnail_id = get_post_meta( $post->ID, 'pracphoto', true );
	     $attachment_id = $thumbnail_id;
	     $attachment_data = Avada()->images->get_attachment_data( $thumbnail_id );


	     $image_markup_overall = '';
	     $image_markup = '';
	     if ( $attachment_id ) :
	       $attachment_image = wp_get_attachment_image_src( $attachment_id, $size );
	       $attachment_data = Avada()->images->get_attachment_data( $attachment_id );

	        if ( is_array( $attachment_data ) ) :

	         $image_markup_overall .=  '<div class="fusion-image-wrapper">
	             <a href="'.get_the_permalink($post->ID).'" aria-label="Headshot of '. get_the_title().'">';
	               $image_markup = '<img src="' . $attachment_image[0] . '" alt="' . $attachment_data['alt'] . '" class="wp-image-' . $attachment_id . '" role="presentation"/>';
	               $image_markup = Avada()->images->edit_grid_image_src( $image_markup, get_the_ID(), $attachment_id, $size );

	               if ( function_exists( 'wp_image_add_srcset_and_sizes' ) ) :
	                $image_markup_overall .= wp_image_add_srcset_and_sizes( $image_markup, wp_get_attachment_metadata($attachment_id), $attachment_id ); // WPCS: XSS ok.
	               else :
	               $image_markup_overall .= $image_markup; // WPCS: XSS ok.
	               endif;
	             $image_markup_overall .= '</a></div>';
	           endif;
	         endif;

	      $content .= '<article id="provider-'.get_the_ID().'" class="post type-post format-standard  fusion-post-grid post-'.get_the_ID().' provider type-provider status-publish hentry">
	        <div class="fusion-post-wrapper" style="background-color:rgba(255,255,255,0);border:0px solid #ffffff;border-bottom-width:0px;">'.$image_markup_overall.'
	          <div class="fusion-provider-content-wrapper"><div class="fusion-provider-content provider-content">
	           <h2 class="blog-shortcode-post-title entry-title"><a href="'.get_the_permalink($post->ID).'">'. $post->post_title.'</a></h2>
	           <p class="provider-job">'.get_post_meta( $post->ID, 'jobtitle', true ).'</p>
	         </div>
	       </div>
	       <div class="fusion-clearfix"></div></div>
	      </article>';
	   }
	   wp_reset_postdata();

	     $content .= '<div class="fusion-clearfix"></div></div></div>';
	   return $content;
	 	}
	 }



	 function simpleclinic_add_providers_generator_element() {
		   if (class_exists ('FusionBuilder') && class_exists ('Avada')) {
				 fusion_builder_map(
				     array(
				         'name'            => esc_attr__( 'Providers', 'fusion-builder' ),
				         'shortcode'       => 'simpleclinicproviders',
				         'icon'            => 'fusiona-font',
				         'params'          => array(
				             array(
				                 'type'        => 'range',
				                 'heading'     => esc_attr__( 'How many per row?', 'fusion-builder' ),
				                 'description' => esc_attr__( 'How many providers should be on a row?', 'fusion-builder' ),
				                 'param_name'  => 'providers_per_row',
				                 'value'       => '5',
				                 'min'         => '2',
				                 'max'         => '6',
				                 'step'        => '1',
				             ),

										 array(
											 'type'        => 'textfield',
											 'heading'     => esc_attr__( 'Include providers by ID', 'fusion-builder' ),
											 'param_name'  => 'include_providers',
											 'value'       => '',
											 'description' => esc_attr__( 'Enter a comma-separated list of provider IDs that should be included. Leave blank for all.', 'fusion-builder' ),
										 ),
										 array(
											 'type'        => 'textfield',
											 'heading'     => esc_attr__( 'Exclude providers by ID', 'fusion-builder' ),
											 'param_name'  => 'exclude_providers',
											 'value'       => '',
											 'description' => esc_attr__( 'Enter a comma-separated list of provider IDs that should be excluded from display. Leave blank for all.', 'fusion-builder' ),
										 ),
				         ),
				     )
				 );
			 }
	 }
	 add_action( 'fusion_builder_before_init', 'simpleclinic_add_providers_generator_element' );


class SimpleClinicFusion_modalisitiesoverview {

	/**
	 * The alert class.
	 *
	 * @access private
	 * @since 1.0
	 * @var string
	 */
	private $modalisitiesoverview_class;

	/**
	 * An array of the shortcode arguments.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var array
	 */
	public static $args;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {


    add_filter( 'fusion_attr_modalisitiesoverview-shortcode', array( $this, 'attr' ) );
    add_filter( 'fusion_attr_modalisitiesoverview-shortcode-section', array( $this, 'section_attr' ) );
    add_filter( 'fusion_attr_modalisitiesoverview-shortcode-column', array( $this, 'column_attr' ) );
    add_filter( 'fusion_attr_modalisitiesoverview-shortcode-slideshow', array( $this, 'slideshow_attr' ) );
    add_filter( 'fusion_attr_modalisitiesoverview-shortcode-img', array( $this, 'img_attr' ) );
    add_filter( 'fusion_attr_modalisitiesoverview-shortcode-img-link', array( $this, 'link_attr' ) );

    add_shortcode( 'simpleclinic_specialty_display', array( $this, 'render' ) );
	}

	/**
	 * Render the shortcode
	 *
	 * @access public
	 * @since 1.0
	 * @param  array  $args    Shortcode parameters.
	 * @param  string $content Content between shortcode.
	 * @return string          HTML output.
	 */
	public function render( $args, $content = '' ) {
		$defaults = FusionBuilder::set_shortcode_defaults(
			array(
				'hide_on_mobile'      => fusion_builder_default_visibility( 'string' ),
				'class'               => '',
				'id'                  => '',
        'post-type' => 'sc_provider',
				'layout'              => 'default',
        'taxonomy-name' => 'sc_specialty',
        'showcatimage' => 'show',
        'showtitle' => 'show',
        'showdesc' => 'show',
        'hide-empty' => 'yes',
        'columns' => 3,
				'exclude_specialties'=> '',
				'include_specialties'=> '',
        'excerpt_length'      => '',
        'excerpt_words'       => '15', // Deprecated.
        'animation_direction' => 'left',
				'animation_speed'     => '',
				'animation_type'      => '',
				'animation_offset'    => ( class_exists( 'Avada' ) ) ? Avada()->settings->get( 'animation_offset' ) : '',
        'hover_type'          => 'none',
			), $args
		);

    if ( $defaults['columns'] > 6 ) {
      $defaults['columns'] = 6;
    }

    $defaults['hide-empty'] = ( 'yes' === $defaults['hide-empty'] || 'true' === $defaults['hide-empty'] ) ? true : false;


    if ( $defaults['excerpt_length'] || '0' === $defaults['excerpt_length'] ) {
      $defaults['excerpt_words'] = $defaults['excerpt_length'];
    }
		extract( $defaults );

		self::$args = $defaults;

$args = array(
    'hide_empty' => self::$args['hide-empty']
	);
	$exclude = preg_replace('/\s+/', '', self::$args['exclude_specialties']);

	if (strlen($exclude) > 0) {
		$args['exclude'] = explode(',', $exclude);
	}

	$include = preg_replace('/\s+/', '', self::$args['include_specialties']);

	if (strlen($include) > 0) {
		$args['include'] = explode(',', $include);
	}

$terms = get_terms(self::$args['taxonomy-name'], $args );

$html = '';
  if (count($terms)) {
    $items = '';
    foreach ($terms as $term) {
      $attachment = $slideshow = $slides = $content = '';

      if ( 'show' ===  self::$args['showcatimage'] || 'Show' ===  self::$args['showcatimage'] ) {
        if ( 'default' == self::$args['layout'] ) {
          $image_size = 'recent-posts';
        } elseif ( 'thumbnails-on-side' == self::$args['layout'] ) {
          $image_size = 'portfolio-five';
        }

        unset($imageID);

        if (function_exists('z_taxonomy_image_url')) {
            $image_url = z_taxonomy_image_url($term->term_id);
            $imageID =  simpleclinic_get_image_id_by_url($image_url);
        }

				$image_markup_overall = '';

        if (isset($imageID) && $imageID) {
          $attachment_image   = wp_get_attachment_image_src( $imageID, $image_size );
          $attachment_img_tag = wp_get_attachment_image($imageID, $image_size );
          $attachment_img_tag_custom = '<img src="' . $attachment_image[0] . '" alt="' . get_post_meta( $imageID, '_wp_attachment_image_alt', true ) . '" />';

					$image_markup_overall .=  '<div class="fusion-image-wrapper">
				 		 <a href="'.get_term_link($term->term_id).'">';
				 			 $image_markup = Avada()->images->edit_grid_image_src( $attachment_img_tag_custom, $term->term_id, $imageID, $image_size );

				 			 if ( function_exists( 'wp_image_add_srcset_and_sizes' ) ) :
				 				$image_markup_overall .= wp_image_add_srcset_and_sizes( $image_markup, wp_get_attachment_metadata($imageID), $imageID ); // WPCS: XSS ok.
				 			 else :
				 			 $image_markup_overall .= $image_markup; // WPCS: XSS ok.
				 			 endif;
				 		 $image_markup_overall .= '</a></div>';
        }
      }

      if ( 'show' ==  self::$args['showtitle'] || 'Show' ==  self::$args['showtitle'] ) {
      //  $content .= ( function_exists( 'avada_render_rich_snippets_for_pages' ) ) ? avada_render_rich_snippets_for_pages( false ) : '';

        $content .= '<h4 class="' . $term->name . '"><a href="' . get_term_link($term->term_id) . '">' . $term->name . '</a></h4>';
      } else {
        $content .= avada_render_rich_snippets_for_pages();
      }


      if ( ('show' == self::$args['showdesc'] || 'Show' == self::$args['showdesc']) && isset($term->description) && strlen($term->description) > 0) {

        $content .= wp_trim_words( $term->description, self::$args['excerpt_length'], $more = null );
      }

			$items .= '<div ' . FusionBuilder::attributes( 'modalisitiesoverview-shortcode-column' ) . '>' .  $image_markup_overall . '<div ' . FusionBuilder::attributes( 'modalisitiesoverview-content' ) . '>' . $content . '</div></div>';
    }

    $html = '<div ' . FusionBuilder::attributes( 'modalisitiesoverview-shortcode' ) . '><section ' . FusionBuilder::attributes( 'modalisitiesoverview-shortcode-section' ) . '>' . $items . '</section></div>';
  }


		return $html;

	}


  public function section_attr() {
		return array(
			'class' => 'fusion-columns columns fusion-columns-' . self::$args['columns'] . ' columns-' . self::$args['columns'],
		);
	}

	/**
	 * Builds the column attributes array.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function column_attr() {

		$columns = 3;
		if ( self::$args['columns'] ) {
			$columns = 12 / self::$args['columns'];
		}

		$attr = array(
			'class' => 'fusion-column column col col-lg-' . $columns . ' col-md-' . $columns . ' col-sm-' . $columns . '',
		);

		if ( '5' == self::$args['columns'] ) {
			$attr['class'] = 'fusion-column column col-lg-2 col-md-2 col-sm-2';
		}

		if ( self::$args['animation_type'] ) {
			$animations = FusionBuilder::animations( array(
				'type'      => self::$args['animation_type'],
				'direction' => self::$args['animation_direction'],
				'speed'     => self::$args['animation_speed'],
				'offset'    => self::$args['animation_offset'],
			) );

			$attr = array_merge( $attr, $animations );

			$attr['class'] .= ' ' . $attr['animation_class'];
			unset( $attr['animation_class'] );
		}

		return $attr;

	}

	/**
	 * Builds the slideshow attributes array.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function slideshow_attr() {

		$attr = array(
			'class' => 'fusion-flexslider flexslider',
		);

		if ( 'thumbnails-on-side' == self::$args['layout'] ) {
			$attr['class'] .= ' floated-slideshow';
		}

		if ( self::$args['hover_type'] ) {
			$attr['class'] .= ' flexslider-hover-type-' . self::$args['hover_type'];
		}

		return $attr;

	}

	/**
	 * Builds the image attributes array.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $args The arguments array.
	 * @return array
	 */
	public function img_attr( $args ) {

		$attr = array(
			'src' => $args['src'],
		);

		if ( $args['alt'] ) {
			$attr['alt'] = $args['alt'];
		}

		return $attr;

	}

	/**
	 * Builds the link attributes array.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $args The arguments array.
	 * @return array
	 */
	public function link_attr( $args ) {

		$attr = array();

		if ( self::$args['hover_type'] ) {
			$attr['class'] = 'hover-type-' . self::$args['hover_type'];
		}

		return $attr;

	}

	/**
	 * Builds the attributes array.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function attr() {

    $attr = fusion_builder_visibility_atts( self::$args['hide_on_mobile'], array(
			'class' => 'fusion-taxonomy-overview avada-container layout-' . self::$args['layout'] . ' layout-columns-' . self::$args['columns'],
		) );

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	/**
	 * Builds the button attributes array.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function button_attr() {

		$attr = array();

		if ( 'custom' === $this->alert_class ) {
			$attr['style'] = 'color:' . self::$args['accent_color'] . ';border-color:' . self::$args['accent_color'] . ';';
		}

		$attr['type']         = 'button';
		$attr['class']        = 'close toggle-alert';
		$attr['data-dismiss'] = 'alert';
		$attr['aria-hidden']  = 'true';

		return $attr;

	}
}

new SimpleClinicFusion_modalisitiesoverview();


/**
 * Map shortcode to Fusion Builder
 *
 * @since 1.0
 */
function simpleclinic_fusion_element_specialty_display() {
  if (class_exists ('FusionBuilder') && class_exists ('Avada')) {
	  fusion_builder_map( array(
	    'name'            => esc_attr__( 'Specialties', 'fusion-builder' ),
	    'shortcode'       => 'simpleclinic_specialty_display',
	    'icon'            => 'fa fa-lg fa-th',
	    'allow_generator' => true,
	    'params'          => array(
	      array(
	        'type'        => 'radio_button_set',
	        'heading'     => esc_attr__( 'Hide empty specialty categories', 'fusion-builder' ),
	        'param_name'  => 'hide-empty',
	        'value'       => array(
	          esc_attr__( 'Yes', 'fusion-builder' )    => 'yes',
	          esc_attr__( 'No', 'fusion-builder' )  => 'no',
	        ),
	        'default'     => 'yes',
	      ),
	      array(
	        'type'        => 'select',
	        'heading'     => esc_attr__( 'Display category image', 'fusion-builder' ),
	        'description' => esc_attr__( 'Select whether to show a category image.', 'fusion-builder' ),
	        'param_name'  => 'showcatimage',
	        'default'     => 'show',
	        'value'       => array(
	          esc_attr__( 'Show', 'fusion-builder' ) => 'show',
	          esc_attr__( 'Hide', 'fusion-builder' )   => 'hide'
	        ),
	      ),
	      array(
	        'type'        => 'select',
	        'heading'     => esc_attr__( 'Display specialty title', 'fusion-builder' ),
	        'description' => esc_attr__( 'Select whether to show the specialty title.', 'fusion-builder' ),
	        'param_name'  => 'showtitle',
	        'default'     => 'show',
	        'value'       => array(
	          esc_attr__( 'Show', 'fusion-builder' ) => 'show',
	          esc_attr__( 'Hide', 'fusion-builder' )   => 'hide'
	        ),
	      ),

	      array(
	        'type'        => 'select',
	        'heading'     => esc_attr__( 'Display specialty description', 'fusion-builder' ),
	        'description' => esc_attr__( 'Select whether to show the specialty description.', 'fusion-builder' ),
	        'param_name'  => 'showdesc',
	        'default'     => 'show',
	        'value'       => array(
	          esc_attr__( 'Show', 'fusion-builder' ) => 'show',
	          esc_attr__( 'Hide', 'fusion-builder' )   => 'hide'
	        ),
	      ),
	      array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Excerpt Length', 'fusion-builder' ),
					'description' => esc_attr__( 'Insert the number of words/characters you want to show in the excerpt.', 'fusion-builder' ),
					'param_name'  => 'excerpt_length',
					'value'       => '35',
					'min'         => '0',
					'max'         => '500',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'showdesc',
							'value'    => 'show',
							'operator' => '==',
						),
					),
				),
	      array(
	        'type'        => 'range',
	        'heading'     => esc_attr__( 'Columns', 'fusion-builder' ),
	        'description' => esc_attr__( 'Select the number of columns to display.', 'fusion-builder' ),
	        'param_name'  => 'columns',
	        'value'       => '3',
	        'min'         => '1',
	        'max'         => '6',
	        'step'        => '1',
	      ),

				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Include specialties by ID', 'fusion-builder' ),
					'param_name'  => 'include_specialties',
					'value'       => '',
					'description' => esc_attr__( 'Enter a comma-separated list of specialty IDs that should be included. Leave blank for all.', 'fusion-builder' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Exclude specialties by ID', 'fusion-builder' ),
					'param_name'  => 'exclude_specialties',
					'value'       => '',
					'description' => esc_attr__( 'Enter a comma-separated list of specialty IDs that should be excluded from display. Leave blank for all.', 'fusion-builder' ),
				),

	      array(
	        'type'        => 'checkbox_button_set',
	        'heading'     => esc_attr__( 'Element Visibility', 'fusion-builder' ),
	        'param_name'  => 'hide_on_mobile',
	        'value'       => fusion_builder_visibility_options( 'full' ),
	        'default'     => fusion_builder_default_visibility( 'array' ),
	        'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'fusion-builder' ),
	      ),
	      array(
	        'type'        => 'textfield',
	        'heading'     => esc_attr__( 'CSS Class', 'fusion-builder' ),
	        'param_name'  => 'class',
	        'value'       => '',
	        'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'fusion-builder' ),
	      ),
	      array(
	        'type'        => 'textfield',
	        'heading'     => esc_attr__( 'CSS ID', 'fusion-builder' ),
	        'param_name'  => 'id',
	        'value'       => '',
	        'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'fusion-builder' ),
	      ),
	    ),
	  ) );
	}
}
add_action( 'fusion_builder_before_init', 'simpleclinic_fusion_element_specialty_display' );
