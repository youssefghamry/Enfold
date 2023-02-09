<?php
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly

/*
 * Rank Math SEO Integration
 */

if( ! defined( 'RANK_MATH_VERSION' ) && ! class_exists( 'RankMath' ) )
{
	return;
}

function avia_rank_math_register_assets()
{
	wp_enqueue_script( 
		'avia_analytics_js', 
		AVIA_BASE_URL . 'config-templatebuilder/avia-template-builder/assets/js/avia-analytics.js' , 
		[ 'avia_builder_js' ], 
		false, 
		true 
	);

	wp_enqueue_script( 
		'avia_rank_math_js', 
		AVIA_BASE_URL . 'config-rank-math/rank-math-mod.js', 
		[ 'wp-hooks', 'wp-shortcode', 'rank-math-analyzer', 'avia_analytics_js' ], 
		false, 
		true
	);
}

if( is_admin() )
{
	add_action('init', 'avia_rank_math_register_assets');
}

if( ! function_exists( 'avia_rank_math_register_toc_widget' ) )
{

	add_filter('rank_math/researches/toc_plugins', 'avia_rank_math_register_toc_widget', 10, 1);

	/**
	 * Notifies Rank Math that the theme contains a TOC widget or element. 
	 * https://rankmath.com/kb/table-of-contents-not-detected/
	 *
	 * @since 5.0
	 * @return array
	 */
	function avia_rank_math_register_toc_widget($toc_plugins) {
		$toc_plugins['seo-by-rank-math/rank-math.php'] = 'Rank Math';

		return $toc_plugins;
	}
}

