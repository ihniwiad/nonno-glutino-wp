<?php

/**
 * custom post type tutorials
 */

function create_tutorials_custom_post() {
    register_post_type( 'tutorials-cpt', // my-custom-post
        array(
        'labels' => array(
            'name' => __( 'Tutorials', 'bsx-wordpress' ),
            'singular_name' => __( 'Tutorial', 'bsx-wordpress' ),
        ),
        'public' => true,
        'has_archive' => false,
        'rewrite' => array( 
            'slug' => 'tutorials', 
            'with_front' => false,
        ),
        'show_in_rest' => true,
        'supports' => array(
            'title',
            'editor',
            'custom-fields',
            'thumbnail',
            'page-attributes' // position etc.
            // 'excerpt',
        ),
        'menu_position' => 30,
        'menu_icon' => 'dashicons-info',
        'exclude_from_search' => true,
        'publicly_queryable'  => true, // Whether queries can be performed on the front end for the post type as part of parse_request(). Endpoints would include: * ?post_type={post_type_key} * ?{post_type_key}={single_post_slug} * ?{post_type_query_var}={single_post_slug} If not set, the default is inherited from $public.
        'taxonomies'  => array( 'tutorials-cat' ),
        'show_in_nav_menus' => false,
    ) );
}
add_action( 'init', 'create_tutorials_custom_post' );



