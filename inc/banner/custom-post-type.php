<?php

/**
 * custom post type banner
 */

function create_banner_custom_post() {
    register_post_type( 'banner-custom-post', // my-custom-post
        array(
        'labels' => array(
            'name' => __( 'Banner', 'bsx-wordpress' ),
            'singular_name' => __( 'Banner', 'bsx-wordpress' ),
        ),
        'public' => true,
        'has_archive' => false,
        'rewrite' => array( 'slug' => 'banner' ),
        'show_in_rest' => true,
        'supports' => array(
            'title',
            'editor',
            'custom-fields'
        ),
        'menu_position' => 30,
        'menu_icon' => 'dashicons-button',
    ) );
}
add_action( 'init', 'create_banner_custom_post' );





