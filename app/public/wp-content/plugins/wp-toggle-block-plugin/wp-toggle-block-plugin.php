<?php
/**
 * Plugin Name: WP Toggle Block Plugin
 * Description: A Gutenberg block with a toggle to switch between two other blocks.
 * Version: 1.0
 * Author: Your Name
 */

 function toggle_block_enqueue_assets() {
    wp_enqueue_script(
        'toggle-block-editor-script',
        plugin_dir_url(__FILE__) . 'build/index.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
        filemtime(plugin_dir_path(__FILE__) . 'build/index.js')
    );

    // Enqueue front-end JavaScript
    wp_enqueue_script(
        'toggle-block-frontend-script',
        plugin_dir_url(__FILE__) . 'src/frontend.js', // Adjust the path if needed
        array('jquery'),
        filemtime(plugin_dir_path(__FILE__) . 'src/frontend.js'),
        true
    );
}

add_action('enqueue_block_editor_assets', 'toggle_block_enqueue_assets');
add_action('wp_enqueue_scripts', 'toggle_block_enqueue_assets');