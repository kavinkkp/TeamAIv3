<?php
/**
 * Plugin Name: Typeform
 * Plugin URI:  https://www.typeform.com/
 * Description: Create beautiful online forms, surveys, quizzes, and much more.
 * Version:     1.3.2
 * Author:      Typeform
 * Author URI:  https://www.typeform.com/?utm_source=wordpressorg&utm_medium=referral&utm_campaign=wordpressorg_integration&utm_content=directory
 * License:     Apache-2.0
 * License URI: https://directory.fsf.org/wiki/License:Apache-2.0
 *
 * @package typeform
 */

defined('ABSPATH') or die('No script kiddies please!');

function typeform_shortcode_handler($attributes)
{
    $url = FALSE;
    $type = isset($attributes['type']) ? $attributes['type'] : null;
    $text = isset($attributes['button_text']) ? $attributes['button_text'] : null;
    $height = isset($attributes['height']) ? $attributes['height'] : null;
    $width = isset($attributes['width']) ? $attributes['width'] : null;

    if (isset($attributes['url'])) {
        $url = $attributes['url'];
    } elseif (isset($attributes['builder'])) {
        $url = 'https://template.typeform.com/to/Bmx0OB?' . $attributes['builder'];
    }

    if ($url != FALSE) {
        switch ($type) {
            case 'drawer':
                return '<typeform-popup url="' . $url . '" mode="drawer_left">' . $text . '</typeform-popup>';
            case 'popup':
                return '<typeform-popup url="' . $url . '">' . $text . '</typeform-popup>';
            case 'embed':
            default:
                return '<typeform-widget url="' . $url . '" height="' . $height . '" width="' . $width . '"></typeform-widget>';
        }
    }
}

function typeform_plugin_scripts()
{
    wp_enqueue_script(
        'typeform-embed',
        plugin_dir_url(__FILE__) . 'dist/typeform-embed-block.js',
        array('wp-blocks', 'wp-i18n', 'wp-editor'),
        true
    );

    wp_enqueue_style(
        'videoask-embed-style',
        plugin_dir_url(__FILE__) . 'dist/style.css'
    );
}

function typeform_elements()
{
    echo '<script type="text/javascript" src="' . plugin_dir_url(__FILE__) . 'dist/typeform-elements.js"></script>';
}

// For gutenberg
if (function_exists('register_block_type')) {
    add_shortcode('typeform_embed', 'typeform_shortcode_handler');
    add_action('wp_head', 'typeform_elements');
    add_action('enqueue_block_editor_assets', 'typeform_plugin_scripts');
}
