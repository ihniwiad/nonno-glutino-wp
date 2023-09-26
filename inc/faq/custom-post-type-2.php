<?php

/**
 * custom post type faq 2
 */

function create_faq_2_custom_post() {
    $menu_icon = file_get_contents( __DIR__ . '/menu-icon-2.svg' );
    register_post_type( 'faq-2-cpt', // my-cpt
        array(
        'labels' => array(
            'name' => __( 'FAQs', 'bsx-wordpress' ) . ' 2',
            'singular_name' => __( 'FAQ', 'bsx-wordpress' ) . ' 2',
        ),
        'public' => true,
        'has_archive' => false,
        'rewrite' => array( 'slug' => 'faq-2' ),
        'show_in_rest' => true,
        'supports' => array(
            'title',
            'editor',
            'custom-fields'
        ),
        'menu_position' => 31,
        // 'menu_icon' => 'dashicons-editor-help',
        'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode( $menu_icon ),
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
    ) );
}
add_action( 'init', 'create_faq_2_custom_post' );


