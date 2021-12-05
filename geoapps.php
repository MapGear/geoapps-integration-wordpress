<?php
/**
 * Plugin Name:       GeoApps
 * Description:       GeoApps Wordpress plugin
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            MapGear B.V.
 * License:           ISV
 * License URI:       
 * Text Domain:       geoapps
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
 */
function createGeoAppsBlock() {
	wp_enqueue_script(
        'geoapps-script',
        "https://demo.geoapps.nl/scripts/geoapps/v1/geoapps.min.js",
        array()
    );
    wp_enqueue_style(
        'geoapps-style',
        "https://demo.geoapps.nl/scripts/geoapps/v1/geoapps.min.css",
        array()
    );

	register_block_type(__DIR__, array(
        "render_callback" => "render_map_block",
    ));
}
add_action( 'init', 'createGeoAppsBlock' );


function render_map_block($settings) {
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