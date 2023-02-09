<?php
/**
 * Partner/Logo Element
 *
 * Shortcode that allows to display a simple partner logo grid or slider
 */
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( ! class_exists( 'avia_sc_partner_logo' ) )
{
	class avia_sc_partner_logo extends aviaShortcodeTemplate
	{
		/**
		 * Save avia_partner_logo objects for reuse. As we need to access the same object when creating the post css file in header,
		 * create the styles and HTML creation. Makes sure to get the same id.
		 *
		 *			$element_id	=> avia_partner_logo
		 *
		 * @since 4.8.8.1
		 * @var array
		 */
		protected $obj_partner_logo = array();

		/**
		 * @since 4.8.8.1
		 * @param \AviaBuilder $builder
		 */
		public function __construct( \AviaBuilder $builder )
		{
			parent::__construct($builder);

			$this->obj_partner_logo = array();
		}

		/**
		 * @since 4.8.8.1
		 */
		public function __destruct()
		{
			unset( $this->obj_partner_logo );

			parent::__destruct();
		}

		/**
		 * Create the config array for the shortcode button
		 */
		function shortcode_insert_button()
		{
			$this->config['version']		= '1.0';
			$this->config['self_closing']	= 'no';
			$this->config['base_element']	= 'yes';

			$this->config['name']			= __( 'Partner/Logo Element', 'avia_framework' );
			$this->config['tab']			= __( 'Media Elements', 'avia_framework' );
			$this->config['icon']			= AviaBuilder::$path['imagesURL'] . 'sc-partner.png';
			$this->config['order']			= 7;
			$this->config['target']			= 'avia-target-insert';
			$this->config['shortcode'] 		= 'av_partner';
			$this->config['shortcode_nested'] = array( 'av_partner_logo' );
			$this->config['tooltip'] 	    = __( 'Display a partner/logo Grid or Slider', 'avia_framework' );
			$this->config['preview'] 		= false;
			$this->config['disabling_allowed'] = true;
			$this->config['id_name']		= 'el_id';
			$this->config['id_show']		= 'yes';
			$this->config['name_item']		= __( 'Partner/Logo Item', 'avia_framework' );
			$this->config['tooltip_item']	= __( 'A Partner/Logo Element Item', 'avia_framework' );
		}

		function extra_assets()
		{
			//load css
			wp_enqueue_style( 'avia-module-slideshow', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/slideshow/slideshow.css', array( 'avia-layout' ), false );
			wp_enqueue_style( 'avia-module-slideshow-contentpartner', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/contentslider/contentslider.css', array( 'avia-module-slideshow' ), false );
			wp_enqueue_style( 'avia-module-postslider', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/postslider/postslider.css', array( 'avia-layout' ), false );

				//load js
			wp_enqueue_script( 'avia-module-slideshow', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/slideshow/slideshow.js', array( 'avia-shortcodes' ), false, true );
		}

		/**
		 * Popup Elements
		 *
		 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
		 * opens a modal window that allows to edit the element properties
		 *
		 * @return void
		 */
		function popup_elements()
		{
			$this->elements = array(

				array(
						'type' 	=> 'tab_container',
						'nodescription' => true
					),

				array(
						'type' 	=> 'tab',
						'name'  => __( 'Content', 'avia_framework' ),
						'nodescription' => true
					),

					array(
								'type'			=> 'template',
								'template_id'	=> $this->popup_key( 'content_entries' )
							),

				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),

				array(
						'type' 	=> 'tab',
						'name'  => __( 'Styling', 'avia_framework' ),
						'nodescription' => true
					),

					array(
							'type'			=> 'template',
							'template_id'	=> 'toggle_container',
							'templates_include'	=> array(
													$this->popup_key( 'styling_general' ),
													$this->popup_key( 'styling_controls' ),
													$this->popup_key( 'styling_nav_colors' ),
													$this->popup_key( 'styling_image' ),
													$this->popup_key( 'styling_border' ),
													$this->popup_key( 'styling_boxshadow' ),
													$this->popup_key( 'styling_padding' )
												),
							'nodescription' => true
						),

				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),

				array(
						'type' 	=> 'tab',
						'name'  => __( 'Advanced', 'avia_framework' ),
						'nodescription' => true
					),

					array(
							'type' 	=> 'toggle_container',
							'nodescription' => true
						),

						array(
								'type'			=> 'template',
								'template_id'	=> $this->popup_key( 'advanced_animation_slider' )
							),

						array(
								'type'			=> 'template',
								'template_id'	=> $this->popup_key( 'advanced_heading' )
							),

						array(
								'type'			=> 'template',
								'template_id'	=> 'lazy_loading_toggle',
								'lockable'		=> true
							),

						array(
								'type'			=> 'template',
								'template_id'	=> 'screen_options_toggle',
								'lockable'		=> true
							),

						array(
								'type'			=> 'template',
								'template_id'	=> 'developer_options_toggle',
								'args'			=> array( 'sc' => $this )
							),

					array(
							'type' 	=> 'toggle_container_close',
							'nodescription' => true
						),

				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),

				array(
						'type'			=> 'template',
						'template_id'	=> 'element_template_selection_tab',
						'args'			=> array( 'sc' => $this )
					),

				array(
						'type' 	=> 'tab_container_close',
						'nodescription' => true
					)


			);

		}

		/**
		 * Create and register templates for easier maintainance
		 *
		 * @since 4.6.4
		 */
		protected function register_dynamic_templates()
		{

			$this->register_modal_group_templates();

			/**
			 * Content Tab
			 * ===========
			 */

			$c = array(

						array(
							'name'		=> __( 'Heading', 'avia_framework' ),
							'desc'		=> __( 'Enter a heading to display above the images. Leave empty for no heading.', 'avia_framework' ),
							'id'		=> 'heading',
							'type'		=> 'input',
							'std'		=> '',
							'lockable'	=> true,
							'tmpl_set_default'	=> false
						),

						array(
							'type'			=> 'modal_group',
							'id'			=> 'content',
							'container_class'	=> 'avia-element-fullwidth avia-multi-img',
							'modal_title'	=> __( 'Edit Form Element', 'avia_framework' ),
							'add_label'		=> __( 'Add single image', 'avia_framework' ),
							'std'			=> array(),
							'editable_item'	=> true,
							'lockable'		=> true,
							'tmpl_set_default'	=> false,
							'creator'		=> array(
												'name'		=> __( 'Add Images', 'avia_framework' ),
												'desc'		=> __( 'Here you can add new Images to the partner/logo element.', 'avia_framework' ),
												'id'		=> 'id',
												'type'		=> 'multi_image',
												'title'		=> __( 'Add multiple Images', 'avia_framework' ),
												'button'	=> __( 'Insert Images', 'avia_framework' ),
												'std'		=> ''
											),
							'subelements'	=> $this->create_modal()
						),

//						array(
//							'name' 	=> __( 'Use first slides caption as permanent caption', 'avia_framework' ),
//							'desc' 	=> __( 'If checked the caption will be placed on top of the slider. Please be aware that all slideshow link settings and other captions will be ignored then', 'avia_framework' ) ,
//							'id' 	=> 'perma_caption',
//							'std' 	=> '',
//							'type' 	=> 'checkbox'
//						),

				);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'content_entries' ), $c );

			/**
			 * Styling Tab
			 * ===========
			 */

			$c = array(
						array(
							'name'		=> __( 'Logo Slider Or Logo Grid Layout', 'avia_framework' ),
							'desc'		=> __( 'Do you want to use a grid or a slider to display the logos?', 'avia_framework' ),
							'id'		=> 'type',
							'type'		=> 'select',
							'std'		=> 'slider',
							'lockable'	=> true,
							'subtype'	=> array(
												__( 'Slider', 'avia_framework' )	=> 'slider',
												__( 'Grid', 'avia_framework' )		=> 'grid'
											),
						),

						array(
								'type'			=> 'template',
								'template_id'	=> 'columns_count_icon_switcher',
								'lockable'		=> true,
								'heading'		=> array(),
								'std'			=> array(
														'default'	=> '3'
													),
								'id_sizes'		=>	array(
														'default'	=> 'columns'
													),
								'subtype'		=> array(
														'default'	=> AviaHtmlHelper::number_array( 2, 8, 1, array( __( '1 Column', 'avia_framework' )	=> '1', ), ' ' . __( 'Columns', 'avia_framework' ) ),
													)
						)

				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Slider Or Grid', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_general' ), $template );

			$c = array(

						array(
							'type'			=> 'template',
							'template_id'	=> 'slideshow_controls_small',
							'lockable'		=> true,
							'required'		=> array( 'type', 'equals', 'slider' ),
						)

				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Navigation Controls', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_controls' ), $template );

			$c = array(

						array(
							'type'			=> 'template',
							'template_id'	=> 'slideshow_navigation_colors',
							'lockable'		=> true,
							'required'		=> array( 'control_layout', 'parent_in_array', ',av-control-default' ),
						)

				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Navigation Control Colors', 'avia_framework' ),
								'content'		=> $c
							)
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_nav_colors' ), $template );

			$c = array(
						array(
							'name'		=> __( 'Partner/Logo Image Size', 'avia_framework' ),
							'desc'		=> __( 'Choose the image size. When using svg images you must select an image size. If &quot;No scaling&quot; is used we make a fallback to WP default &quot;Medium&quot;.', 'avia_framework' ),
							'id'		=> 'size',
							'type'		=> 'select',
							'std'		=> '',
							'lockable'	=> true,
							'subtype'	=> AviaHelper::get_registered_image_sizes( array( 'thumbnail', 'logo', 'widget', 'slider_thumb' ) )
						),

						array(
							'name'		=> __( 'Image Size Behaviour', 'avia_framework' ),
							'desc'		=> __( 'Should the image stretch to fill the available space?', 'avia_framework' ),
							'id'		=> 'img_size_behave',
							'type'		=> 'select',
							'std'		=> '',
							'lockable'	=> true,
							'subtype'	=> array(
												__( 'Image stretches to fill the available space', 'avia_framework' )	=> '',
												__( 'Do not stretch image. If more space is available image will be centered.', 'avia_framework' )	=> 'no_stretch',
							)
						)

				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Images', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_image' ), $template );


			$c = array(

						array(
							'name'		=> __( 'Display A Border Around Images', 'avia_framework' ),
							'desc'		=> __( 'Do you want to display a light border around the images?', 'avia_framework' ),
							'id'		=> 'border',
							'type'		=> 'select',
							'std'		=> '',
							'lockable'	=> true,
							'subtype'	=> array(
												__( 'Display border', 'avia_framework' )		=> '',
												__( 'Do not display border', 'avia_framework' )	=> 'av-border-deactivate'
											),
						),

						array(
							'type'			=> 'template',
							'template_id'	=> 'border',
							'id'			=> 'img_border',
							'default_check'	=> true,
							'lockable'		=> true,
							'required'		=> array( 'border', 'equals', '' )
						),

						array(
							'type'			=> 'template',
							'template_id'	=> 'border_radius',
							'lockable'		=> true,
							'required'		=> array( 'border', 'equals', '' )
						)

				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Image Border', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_border' ), $template );

			$c = array(

						array(
							'type'			=> 'template',
							'template_id'	=> 'box_shadow',
							'default_check'	=> true,
							'lockable'		=> true,
							'required'		=> array( 'border', 'equals', '' )
						)

			);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Image Box Shadow', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_boxshadow' ), $template );

			$c = array(
						array(
							'type'			=> 'template',
							'template_id'	=> 'padding',
							'name'			=> __( 'Image Padding', 'avia_framework' ),
							'desc'			=> __( 'Set a distance between the images - needed when using box shadow outside. Both pixel and &percnt; based values are accepted. eg: 30px, 5&percnt;. Leave empty for none.', 'avia_framework' ),
							'std'			=> '',
							'required'		=> array( 'border', 'equals', '' )
						)
				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Image Padding', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_padding' ), $template );


			/**
			 * Advanced Tab
			 * ============
			 */

			$c = array(

						array(
							'type'			=> 'template',
							'template_id'	=> 'slideshow_transition',
							'desc'			=> __( 'Select the transition for your logoslider', 'avia_framework' ),
							'required'		=> array( 'type', 'equals', 'slider' ),
							'select_trans'	=> array( 'slide', 'fade' ),
							'lockable'		=> true
						),

						array(
							'type'				=> 'template',
							'template_id'		=> 'slideshow_rotation',
							'desc'				=> __( 'Select if the logo slider should rotate by default', 'avia_framework' ),
							'required'			=> array( 'type', 'equals', 'slider' ),
							'required_manual'	=> array( 'type', 'equals', 'slider' ),
							'stop_id'			=> 'autoplay_stopper',
							'lockable'			=> true
						)
				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Slider Animation', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'advanced_animation_slider' ), $template );


			$c = array(
						array(
							'type'				=> 'template',
							'template_id'		=> 'heading_tag',
							'theme_default'		=> 'h3',
							'context'			=> __CLASS__,
							'lockable'			=> true,
						)

				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Heading', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'advanced_heading' ), $template );

		}

		/**
		 * Creates the modal popup for a single entry
		 *
		 * @since 4.6.4
		 * @return array
		 */
		protected function create_modal()
		{
			$elements = array(

				array(
						'type' 	=> 'tab_container',
						'nodescription' => true
					),

				array(
						'type' 	=> 'tab',
						'name'  => __( 'Content', 'avia_framework' ),
						'nodescription' => true
					),

					array(
								'type'			=> 'template',
								'template_id'	=> $this->popup_key( 'modal_content_slidecontent' )
							),

				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),

				array(
						'type' 	=> 'tab',
						'name'  => __( 'Advanced', 'avia_framework' ),
						'nodescription' => true
					),

					array(
								'type'			=> 'template',
								'template_id'	=> $this->popup_key( 'modal_advanced_link' )
							),

				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),

				array(
						'type'			=> 'template',
						'template_id'	=> 'element_template_selection_tab',
						'args'			=> array(
												'sc'			=> $this,
												'modal_group'	=> true
											)
					),

				array(
						'type' 	=> 'tab_container_close',
						'nodescription' => true
					)

				);

			return $elements;
		}

		/**
		 * Register all templates for the modal group popup
		 *
		 * @since 4.6.4
		 */
		protected function register_modal_group_templates()
		{
			/**
			 * Content Tab
			 * ===========
			 */

			$c = array(
						array(
							'name'		=> __( 'Choose Another Image', 'avia_framework' ),
							'desc'		=> __( 'Either upload a new, or choose an existing image from your media library', 'avia_framework' ),
							'id'		=> 'id',
							'fetch'		=> 'id',
							'type'		=> 'image',
							'title'		=> __( 'Change Image', 'avia_framework' ),
							'button'	=> __( 'Change Image', 'avia_framework' ),
							'std'		=> '',
							'lockable'	=> true,
							'locked'	=> array( 'id' )
						),


						array(
							'name'		=> __( 'Image Caption', 'avia_framework' ),
							'desc'		=> __( 'Display a image caption on hover', 'avia_framework' ),
							'id'		=> 'hover',
							'type'		=> 'input',
							'std'		=> '',
							'lockable'	=> true,
						)
				);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'modal_content_slidecontent' ), $c );

			/**
			 * Advanced Tab
			 * ============
			 */

			$c = array(
						array(
							'type'			=> 'template',
							'template_id'	=> 'linkpicker_toggle',
							'name'			=> __( 'Partner/Logo Link?', 'avia_framework' ),
							'desc'			=> __( 'Where should the image/logo link to?', 'avia_framework' ),
							'subtypes'		=> array( 'no', 'lightbox', 'manually', 'single', 'taxonomy' ),
							'target_id'		=> 'link_target',
							'lockable'		=> true,
							'no_toggle'		=> true
						),

						array(
							'name'		=> __( 'Link Title', 'avia_framework' ),
							'desc'		=> __( 'Enter a link title', 'avia_framework' ),
							'id'		=> 'linktitle',
							'type'		=> 'input',
							'std'		=> '',
							'lockable'	=> true,
							'required'	=> array( 'link', 'equals', 'manually' )
						)

				);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'modal_advanced_link' ), $c );

		}

		/**
		 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
		 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
		 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
		 *
		 *
		 * @param array $params this array holds the default values for $content and $args.
		 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
		 */
		function editor_element( $params )
		{
			$default = array();
			$locked = array();
			$attr = $params['args'];
			Avia_Element_Templates()->set_locked_attributes( $attr, $this, $this->config['shortcode'], $default, $locked );

			$template = $this->update_template_lockable( 'heading', ' - <strong>{{heading}}</strong>' , $locked );
			$heading = ! empty( $attr['heading'] ) ? "- <strong>{$attr['heading']}</strong>" : '';

			$params = parent::editor_element( $params );

			$params['innerHtml'] .= "<div class='avia-element-label' {$template} data-update_element_template='yes'>{$heading}</div>";

			return $params;
		}

		/**
		 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
		 * Works in the same way as Editor Element
		 * @param array $params this array holds the default values for $content and $args.
		 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
		 */
		function editor_sub_element( $params )
		{
			$default = array();
			$locked = array();
			$attr = $params['args'];
			Avia_Element_Templates()->set_locked_attributes( $attr, $this, $this->config['shortcode_nested'][0], $default, $locked );

			$img_template = $this->update_option_lockable( array( 'id', 'img_fakeArg' ), $locked );
			$template = $this->update_option_lockable( 'hover', $locked );

			$thumbnail = isset( $attr['id'] ) ? wp_get_attachment_image( $attr['id'] ) : '';

			$params['innerHtml']  = '';
			$params['innerHtml'] .= "<div class='avia_title_container' data-update_element_template='yes'>";
			$params['innerHtml'] .=		"<span class='avia_slideshow_image' {$img_template}>{$thumbnail}</span>";
			$params['innerHtml'] .=		"<div class='avia_slideshow_content'>";
			$params['innerHtml'] .=			"<h4 class='avia_title_container_inner' {$template} >{$attr['hover']}</h4>";
			$params['innerHtml'] .=		'</div>';
			$params['innerHtml'] .= '</div>';

			return $params;
		}


		/**
		 * Returns false by default.
		 * Override in a child class if you need to change this behaviour.
		 *
		 * @since 4.2.1
		 * @param string $shortcode
		 * @return boolean
		 */
		public function is_nested_self_closing( $shortcode )
		{
			if( in_array( $shortcode, $this->config['shortcode_nested'] ) )
			{
				return true;
			}

			return false;
		}

		/**
		 *
		 * @since 4.8.8.1
		 * @param array $args
		 * @return array
		 */
		protected function get_element_styles( array $args )
		{
			$result = parent::get_element_styles( $args );

			extract( $result );

			$default = avia_partner_logo::default_args( $this->get_default_sc_args() );

			$locked = array();
			Avia_Element_Templates()->set_locked_attributes( $atts, $this, $shortcodename, $default, $locked, $content );
			Avia_Element_Templates()->add_template_class( $meta, $atts, $default );
			$meta = aviaShortcodeTemplate::set_frontend_developer_heading_tag( $atts, $meta );

			$atts = shortcode_atts( $default, $atts, $this->config['shortcode'] );

			$add = array(
						'handle'		=> $shortcodename,
						'content'		=> ShortcodeHelper::shortcode2array( $content ),
						'class'			=> '',
						'custom_markup' => '',
						'custom_el_id'	=> '',
						'heading_tag'	=> '',
						'heading_class'	=> '',
					);

			$atts = array_merge( $atts, $add );

			foreach( $atts['content'] as $key => &$item )
			{
				$item_def = $this->get_default_modal_group_args();
				Avia_Element_Templates()->set_locked_attributes( $item['attr'], $this, $this->config['shortcode_nested'][0], $item_def, $locked, $item['content'] );
			}

			unset( $item );


			if( ! isset( $this->obj_partner_logo[ $element_id ] ) )
			{
				$this->obj_partner_logo[ $element_id ] = new avia_partner_logo( $atts, $this );
			}

			$partner_logo = $this->obj_partner_logo[ $element_id ];

			$update = array(
							'class'				=> ! empty( $meta['el_class'] ) ? $meta['el_class'] : '',
							'custom_markup'		=> ! empty( $meta['custom_markup'] ) ? $meta['custom_markup'] : '',
							'el_id'				=> ! empty( $meta['custom_el_id'] ) ? $meta['custom_el_id'] : '',
							'heading_tag'		=> ! empty( $meta['heading_tag'] ) ? $meta['heading_tag'] : '',
							'heading_class'		=> ! empty( $meta['heading_class'] ) ? $meta['heading_class'] : '',
						);

			$atts = $partner_logo->update_config( $update );


			$result['default'] = $default;
			$result['atts'] = $atts;
			$result['content'] = $content;
			$result['element_styling'] = $element_styling;
			$result['meta'] = $meta;

			$result = $partner_logo->get_element_styles( $result );

			return $result;
		}

		/**
		 * Frontend Shortcode Handler
		 *
		 * @param array $atts array of attributes
		 * @param string $content text within enclosing form of shortcode element
		 * @param string $shortcodename the shortcode found, when == callback name
		 * @return string $output returns the modified html string
		 */
		function shortcode_handler( $atts, $content = '', $shortcodename = '', $meta = '' )
		{
			$result = $this->get_element_styles( compact( array( 'atts', 'content', 'shortcodename', 'meta' ) ) );

			extract( $result );

			if( 'disabled' == $atts['img_scrset'] )
			{
				Av_Responsive_Images()->force_disable( 'disabled' );
			}

			$partner_logo = $this->obj_partner_logo[ $element_id ];
			$html = $partner_logo->html();

			Av_Responsive_Images()->force_disable( 'reset' );

			return $html;
		}
	}
}


if( ! class_exists( 'avia_partner_logo' ) )
{
	class avia_partner_logo extends \aviaBuilder\base\aviaSubItemQueryBase
	{
		use \aviaBuilder\traits\scSlideshowUIControls;

		/**
		 * slider count for the current page
		 *
		 * @var int
		 */
		static protected $slider = 0;

		/**
		 * Array of subitem arrays etracted from content
		 *
		 *		'index'  => $atts
		 *
		 *
		 * @since ???
		 * @var array
		 */
		protected $subslides;

		/**
		 * attachment posts for the current slider
		 *
		 * @var array
		 */
		protected $slides;

		/**
		 * number of slides
		 *
		 * @var int
		 */
		protected $slide_count;

		/**
		 * unique array of slide id's
		 *
		 * @var array
		 */
		protected $id_array;

		/**
		 * Grid layout class string - avoid multiple init in subitem loop
		 *
		 * @since 4.8.8.1
		 * @var string
		 */
		protected $grid_class;



		/**
		 *
		 * @since 4.8.8.1						added $sc_context
		 * @param array $atts
		 * @param aviaShortcodeTemplate $sc_context
		 */
		public function __construct( array $atts, aviaShortcodeTemplate $sc_context = null )
		{
			parent::__construct( $atts, $sc_context, avia_partner_logo::default_args() );

			$this->subslides = array();
			$this->slides = array();
			$this->slide_count = 0;
			$this->id_array = array();
			$this->grid_class = null;

			/**
			 * @since 4.7.6.2
			 * @param array $this->config
			 * @return array
			 */
			$this->config = apply_filters( 'avf_partner_logo_config', $this->config );


			//if we got subslides overwrite the id array
			if( ! empty( $this->config['content'] ) )
			{
				$this->extract_subslides( $this->config['content'] );
			}

			$this->set_slides( $this->config['ids'] );
		}

		/**
		 *
		 * @since 4.2.5
		 * @added_by Günter
		 */
		public function __destruct()
		{
			unset( $this->subslides );
			unset( $this->slides );
			unset( $this->id_array );

			parent::__destruct();
		}

		/**
		 * Returns the defaults array for this class
		 *
		 * @since 4.8.8.1
		 * @return array
		 */
		static public function default_args( array $args = array() )
		{
			$default = array(
							'type'						=> 'grid',
							'navigation'				=> 'arrows',
							'control_layout'			=> 'av-control-default',
							'nav_visibility_desktop'	=> '',
							'animation'					=> 'slide',
							'autoplay'					=> 'false',
							'interval'					=> 5,
							'autoplay_stopper'			=> '',
							'manual_stopper'			=> '',
							'size'						=> 'featured',
							'ids'						=> '',
							'handle'					=> '',
							'heading'					=> '',
							'border'					=> '',
							'columns'					=> 3,
							'class'						=> '',
							'custom_markup'				=> '',
							'custom_el_id'				=> '',
							'heading_tag'				=> '',
							'heading_class'				=> '',
							'hover'						=> '',
							'css_id'					=> '',
							'img_scrset'				=> '',
							'img_size_behave'			=> '',
							'lazy_loading'				=> 'disabled',
							'content'					=> array()
						);

			$default = array_merge( $default, $args );

			/**
			 * @since 4.8.8.1
			 * @param array $default
			 * @return array
			 */
			return apply_filters( 'avf_partner_logo_defaults', $default );
		}

		/**
		 * Create custom stylings
		 *
		 * Attention: Due to paging we cannot add any backgrouund images to selectors !!!!
		 * =========
		 *
		 * @since 4.8.8.1
		 * @param array $result
		 * @return array
		 */
		public function get_element_styles( array $result )
		{
			extract( $result );

			$element_styling->create_callback_styles( $this->config );

			$classes = array(
						'avia-logo-element-container',
						$element_id,
						$this->config['border'],
						'avia-logo-' . $this->config['type'],
						'avia-content-slider',
						'avia-smallarrow-slider',
						"avia-content-{$this->config['type']}-active",
						'noHover'
					);

			$classes[] = $this->config['columns'] % 2 ? 'avia-content-slider-odd' : 'avia-content-slider-even';

			$element_styling->add_classes( 'container', $classes );
			$element_styling->add_classes( 'container', $this->config['class'] );

			$element_styling->add_responsive_classes( 'container', 'hide_element', $this->config );

			if( 'slider' == $this->config['type'] )
			{
				$ui_args = array(
							'element_id'		=> $element_id,
							'element_styling'	=> $element_styling,
							'atts'				=> $this->config,
							'autoplay_option'	=> 'true',
							'context'			=> __CLASS__,
							'slider_nav'		=> 'small'
						);

				$this->addSlideshowAttributes( $ui_args );
			}

			if( 'av-border-deactivate' != $this->config['border'] )
			{
				if( 'no_stretch' == $this->config['img_size_behave'] )
				{
					$element_styling->add_callback_styles( 'image-grid', array( 'img_border', 'border_radius', 'box_shadow' ) );
				}
				else
				{
					$element_styling->add_callback_styles( 'image-background', array( 'img_border', 'border_radius', 'box_shadow' ) );

					//	avoid space at bottom
					$element_styling->add_styles( 'image-background', array( 'background-size' => 'cover' ) );
				}

				$element_styling->add_callback_styles( 'slide', array( 'padding' ) );
			}

			$selectors = array(
						'container'			=> ".avia-logo-element-container.{$element_id}",
						'slide'				=> "#top .avia-logo-element-container.{$element_id} .slide-entry",
						'image-background'	=> ".avia-logo-element-container.{$element_id} .av-partner-fake-img",
						'image-grid'		=> "#top .avia-logo-element-container.{$element_id} img"
					);

			$element_styling->add_selectors( $selectors );

			foreach( $this->subslides as $index => $slide )
			{
				$args = array(
							'atts'			=> $slide['attr'],
							'content'		=> $slide['content'],
							'shortcodename'	=> $slide['shortcode'],
							'default'		=> $this->default_item_atts
						);

				$result_item = $this->get_element_styles_item( $args );

				$element_styling->add_subitem_styling( $result_item['element_id'], $result_item['element_styling'] );
			}

			//	save data for later HTML output
			$this->element_id = $element_id;
			$this->element_styles = $element_styling;

			$result['element_styling'] = $element_styling;

			return $result;
		}

		/**
		 * Create custom item stylings
		 * Items are called in object of main shortcode attributes
		 *
		 * @since 4.8.8.1
		 * @param array $args
		 * @return array
		 */
		public function get_element_styles_item( array $args )
		{
			$result = $this->sc_context->get_element_styles_query_item( $args );

			extract( $result );

			if( is_null( $this->grid_class ) )
			{
				switch( $this->config['columns'] )
				{
					case '1':
						$this->grid_class = 'av_fullwidth';
						break;
					case '2':
						$this->grid_class = 'av_one_half';
						break;
					case '3':
						$this->grid_class = 'av_one_third';
						break;
					case '4':
						$this->grid_class = 'av_one_fourth';
						break;
					case '5':
						$this->grid_class = 'av_one_fifth';
						break;
					case '6':
						$this->grid_class = 'av_one_sixth';
						break;
					case '7':
						$this->grid_class = 'av_one_seventh';
						break;
					case '8':
						$this->grid_class = 'av_one_eighth';
						break;
					default:
						$this->grid_class = 'av_one_third';
						break;
				}

			}


			$classes = array(
						'slide-entry',
						$element_id,
						'flex_column',
						'no_margin',
						$this->grid_class,
						'real-thumbnail',
						'post-entry',
						'slide-entry-overview'
					);

			$element_styling->add_classes( 'container', $classes );

			$selectors = array(
						'container'			=> ".avia-logo-element-container .slide-entry.{$element_id}"
					);

			$element_styling->add_selectors( $selectors );

			$result['element_styling'] = $element_styling;

			return $result;
		}

		/**
		 *
		 * @param string $ids
		 * @return void
		 */
		protected function set_slides( $ids )
		{
			if( empty( $ids ) )
			{
				return;
			}

			$ids_array = explode( ',', $ids );

			// in case only id's were given create minimal shortcode so we can add stylings
			if( empty( $this->subslides ) )
			{
				foreach( $ids_array as $id )
				{
					$this->subslides[] = array(
										'shortcode'		=> $this->sc_context['shortcode_nested'][0],
										'attr'			=> array( 'id' => $id ),
										'raw_content'	=> '',
										'content'		=> ''
									);
				}
			}

			/**
			 * Allow to translate image (e.g. WPML)
			 *
			 */
			$this->slides = get_posts( array(
										'include'		=> $ids,
										'post_status'	=> 'inherit',
										'post_type'		=> 'attachment',
										'post_mime_type' => 'image',
										'order'			=> 'ASC',
										'orderby'		=> 'post__in'
									) );


			//resort slides so the id of each slide matches the post id
			$new_slides = array();
			$new_ids = array();
			foreach( $this->slides as $slide )
			{
				$new_slides[ $slide->ID ] = $slide;
				$new_ids[] = $slide->ID;
			}

			$slideshow_data = array();
			$slideshow_data['slides'] = $new_slides;
			$slideshow_data['id_array'] = $ids_array;
			$slideshow_data['slide_count'] = count( $slideshow_data['id_array'] );

			/**
			 * @used_by				config-wpml\config.php				10
			 * @since 4.4.2
			 */
			$slideshow_data = apply_filters( 'avf_avia_builder_slideshow_filter', $slideshow_data, $this );

			$this->slides 		= $slideshow_data['slides'];
			$this->id_array 	= $slideshow_data['id_array'];
			$this->slide_count 	= $slideshow_data['slide_count'];
		}

		/**
		 * @deprecated since version 4.8.8.1
		 * @param string $size
		 */
		public function set_size( $size )
		{
			_deprecated_function( 'avia_partner_logo::set_size()', '4.8.8.1', 'Will be removed in a future version.' );

			$this->config['size'] = $size;
		}

		/**
		 * @deprecated since version 4.8.8.1
		 * @param string $class
		 */
		public function set_extra_class( $class )
		{
			_deprecated_function( 'avia_partner_logo::set_extra_class()', '4.8.8.1', 'Will be removed in a future version.' );

			$this->config['class'] .= " {$class}";
		}

		/**
		 * Create the html
		 *
		 * @since < 4.0
		 * @return string
		 */
		public function html()
		{
			avia_partner_logo::$slider++;

			if( $this->slide_count == 0 )
			{
				return '';
			}

			//	fallback - code no longer supported since 4.8.8
			if( is_null( $this->element_styles ) )
			{
				_deprecated_function( 'avia_partner_logo::html()', '4.8.8.1', 'Calling this function without post css support does not work any longer.' );

				return '';
			}

            extract( $this->config );

			$default_heading = ! empty( $heading_tag ) ? $heading_tag : 'h3';
			$args = array(
						'heading'		=> $default_heading,
						'extra_class'	=> $heading_class
					);

			$extra_args = array( $this );

			/**
			 * @since 4.5.5
			 * @return array
			 */
			$args = apply_filters( 'avf_customize_heading_settings', $args, __CLASS__, $extra_args );

			$heading_tag = ! empty( $args['heading'] ) ? $args['heading'] : $default_heading;
			$css = ! empty( $args['extra_class'] ) ? $args['extra_class'] : $heading_class;

			$extraClass = 'first';
			$slide_loop_count = 1;
			$loop_counter = 1;
			$heading = ! empty( $heading ) ? "<{$heading_tag} class='av-logo-special-heading-text {$css}'>{$heading}</{$heading_tag}>" : '&nbsp;';

			$style_tag = $this->element_styles->get_style_tag( $this->element_id );
			$container_class = $this->element_styles->get_class_string( 'container' );
			$data_slideshow_options = $this->element_styles->get_data_attributes_json_string( 'container', 'slideshow-options' );

			$output  = '';
			$output .= $style_tag;
			$output .= "<div {$custom_el_id} class='{$container_class} avia-content-slider" . avia_partner_logo::$slider . "' {$data_slideshow_options}>";

			$heading_class = '';
			if( $heading == '&nbsp;')
			{
				$heading_class .= ' no-logo-slider-heading ';
			}

			$output .= "<div class='avia-smallarrow-slider-heading {$heading_class}'>";

			if( $heading != '&nbsp;' || $navigation != 'no' )
			{
				$output .= "<div class='new-special-heading'>{$heading}</div>";
			}

			if( count( $this->id_array ) > $columns && $type == 'slider' && $navigation != 'no' )
			{
				//	we need this for swipe event - hidden with css
				$output .= $this->slide_navigation_arrows();
			}

			$output .= '</div>';


			$output .= '<div class="avia-content-slider-inner">';

			$ratio_style = '';

			foreach( $this->subslides as $key => $slides )
			{
				$id = isset( $slides['attr']['id'] ) ? $slides['attr']['id'] : 0;

				$img = '';
				$link = '';

				if( isset( $this->slides[ $id ] ) )
				{
					$slide = $this->slides[ $id ];
					$img_src = wp_get_attachment_image_src( $slide->ID, $size );

					if( empty( $ratio_style ) )
					{
						$width = intval( $img_src[1] );
						$height = intval( $img_src[2] );

						/**
						 * if first image is svg ( $width = 0 ) we check for selected image size or make a fallback to WP medium 300 * 300 if no scaling
						 *
						 * @since 4.8.6.3
						 */
						if( 0 === $width )
						{
							global $_wp_additional_image_sizes;

							if( 'large' == $size )
							{
								$width = get_option( 'large_size_w' );
								$height = get_option( 'large_size_h' );
							}
							else if( 'no scaling' == $size || ! isset( $_wp_additional_image_sizes[ $size ] ) )
							{
								$width = get_option( 'medium_size_w' );
								$height = get_option( 'medium_size_h' );
							}
							else
							{
								$width = $_wp_additional_image_sizes[ $size ]['width'];
								$height = $_wp_additional_image_sizes[ $size ]['height'];
							}
						}

						$ratio  = ( 100 / $width ) * $height;
						$ratio_style = "padding-bottom:{$ratio}%;";
					}

					$meta = array_merge( array( 'link' => '', 'link_target' => '', 'linktitle' => '', 'hover' => '', 'custom_markup' => '' ), $slides['attr'] );
					extract( $meta );

					$style = "style='{$ratio_style} background-image:url({$img_src[0]});'";
					$img = "<span class='av-partner-fake-img' {$style}></span>";

					if( $img_size_behave == 'no_stretch' )
					{
					   $img = wp_get_attachment_image( $slide->ID, $size );
					   $img = Av_Responsive_Images()->make_image_responsive( $img, $slide->ID, $this->config['lazy_loading'] );
					}

					$link = AviaHelper::get_url( $link, $slide->ID, true );
					$blank = AviaHelper::get_link_target( $link_target );
				}

				$parity = $loop_counter % 2 ? 'odd' : 'even';
				$last = $this->slide_count == $slide_loop_count ? 'post-entry-last' : '';
				$post_class = "slide-loop-{$slide_loop_count} slide-parity-{$parity} {$last}";
				$single_data = empty( $hover ) ? '' : 'data-avia-tooltip="' . $hover . '"';

				if( $loop_counter == 1 )
				{
					$output .= '<div class="slide-entry-wrap">';
				}

				if( ! empty( $link ) )
				{
					$lightbox_attr = Av_Responsive_Images()->html_attr_image_src( $link, false );
					$imgage = "<a {$lightbox_attr} data-rel='slide-" . avia_partner_logo::$slider . "' class='slide-image' title='{$linktitle}' {$blank}>{$img}</a>";
				}
				else
				{
					$imgage = $img;
				}


				//	add item container data
				$item_info = $this->element_styles->get_subitem_styling_info( $slide_loop_count - 1 );
				$container_class = $item_info['element_styling']->get_class_string( 'container' );

				$output .= "<div {$single_data} class='{$container_class} {$post_class} {$extraClass}'>";
				$output .=		$imgage;
				$output .= '</div>';

				$loop_counter ++;
				$slide_loop_count ++;
				$extraClass = '';

				if( $loop_counter > $columns )
				{
					$loop_counter = 1;
					$extraClass = 'first';
				}

				if( $loop_counter == 1 || ! empty( $last ) )
				{
					$output .= '</div>';
				}
			}

			$output .= '</div>';


			if( count( $this->id_array ) > $columns && $type == 'slider' && $navigation != 'no' )
			{
				if( $navigation == 'dots' )
				{
					$output .= $this->slide_navigation_dots();
				}
			}

			$output .= '</div>';

			return $output;
		}

		/**
		 * Create arrows to scroll slides
		 *
		 * @since 4.8.3			reroute to aviaFrontTemplates
		 * @return string
		 */
		protected function slide_navigation_arrows()
		{
			$args = array(
						'context'		=> get_class(),
						'params'		=> $this->config
					);

			return aviaFrontTemplates::slide_navigation_arrows( $args );
		}

		/**
		 *
		 * @return string
		 */
		protected function slide_navigation_dots()
		{
			$args = array(
						'total_entries'		=> $this->slide_count,
						'container_entries'	=> $this->config['columns'],
						'context'			=> get_class(),
						'params'			=> $this->config
					);


			return aviaFrontTemplates::slide_navigation_dots( $args );
		}

		/**
		 *
		 * @param array $slide_array
		 */
		protected function extract_subslides( $slide_array )
		{
			$this->config['ids'] = array();
			$this->subslides = array();

			foreach( $slide_array as $index => $slide )
			{
				$this->subslides[ $index ] = $slide;
				$this->config['ids'][] = $slide['attr']['id'];
			}

			$this->config['ids'] = implode( ',', $this->config['ids'] );

			unset( $this->config['content'] );
		}
	}
}
