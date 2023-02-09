<?php
namespace aviaFramework\widgets;

/**
 * AVIA SOCIALCOUNT
 *
 * Widget that retrieves, stores and displays the number of Twitter and rss followers
 *
 * @package AviaFramework
 * @since ???
 * @since 4.9			Code was moved from class-framework-widgets.php
 */
if( ! defined( 'AVIA_FW' ) ) {  exit( 'No direct script access allowed' );  }


if( ! class_exists( __NAMESPACE__ . '\avia_socialcount' ) )
{
	class avia_socialcount extends \aviaFramework\widgets\base\Avia_Widget
	{
		/**
		 *
		 */
		public function __construct()
		{
			$id_base = 'avia_socialcount';
			$name = THEMENAME . ' ' . __( 'RSS Link and Twitter Account', 'avia_framework' );

			$widget_options = array(
							'classname'				=> 'avia_socialcount avia_no_block_preview',
							'description'			=> __( 'A widget to display a link to your Twitter profile and rss feed', 'avia_framework' ),
							'show_instance_in_rest' => true,
							'customize_selective_refresh' => false
						);

			parent::__construct( $id_base, $name, $widget_options );

			$this->defaults = array(
								'rss'		=> avia_get_option( 'feedburner' ),
								'twitter'	=> avia_get_option( 'twitter' )
							);
		}

		/**
		 * Output the widget in frontend
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance )
		{
			$instance = $this->parse_args_instance( $instance );

			if( $this->in_block_editor_preview( $args, $instance ) )
			{
				return;
			}

			extract( $args, EXTR_SKIP );

			$twitter = empty( $instance['twitter'] ) ? '' : $instance['twitter'];
			$rss = empty( $instance['rss'] ) ? '' : $instance['rss'];

			$rss = preg_replace( '!https?:\/\/feeds.feedburner.com\/!', '', $rss );


			if( ! empty( $twitter ) || ! empty( $rss ) )
			{
				$addClass = 'asc_multi_count';

				if( ! isset( $twitter ) || ! isset( $rss ) )
				{
					$addClass = 'asc_single_count';
				}

				echo $before_widget;

				$output = '';
				if( ! empty( $twitter ) )
				{
					$link = 'http://twitter.com/' . $twitter . '/';
					$before = apply_filters( 'avf_social_widget', '', 'twitter' );
					$output .= "<a href='{$link}' class='asc_twitter {$addClass}'>";
					$output .=		$before;
					$output .=		'<strong class="asc_count">' . __( 'Follow', 'avia_framework' ) . '</strong>';
					$output .=		'<span>' . __( 'on Twitter', 'avia_framework' ) . '</span>';
					$output .= '</a>';
				}

				if( $rss )
				{
					$output .= "<a href='{$rss}' class='asc_rss {$addClass}'>";
					$output .=		apply_filters( 'avf_social_widget', '', 'rss' );
					$output .=		'<strong class="asc_count">' . __( 'Subscribe', 'avia_framework' ) . '</strong>';
					$output .=		'<span>' . __( 'to RSS Feed', 'avia_framework' ).'</span>';
					$output .= '</a>';
				}

				echo $output;
				echo $after_widget;
			}
		}

		/**
		 * Callback to output a custom block preview
		 *
		 * @since 4.9
		 * @param array $args
		 * @param array $instance
		 * @return boolean					true if output processed
		 */
		protected function widget_block_preview( array $args, array $instance = array() )
		{
			echo isset( $args['before_widget'] ) ? $args['before_widget'] : '';

			echo '<div class="avia-preview-headline">' . $this->name . '</div>';
			echo '<div class="avia-preview-info">' . __( 'RSS Feed URL:', 'avia_framework' ) . ' ' . $instance['rss'] .  '</div>';
			echo '<div class="avia-preview-info">' . __( 'Twitter Username:', 'avia_framework' ) . ' ' . $instance['twitter'] .  '</div>';
			echo '<div class="avia-preview-in-front">' . __( 'Content is only rendered in frontend.', 'avia_framework' ) . '</div>';

			echo isset( $args['after_widget'] ) ? $args['after_widget'] : '';

			return true;
		}

		/**
		 * Update widget options
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 * @return array
		 */
		public function update( $new_instance, $old_instance )
		{
			$instance = $this->parse_args_instance( $old_instance );

			foreach( $new_instance as $key => $value )
			{
				$instance[ $key ] = strip_tags( $value );
			}

			return $instance;
		}

		/**
		 * Output the form in backend
		 *
		 * @param array $instance
		 */
		public function form( $instance )
		{
			$instance = $this->parse_args_instance( $instance );

			$twitter = empty( $instance['twitter'] ) ? '' :  strip_tags( $instance['twitter'] );
			$rss = empty( $instance['rss'] ) ? '' :  strip_tags( $instance['rss'] );
	?>
			<p>
				<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter Username:', 'avia_framework' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>" /></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'rss' ); ?>"><?php _e( 'Enter your feed url:', 'avia_framework' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo esc_attr( $rss ); ?>" /></label>
			</p>
<?php
		}
	}
}
