<?php
/**
 * Plugin Name:       GeoApps
 * Description:       GeoApps Wordpress plugin
 * Requires at least: 5.8
 * Requires PHP:      5.6
 * Version:           1.1.0
 * Author:            MapGear B.V.
 * License:           GPLv2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       geoapps
 *
 * @package           geoapps
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
 */
function geoapps_create_map_block() {
	wp_enqueue_script(
        'geoapps-script',
        "https://mapgear.geoapps.nl/scripts/geoapps/v1/geoapps.min.js",
        array()
    );
    wp_enqueue_style(
        'geoapps-style',
        "https://mapgear.geoapps.nl/scripts/geoapps/v1/geoapps.min.css",
        array()
    );

	register_block_type(__DIR__, array(
        "render_callback" => "geoapps_render_map_block",
    ));
}
add_action( 'init', 'geoapps_create_map_block' );

/**
 * Implement the frontend rendering for this block. It generates a div, configured with the styling
 * properties. After that, it initialized the map object into this specific div through the GeoApps API
 * 
 * Details of the GeoApps API can be found at: https://docs.geoapps.dev/
 */
function geoapps_render_map_block($settings) {
    $id = uniqid('mv_');

    $output = "";

    $output .= "<div style=\"width: " . $settings["width"] . "; height: " . $settings["height"] . "; border: 1px solid black;\" id=\""  . $id . "\"></div>";

    $output .= "<script type=\"text/javascript\">";
    $output .= "(function() {";
    $output .= "geoapps.Initialize(\"" . $settings["tenant_url"] . "\");";
    $output .= "var map = geoapps.AddMap(\"" . $id . "\", \"" . $settings["map_id"] . "\");";
    $output .= "map.Controls.AddZoomControls();";
    $output .= "map.Interactions.DisableMouseWheelZoom();";
    $output .= "})();";
    $output .= "</script>";

    return $output;
}

/**
 * Configure a new block category, specific for GeoApps. This shows up in the
 * Gutenberg editor when creating a new page or post, so the block(s) provided
 * through this plugin are clearly distuingished.
 */
function geoapps_block_category( $categories ) {
	return array_merge(
		$categories,
		[
			[
				'slug'  => 'geoapps',
				'title' => __( 'GeoApps', 'geoapps' ),
			],
		]
	);
}
add_action( 'block_categories', 'geoapps_block_category', 10, 2 );
