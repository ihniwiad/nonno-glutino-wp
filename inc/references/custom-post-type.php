<?php

/**
 * custom post type references
 */

function create_references_custom_post() {
    register_post_type( 'ref-custom-post', // my-custom-post
        array(
        'labels' => array(
            'name' => __( 'References', 'bsx-wordpress' ),
            'singular_name' => __( 'Reference', 'bsx-wordpress' ),
        ),
        'public' => true,
        'has_archive' => false,
        'rewrite' => array( 'slug' => 'references' ),
        'show_in_rest' => true,
        'supports' => array(
            'title',
            'editor',
            'custom-fields',
            'thumbnail',
            'page-attributes' // position etc.
        ),
        'menu_position' => 30,
        'menu_icon' => 'dashicons-flag',
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'taxonomies'  => array( 'category' ),
    ) );
}
add_action( 'init', 'create_references_custom_post' );


