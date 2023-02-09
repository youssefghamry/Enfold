<?php
/**
 * Testimonials
 *
 * Creates a Testimonial Grid
 */
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( ! class_exists( 'avia_sc_testimonial' ) )
{
	class avia_sc_testimonial extends aviaShortcodeTemplate
	{
		use \aviaBuilder\traits\scSlideshowUIControls;

		/**
		 * @since < 4.0
		 * @var int
		 */
		static protected $rows = 0;

		/**
		 * @since < 4.0
		 * @var int
		 */
		static protected $counter = 0;

		/**
		 * Used to store last counter value after adding element styles
		 *
		 * @since 4.8.4
		 * @var int
		 */
		static protected $counter_prev = 0;

		/**
		 * flag if we need to close container
		 *
		 * @since 4.8.8
		 * @var boolean
		 */
		static protected $section_printed = false;

		/**
		 * @since < 4.0
		 * @var int
		 */
		static protected $columns = 0;

		/**
		 * @since < 4.0
		 * @var string
		 */
		static protected $style = '';

		/**
		 * @since < 4.0
		 * @var string
		 */
		static protected $grid_style = '';

		/**
		 *
		 * @since 4.8.8
		 * @var array
		 */
		protected $in_sc_exec;

		/**
		 *
		 * @since 4.5.5
		 * @param AviaBuilder $builder
		 */
		public function __construct( $builder )
		{
			$this->in_sc_exec = false;

			parent::__construct( $builder );
		}

		/**
		 * @since 4.5.5
		 */
		public function __destruct()
		{
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

			$this->config['name']			= __( 'Testimonials', 'avia_framework' );
			$this->config['tab']			= __( 'Content Elements', 'avia_framework' );
			$this->config['icon']			= AviaBuilder::$path['imagesURL'] . 'sc-testimonials.png';
			$this->config['order']			= 20;
			$this->config['target']			= 'avia-target-insert';
			$this->config['shortcode']		= 'av_testimonials';
			$this->config['shortcode_nested'] = array( 'av_testimonial_single' );
			$this->config['tooltip']		= __( 'Creates a Testimonial Grid', 'avia_framework' );
			$this->config['preview']		= 'xlarge';
			$this->config['disabling_allowed'] = true;
			$this->config['id_name']		= 'id';
			$this->config['id_show']		= 'yes';
			$this->config['alb_desc_id']	= 'alb_description';
			$this->config['name_item']		= __( 'Testimonial Item', 'avia_framework' );
			$this->config['tooltip_item']	= __( 'A Testimonial Element Item', 'avia_framework' );
		}

		function extra_assets()
		{
			//load css
			wp_enqueue_style( 'avia-module-slideshow', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/slideshow/slideshow.css', array( 'avia-layout' ), false );
			wp_enqueue_style( 'avia-module-testimonials', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/testimonials/testimonials.css', array( 'avia-layout' ), false );

			wp_enqueue_script( 'avia-module-slideshow', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/slideshow/slideshow.js', array( 'avia-shortcodes' ), false, true );
			wp_enqueue_script( 'avia-module-testimonials', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/testimonials/testimonials.js', array( 'avia-shortcodes' ), false, true );
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
							'template_id'	=> $this->popup_key( 'content_testemonial' )
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
													$this->popup_key( 'styling_fonts' ),
													$this->popup_key( 'styling_colors' )
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
							'name'			=> __( 'Add/Edit Testimonial', 'avia_framework' ),
							'desc'			=> __( 'Here you can add, remove and edit your Testimonials.', 'avia_framework' ),
							'type'			=> 'modal_group',
							'id'			=> 'content',
							'modal_title'	=> __( 'Edit Testimonial', 'avia_framework' ),
							'editable_item'	=> true,
							'lockable'		=> true,
							'tmpl_set_default'	=> false,
							'std'			=> array(
													array(
														'name'		=> __( 'Name', 'avia_framework' ),
														'Subtitle'	=> '',
														'check'		=> 'is_empty'
													),
												),
							'subelements'	=> $this->create_modal()
						)

				);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'content_testemonial' ), $c );

			/**
			 * Styling Tab
			 * ===========
			 */

			$c = array(
						array(
							'name'		=> __( 'Testimonial Style', 'avia_framework' ),
							'desc'		=> __( 'Here you can select how to display the testimonials. You can either create a testimonial slider or a testimonial grid with multiple columns', 'avia_framework' ),
							'id'		=> 'style',
							'type'		=> 'select',
							'std'		=> 'grid',
							'lockable'	=> true,
							'subtype'	=> array(
												__( 'Testimonial Grid', 'avia_framework' )				=> 'grid',
												__( 'Testimonial Slider (Compact)', 'avia_framework' )	=> 'slider',
												__( 'Testimonial Slider (Large)', 'avia_framework' )	=> 'slider_large',
							)
						),

						array(
							'name'		=> __( 'Testimonial Grid Columns', 'avia_framework' ),
							'desc'		=> __( 'How many columns do you want to display', 'avia_framework' ),
							'id'		=> 'columns',
							'type'		=> 'select',
							'std'		=> '2',
							'lockable'	=> true,
							'required' 	=> array( 'style', 'equals', 'grid' ),
							'subtype'	=> AviaHtmlHelper::number_array( 1, 4, 1 )
						),

						array(
							'name'		=> __( 'Testimonial Grid Style', 'avia_framework' ),
							'desc'		=> __( 'Set the styling for the testimonial grid', 'avia_framework' ),
							'id'		=> 'grid_style',
							'type'		=> 'select',
							'std'		=> '',
							'lockable'	=> true,
							'required' 	=> array( 'style', 'equals', 'grid' ),
							'subtype'	=> array(
												__( 'Default Grid', 'avia_framework' )	=> '',
												__( 'Minimal Grid', 'avia_framework' )	=> 'av-minimal-grid-style',
												__( 'Boxed Grid', 'avia_framework' )	=> 'av-minimal-grid-style av-boxed-grid-style',
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
							'template_id'	=> 'slideshow_controls',
							'include'		=> array( 'arrows' ),
							'lockable'		=> true,
							'required'		=> array( 'style', 'contains', 'slider' )
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
							'include'		=> array( 'arrows' ),
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
							'name'			=> __( 'Testimonial Name Font Sizes', 'avia_framework' ),
							'desc'			=> __( 'Select a custom font size for the testimonial name.', 'avia_framework' ),
							'type'			=> 'template',
							'template_id'	=> 'font_sizes_icon_switcher',
							'lockable'		=> true,
							'textfield'		=> true,
							'subtype'		=> array(
												'default'	=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '' ), 'px' ),
												'desktop'	=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '' ), 'px' ),
												'medium'	=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' ),
												'small'		=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' ),
												'mini'		=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' )
											),
							'id_sizes'		=> array(
												'default'	=> 'size-name',
												'desktop'	=> 'av-desktop-font-size-name',
												'medium'	=> 'av-medium-font-size-name',
												'small'		=> 'av-small-font-size-name',
												'mini'		=> 'av-mini-font-size-name'
											)
						),

						array(
							'name'			=> __( 'Testimonial Subtitle Font Sizes', 'avia_framework' ),
							'desc'			=> __( 'Select a custom font size for the testimonial subtitle.', 'avia_framework' ),
							'type'			=> 'template',
							'template_id'	=> 'font_sizes_icon_switcher',
							'lockable'		=> true,
							'textfield'		=> true,
							'subtype'		=> array(
												'default'	=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '' ), 'px' ),
												'desktop'	=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '' ), 'px' ),
												'medium'	=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' ),
												'small'		=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' ),
												'mini'		=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' )
											),
							'id_sizes'		=> array(
												'default'	=> 'size-subtitle',
												'desktop'	=> 'av-desktop-font-size-subtitle',
												'medium'	=> 'av-medium-font-size-subtitle',
												'small'		=> 'av-small-font-size-subtitle',
												'mini'		=> 'av-mini-font-size-subtitle'
											)
						),

						array(
							'name'			=> __( 'Testimonial Content Font Sizes', 'avia_framework' ),
							'desc'			=> __( 'Select a custom font size for the testimonial content.', 'avia_framework' ),
							'type'			=> 'template',
							'template_id'	=> 'font_sizes_icon_switcher',
							'lockable'		=> true,
							'textfield'		=> true,
							'subtype'		=> array(
												'default'	=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '' ), 'px' ),
												'desktop'	=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '' ), 'px' ),
												'medium'	=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' ),
												'small'		=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' ),
												'mini'		=> AviaHtmlHelper::number_array( 10, 50, 1, array( __( 'Use Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' )
											),
							'id_sizes'		=> array(
												'default'	=> 'size-content',
												'desktop'	=> 'av-desktop-font-size-content',
												'medium'	=> 'av-medium-font-size-content',
												'small'		=> 'av-small-font-size-content',
												'mini'		=> 'av-mini-font-size-content'
											)
						)

				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Font Sizes', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_fonts' ), $template );

			$c = array(
						array(
							'name'		=> __( 'Font Colors', 'avia_framework' ),
							'desc'		=> __( 'Either use the themes default colors or apply some custom ones', 'avia_framework' ),
							'id'		=> 'font_color',
							'type'		=> 'select',
							'std'		=> '',
							'lockable'	=> true,
							'subtype'	=> array(
												__( 'Default', 'avia_framework' )				=> '',
												__( 'Define Custom Colors', 'avia_framework' )	=> 'custom'
											),
						),

						array(
							'name'		=> __( 'Name Font Color', 'avia_framework' ),
							'desc'		=> __( 'Select a custom font color. Leave empty to use the default', 'avia_framework' ),
							'id'		=> 'custom_title',
							'type'		=> 'colorpicker',
							'std'		=> '',
							'rgba'		=> true,
							'container_class' => 'av_half av_half_first',
							'lockable'	=> true,
							'required'	=> array( 'font_color', 'equals', 'custom' )
						),

						array(
							'name'		=> __( 'Subtitle Font Color', 'avia_framework' ),
							'desc'		=> __( 'Select a custom font color. Leave empty to use &quot;Name Font Color&quot;', 'avia_framework' ),
							'id'		=> 'custom_sub',
							'type'		=> 'colorpicker',
							'std'		=> '',
							'rgba'		=> true,
							'container_class' => 'av_half',
							'lockable'	=> true,
							'required'	=> array( 'font_color', 'equals', 'custom' )
						),

						array(
							'name'		=> __( 'Content Font Color', 'avia_framework' ),
							'desc'		=> __( 'Select a custom font color. Leave empty to use the default', 'avia_framework' ),
							'id'		=> 'custom_content',
							'type'		=> 'colorpicker',
							'std'		=> '',
							'rgba'		=> true,
							'container_class' => 'av_half av_half_first',
							'lockable'	=> true,
							'required'	=> array( 'font_color', 'equals', 'custom' )
						)
				);

			$template = array(
							array(
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Colors', 'avia_framework' ),
								'content'		=> $c
							),
					);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_colors' ), $template );

			/**
			 * Advanced Tab
			 * ===========
			 */

			$c = array(

						array(
							'type'			=> 'template',
							'template_id'	=> 'slideshow_transition',
							'desc'			=> __( 'Select the transition for your testimonials', 'avia_framework' ),
							'required'		=> array( 'style', 'contains', 'slider' ),
							'select_trans'	=> array( 'fade', 'slide' ),
							'std_trans'		=> 'fade',
							'lockable'		=> true
						),

						array(
							'type'				=> 'template',
							'template_id'		=> 'slideshow_rotation',
							'desc'				=> __( 'Select if the testimonials slider should rotate by default', 'avia_framework' ),
							'std'				=> 'true',
							'required'			=> array( 'style', 'contains', 'slider' ),
							'required_manual'	=> array( 'style', 'contains', 'slider' ),
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
							'template_id'	=> $this->popup_key( 'modal_content_tstemonial' )
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
							'name'		=> __( 'Image', 'avia_framework' ),
							'desc'		=> __( 'Either upload a new, or choose an existing image from your media library', 'avia_framework' ),
							'id'		=> 'src',
							'type'		=> 'image',
							'fetch'		=> 'id',
							'title'		=> __( 'Insert Image', 'avia_framework' ),
							'button'	=> __( 'Insert', 'avia_framework' ),
							'std'		=> '',
							'lockable'	=> true
						),

						array(
							'name'		=> __( 'Name', 'avia_framework' ),
							'desc'		=> __( 'Enter the Name of the Person to quote', 'avia_framework' ),
							'id'		=> 'name',
							'type'		=> 'input',
							'std'		=> '',
							'lockable'	=> true
						),

						array(
							'name'		=> __( 'Subtitle below name', 'avia_framework' ),
							'desc'		=> __( 'Can be used for a job description', 'avia_framework' ),
							'id'		=> 'subtitle',
							'type'		=> 'input',
							'std'		=> '',
							'lockable'	=> true
						),

						array(
							'name'		=> __( 'Quote', 'avia_framework' ),
							'desc'		=> __( 'Enter the testimonial here', 'avia_framework' ),
							'id'		=> 'content',
							'type'		=> 'tiny_mce',
							'std'		=> '',
							'lockable'	=> true
						),

				);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'modal_content_tstemonial' ), $c );

			$c = array(
						array(
							'name'		=> __( 'Website Link', 'avia_framework' ),
							'desc'		=> __( 'Link to the Persons website', 'avia_framework' ),
							'id'		=> 'link',
							'type'		=> 'input',
							'std'		=> 'http://',
							'lockable'	=> true
						),

						array(
							'name'		=> __( 'Website Name', 'avia_framework' ),
							'desc'		=> __( 'Linktext for the above Link', 'avia_framework' ),
							'id'		=> 'linktext',
							'type'		=> 'input',
							'std'		=> '',
							'lockable'	=> true
						)
				);

			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'modal_advanced_link' ), $c );

		}

		/**
		 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
		 * Works in the same way as Editor Element
		 *
		 * @param array $params this array holds the default values for $content and $args.
		 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
		 */
		function editor_sub_element( $params )
		{
			$default = array();
			$locked = array();
			$attr = $params['args'];
			Avia_Element_Templates()->set_locked_attributes( $attr, $this, $this->config['shortcode_nested'][0], $default, $locked );

			$template = $this->update_template_lockable( 'name', __( 'Testimonial by', 'avia_framework' ) . ': {{name}}', $locked );

			$params['innerHtml']  = '';
			$params['innerHtml'] .= "<div class='avia_title_container' {$template} data-update_element_template='yes'>" . __( 'Testimonial by', 'avia_framework' ) . ": {$attr['name']}</div>";

			return $params;
		}

		/**
		 * Create custom stylings
		 *
		 * @since 4.8.4
		 * @param array $args
		 * @return array
		 */
		protected function get_element_styles( array $args )
		{
			$result = parent::get_element_styles( $args );

			extract( $result );

			$default = array(
						'style'						=> 'grid',
						'columns'					=> '2',
						'grid_style'				=> '',
						'navigation'				=> 'arrows',
						'control_layout'			=> 'av-control-default',
						'nav_visibility_desktop'	=> '',
						'animation'					=> 'fade',
						'autoplay'					=> 'true',
						'interval'					=> 5,
						'autoplay_stopper'			=> '',
						'manual_stopper'			=> '',
						'hoverpause'				=> true,		//	hardcoded for slider
						'font_color'				=> '',
						'custom_title'				=> '',
						'custom_sub'				=> '',
						'custom_content'			=> ''
					);

			$default = $this->sync_sc_defaults_array( $default, 'no_modal_item', 'no_content' );


			$locked = array();
			Avia_Element_Templates()->set_locked_attributes( $atts, $this, $shortcodename, $default, $locked, $content );
			Avia_Element_Templates()->add_template_class( $meta, $atts, $default );


			$this->in_sc_exec = true;

			$atts = shortcode_atts( $default, $atts, $this->config['shortcode'] );


			avia_sc_testimonial::$counter = 1;
			avia_sc_testimonial::$counter_prev = 1;
			avia_sc_testimonial::$section_printed = false;
			avia_sc_testimonial::$rows = 1;
			avia_sc_testimonial::$columns = $atts['columns'];
			avia_sc_testimonial::$style = $atts['style'];
			avia_sc_testimonial::$grid_style = $atts['grid_style'];

			//if we got a slider we only need a single row wrapper
			if( $atts['style'] != 'grid' )
			{
				avia_sc_testimonial::$columns = 100000;
			}


			$cls_style = $atts['style'] == 'slider_large' ? 'slider' : $atts['style'];

			$classes = array(
						'avia-testimonial-wrapper',
						$element_id,
						"avia-{$cls_style}-testimonials",
						"avia-{$cls_style}-{$atts['columns']}-testimonials",
						'avia_animate_when_almost_visible'
					);

			$element_styling->add_classes( 'container', $classes );
			$element_styling->add_classes_from_array( 'container', $meta, 'custom_class' );
			$element_styling->add_responsive_classes( 'container', 'hide_element', $atts );


			if( $atts['style'] == 'grid' )
			{
				$element_styling->add_classes( 'container', $atts['grid_style'] );
			}
			else if( $atts['style'] == 'slider_large' )
			{
				$element_styling->add_classes( 'container', 'av-large-testimonial-slider' );
			}

			if( false !== strpos( $atts['style'], 'slider' ) )
			{
				$ui_args = array(
							'element_id'		=> $element_id,
							'element_styling'	=> $element_styling,
							'atts'				=> $atts,
							'autoplay_option'	=> 'true',
							'context'			=> __CLASS__,
						);

				$this->addSlideshowAttributes( $ui_args );
			}

			$element_styling->add_responsive_font_sizes( 'item-name', 'size-name', $atts, $this );
			$element_styling->add_responsive_font_sizes( 'item-subtitle', 'size-subtitle', $atts, $this );
			$element_styling->add_responsive_font_sizes( 'item-sub-sep', 'size-subtitle', $atts, $this );
			$element_styling->add_responsive_font_sizes( 'item-link', 'size-subtitle', $atts, $this );
			$element_styling->add_responsive_font_sizes( 'item-content', 'size-content', $atts, $this );



			if( $atts['font_color'] == 'custom' )
			{
				if( ! empty( $atts['custom_title'] ) )
				{
					$element_styling->add_styles( 'item-name', array( 'color' => $atts['custom_title'] ) );

					$element_styling->add_classes( 'item-subtitle', 'av_opacity_variation' );
				}

				$color = empty( $atts['custom_sub'] ) ? $atts['custom_title'] : $atts['custom_sub'];
				if( ! empty( $color ) )
				{
					$element_styling->add_styles( 'item-subtitle', array( 'color' => $color ) );
					$element_styling->add_styles( 'item-link', array( 'color' => $color ) );
				}

				if( ! empty( $atts['custom_content'] ) )
				{
					$element_styling->add_styles( 'item-content', array( 'color' => $atts['custom_content'] ) );
					$element_styling->add_styles( 'controls', array( 'color' => $atts['custom_content'] ) );

					$element_styling->add_classes( 'item-content', 'av_inherit_color' );
				}
			}

			$element_styling->add_classes( 'item', array( 'flex_column', 'no_margin' ) );
			switch( $atts['columns'] )
			{
				case 1:
					$element_styling->add_classes( 'item', array( 'av_one_full' ) );
					break;
				case 2:
					$element_styling->add_classes( 'item', array( 'av_one_half' ) );
					break;
				case 3:
					$element_styling->add_classes( 'item', array( 'av_one_third' ) );
					break;
				case 4:
					$element_styling->add_classes( 'item', array( 'av_one_fourth' ) );
					break;
			}


			$selectors = array(
						'container'		=> ".avia-testimonial-wrapper.{$element_id}",
						'item'			=> ".avia-testimonial-wrapper.{$element_id} .avia-testimonial",
						'item-content'	=> "#top .avia-testimonial-wrapper.{$element_id} .avia-testimonial-content",
						'item-name'		=> ".avia-testimonial-wrapper.{$element_id} .avia-testimonial-name",
						'item-subtitle'	=> ".avia-testimonial-wrapper.{$element_id} .avia-testimonial-subtitle",
						'item-sub-sep'	=> ".avia-testimonial-wrapper.{$element_id} .avia-testimonial-subtitle-sep",
						'item-link'		=> ".avia-testimonial-wrapper.{$element_id} .avia-testimonial-link",
						'controls'		=> ".avia-testimonial-wrapper.{$element_id} .avia-slideshow-controls"
					);

			$element_styling->add_selectors( $selectors );

			$result['default'] = $default;
			$result['atts'] = $atts;
			$result['content'] = $content;
			$result['element_styling'] = $element_styling;

			$this->el_styling = $element_styling;

			return $result;
		}

		/**
		 * Create custom stylings for items
		 *
		 * @since 4.8.4
		 * @param array $args
		 * @return array
		 */
		protected function get_element_styles_item( array $args )
		{
			$result = parent::get_element_styles_item( $args );

			/**
			 * Fixes a problem when 3-rd party plugins call nested shortcodes without executing main shortcode  (like YOAST in wpseo-filter-shortcodes)
			 */
			if( ! $this->in_sc_exec )
			{
				return $result;
			}

			extract( $result );

			$default = array(
						'src'			=> '',
						'name'			=> '',
						'subtitle'		=> '',
						'link'			=> '',
						'linktext'		=> '',
						'custom_markup'	=> ''
					);

			$default = $this->sync_sc_defaults_array( $default, 'modal_item', 'no_content' );

			$locked = array();
			Avia_Element_Templates()->set_locked_attributes( $atts, $this, $shortcodename, $default, $locked, $content );

			$atts = shortcode_atts( $default, $atts, $this->config['shortcode_nested'][0] );


			$classes = array(
						'avia-testimonial',
						$element_id
					);

			$element_styling->add_classes( 'container', $classes );

			/**
			 * To support responsive images we moved avatar image from background to <img> tag
			 * The following commented lines are kept in case user like a fallback to background image - can be removed in future versions if no requests
			 *
			 * @since 4.8.4
			 */

			$element_styling->add_classes( 'container', 'avia-testimonial-row-' . avia_sc_testimonial::$rows );

			//if(count($testimonials) <= $rows * $columns) $class.= ' avia-testimonial-row-last ';
			if( avia_sc_testimonial::$counter == 1 )
			{
				$element_styling->add_classes( 'container', 'avia-first-testimonial' );
			}
			else if( avia_sc_testimonial::$counter == avia_sc_testimonial::$columns )
			{
				$element_styling->add_classes( 'container', 'avia-last-testimonial' );
			}

			/**
			 * avatar size filter
			 *
			 * @param string $size
			 * @param int|string $src		attachment id
			 * @param string $extra_class
			 * @return string
			 */
//			$avatar_size = apply_filters( 'avf_testimonials_avatar_size', 'square', $atts['src'], $extra_class );
//
//			//	has to be kept inline because of image
//			$bg = wp_get_attachment_image_src( $atts['src'], $avatar_size );
//			if( ! empty( $bg[0] ) )
//			{
//				$element_styling->add_styles( 'image', array( 'background-image' => $bg[0] ) );
//			}


			$selectors = array(
						'container'	=> ".avia-testimonial.{$element_id}",
						'image'		=> ".avia-testimonial.{$element_id} .avia-testimonial-image"
					);

			$element_styling->add_selectors( $selectors );

			$result['default'] = $default;
			$result['atts'] = $atts;
			$result['content'] = $content;
			$result['element_styling'] = $element_styling;

			//	save for correct HTML markup
			avia_sc_testimonial::$counter_prev = avia_sc_testimonial::$counter;
			avia_sc_testimonial::$counter ++;

			if( avia_sc_testimonial::$counter > avia_sc_testimonial::$columns )
			{
				avia_sc_testimonial::$counter = 1;
				avia_sc_testimonial::$rows ++;
			}

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
			extract( $atts );

			/**
			 * Prior compact slider had no controls - this changed with this version.
			 * Controls only shown on hover in 'slider_large'.
			 *
			 * @since 5.0		also allow controls for compact slider
			 */
			$controls = false !== strpos( $atts['style'], 'slider' );


			$style_tag = $element_styling->get_style_tag( $element_id );
			$container_class = $element_styling->get_class_string( 'container' );
			$data_slideshow_options = $element_styling->get_data_attributes_json_string( 'container', 'slideshow-options' );

			$output  = '';
			$output .= $style_tag;

			$output .= "<div {$meta['custom_el_id']} class='{$container_class}' {$data_slideshow_options}>";
			$output .=		ShortcodeHelper::avia_remove_autop( $content, true );

			//close unclosed wrapper containers
			if( ! avia_sc_testimonial::$section_printed )
			{
				$output .=	'</section>';
			}

			if( $controls )
			{
				$output .= $this->slide_navigation_arrows( $atts );
			}

			$output .= '</div>';

			$this->in_sc_exec = false;

			return $output;
		}

		/**
		 * Create arrows to scroll slides
		 *
		 * @since 4.8.3			reroute to aviaFrontTemplates
		 * @param array $atts
		 * @return string
		 */
		protected function slide_navigation_arrows( array $atts )
		{
			$args = array(
						'context'			=> get_class(),
						'params'			=> $atts
					);

			return aviaFrontTemplates::slide_navigation_arrows( $args );
		}


		/**
		 * Shortcode handler
		 *
		 * @param array $atts
		 * @param string $content
		 * @param string $shortcodename
		 * @return string
		 */
		public function av_testimonial_single( $atts, $content = '', $shortcodename = '' )
		{
			/**
			 * Fixes a problem when 3-rd party plugins call nested shortcodes without executing main shortcode  (like YOAST in wpseo-filter-shortcodes)
			 */
			if( ! $this->in_sc_exec )
			{
				return '';
			}

			$result = $this->get_element_styles_item( compact( array( 'atts', 'content', 'shortcodename' ) ) );

			extract( $result );
			extract( $atts );

			$avatar = '';
			$is_grid = avia_sc_testimonial::$style == 'grid';
			$grid_style = $is_grid ? avia_sc_testimonial::$grid_style : '';

			if( $link && ! $linktext )
			{
				$linktext = $link;
			}

			if( $link == 'http://' || $link == 'https://' )
			{
				$link = '';
			}

			$linktext = htmlentities( $linktext );

			$markup_avatar = avia_markup_helper( array( 'context' => 'single_image', 'echo' => false, 'custom_markup' => $custom_markup ) );
			$markup_person = avia_markup_helper( array( 'context' => 'person','echo' => false, 'custom_markup'=> $custom_markup ) );
			$markup_author = avia_markup_helper( array( 'context' => 'author', 'echo' => false, 'custom_markup' => $custom_markup ) );
			$markup_text = avia_markup_helper( array( 'context' => 'entry', 'echo' => false, 'custom_markup' => $custom_markup ) );
			$markup_content = avia_markup_helper( array( 'context' => 'entry_content', 'echo' => false, 'custom_markup' => $custom_markup ) );
			$markup_name = avia_markup_helper( array( 'context' => 'name', 'echo' => false, 'custom_markup' => $custom_markup ) );
			$markup_job = avia_markup_helper( array( 'context' => 'job', 'echo' => false, 'custom_markup' => $custom_markup ) );

			if( strpos( $link, '@') >= 1 )
			{
				$markup_url = avia_markup_helper( array( 'context' => 'email', 'echo' => false, 'custom_markup' => $custom_markup ) );
			}
			else
			{
				$markup_url = avia_markup_helper( array( 'context' => 'url', 'echo' => false, 'custom_markup' => $custom_markup ) );
			}


			$style_tag = $element_styling->get_style_tag( $element_id );
			$container_class = $element_styling->get_class_string( 'container' );

			//	classes set in main element
			$content_class = $this->el_styling->get_class_string( 'item-content' );
			$subtitle_class = $this->el_styling->get_class_string( 'item-subtitle' );
			$item_class = $this->el_styling->get_class_string( 'item' );

			//final output
			if( $src )
			{
				/**
				 * Avatar size filter
				 *
				 * @param string $size
				 * @param int|string $src		attachment id
				 * @param string $container_class
				 * @return string
				 */
				$avatar_size = apply_filters( 'avf_testimonials_avatar_size', 'square', $src, $container_class );
				$img = wp_get_attachment_image( $src, $avatar_size );

				$avatar = "<div class='avia-testimonial-image' {$markup_avatar}>{$img}</div>";
			}

			$output = '';
			$output .= $style_tag;

			if( avia_sc_testimonial::$counter_prev == 1 )
			{
				$output .= '<section class="avia-testimonial-row">';
			}

			$output .= "<div class='{$container_class} {$item_class}'>";
			$output .=		"<div class='avia-testimonial_inner' {$markup_text}>";

			if( $is_grid && $grid_style == '' )
			{
				$output .=		$avatar;
			}

			$output .=			"<div class='avia-testimonial-content {$content_class}'>";
			$output .=				"<div class='avia-testimonial-markup-entry-content' {$markup_content}>";
			$output .=					ShortcodeHelper::avia_apply_autop( ShortcodeHelper::avia_remove_autop( $content ) );
			$output .=				'</div>';
			$output .=			'</div>';
			$output .=			'<div class="avia-testimonial-meta">';
			$output .=				'<div class="avia-testimonial-arrow-wrap">';
			$output .=					'<div class="avia-arrow"></div>';
			$output .=				'</div>';

			if( ! $is_grid || ( $is_grid && $grid_style != '' ) )
			{
				$output .=			$avatar;
			}

			$output .= 				"<div class='avia-testimonial-meta-mini' {$markup_author}>";

			if( $name )
			{
				$output .= 				"<strong  class='avia-testimonial-name' {$markup_name}>{$name}</strong>";
			}

			if( $subtitle )
			{
				$output .= 				"<span  class='avia-testimonial-subtitle {$subtitle_class}' {$markup_job}>{$subtitle}</span>";
			}

			if( $link )
			{
				$output .= 				"<span class='hidden avia-testimonial-markup-link' {$markup_url}>{$link}</span>";
			}

			if( $link && $subtitle )
			{
				$output .= 				'<span class="avia-testimonial-subtitle-sep">&ndash;</span>';
			}

			if( $link )
			{
				$output .= 				"<a class='aviablank avia-testimonial-link' href='{$link}' rel=’noopener noreferrer’>{$linktext}</a>";
			}

			$output .= 				'</div>';
			$output .= 			'</div>';
			$output .=		'</div>';
			$output .= '</div>';

			if( avia_sc_testimonial::$counter_prev == avia_sc_testimonial::$columns )
			{
				$output .= '</section>';
				avia_sc_testimonial::$section_printed = true;
			}
			else
			{
				avia_sc_testimonial::$section_printed = false;
			}

			return $output;
		}

	}
}

