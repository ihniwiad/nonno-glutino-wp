<?php 

// CHANGE LOCAL LANGUAGE
// must be called before load_theme_textdomain()
 
add_filter( 'locale', 'bsx_theme_localized' );

/**
 * Switch to locale given as query parameter l, if present
 */
function bsx_theme_localized( $locale ) {
    if ( isset( $_GET[ 'l' ] ) ) {
        return sanitize_key( $_GET[ 'l' ] );
    }
    return $locale;
}
/**
 * Load theme translation from bsx-wordpress-example/languages/ directory
 */
load_theme_textdomain( 'bsx-wordpress', get_template_directory() . '/languages' );



/**
 * helper
 */

$file = dirname( __FILE__ ) . '/inc/helper/helper-functions.php';
if ( file_exists( $file ) ) {
    require $file;
}


// paths
$functions_file_basename = basename( __FILE__ );

$rootPath = get_template_directory().'/';
$resourcesPath = 'resources/';
$assetsPath = $rootPath.'assets/';


/**
 * variables
 */

// paths

$serverName = $_SERVER[ 'SERVER_NAME' ];
$homeUrl = get_bloginfo( 'url' ) . '/';

$rootPath = get_bloginfo( 'template_directory' ).'/';
$resourcesPath = 'resources/';

$relativeAssetsPath = 'assets/';
$assetsPath = $rootPath . $relativeAssetsPath;

// make equal protocol
$rootRelatedAssetsPath = explode( str_replace( 'https://', 'http://', $homeUrl ), str_replace( 'https://', 'http://', $assetsPath ) )[ 1 ];

// get css file version using absolute file path
$cssFileName = 'css/style.min.css';
$cssFilePath = $rootRelatedAssetsPath . $cssFileName;
$cssVersion = file_exists( $cssFilePath ) ? filemtime( $cssFilePath ) : 'null';

// get js file versions
$vendorJsFileName = 'js/vendor.min.js';
$vendorJsFilePath = $rootRelatedAssetsPath . $vendorJsFileName;
$vendorJsVersion = file_exists( $vendorJsFilePath ) ? filemtime( $vendorJsFilePath ) : 'null';

$scriptsJsFileName = 'js/scripts.min.js';
$scriptsJsFilePath = $rootRelatedAssetsPath . $scriptsJsFileName;
$scriptsJsVersion = file_exists( $scriptsJsFilePath ) ? filemtime( $scriptsJsFilePath ) : 'null';


// logo path
$logoPath = $assetsPath . 'img/ci/logo/logo.svg';


// dev mode
$isDevMode = false;
if ( isset( $_GET[ 'dev' ] ) && $_GET[ 'dev' ] == '1' ) {
    $isDevMode = true;
}


// patterns
$phoneHrefRemovePatterns = array( '/ /i', '/\./i', '/\//i', '/-/i' );



/**
 * include required files
 */

// classes
require get_template_directory() . '/src/libs/nav/classes/class-bsx-walker-page.php';
require get_template_directory() . '/src/libs/nav/classes/class-bsx-walker-nav-menu.php';

require_once( __DIR__ . '/src/libs/data-processing-consent/class-consent-popup-manager.php' );
require_once( __DIR__ . '/src/libs/img-gallery/class-bsx-photoswipe.php' );
require_once( __DIR__ . '/src/libs/lazy-img/class-lazy-img.php' );


/**
 * WordPress titles
 */
add_theme_support( 'title-tag' );


// remove unnecessary meta tags
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' ) ; 
remove_action( 'wp_head', 'rsd_link' ) ;


/**
 * navigations
 */

function register_my_menus() {
    register_nav_menus(
        array(
            'primary-menu' => __( 'Primary Menu', 'bsx-wordpress' ),
            'footer-column-1-menu' => __( 'Footer Column 1 Menu', 'bsx-wordpress' ),
            'footer-column-2-menu' => __( 'Footer Column 2 Menu', 'bsx-wordpress' ),
            'footer-column-3-menu' => __( 'Footer Column 3 Menu', 'bsx-wordpress' ),
            'footer-column-4-menu' => __( 'Footer Column 4 Menu', 'bsx-wordpress' ),
            'footer-column-5-menu' => __( 'Footer Column 5 Menu', 'bsx-wordpress' ),
            'language-switcher' => __( 'Language switcher', 'bsx-wordpress' ),
            'footer-bottom-menu' => __( 'Footer Bottom Menu', 'bsx-wordpress' ),
            'consent-popup-menu' => __( 'Consent Popup Menu', 'bsx-wordpress' ),
            'mobile-fixed-menu' => __( 'Mobile Fixed Menu', 'bsx-wordpress' )
        )
    );
}
add_action( 'init', 'register_my_menus' );

// add filter to add class name to li or/and class name to current li
function add_additional_class_on_li( $classes, $item, $args ) {
    if ( isset( $args->add_li_class ) ) {
        $classes[] = $args->add_li_class;
    }
    // useful e.g. for language switcher
    if ( isset( $args->add_current_li_class ) ) {
        $classes = is_array( $classes ) ? $classes : (array) $classes;
        if ( in_array( 'current_page_item', $classes ) || in_array( 'current-lang', $classes ) ) {
            $classes[] = $args->add_current_li_class;
        }
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'add_additional_class_on_li', 1, 3 );

// add filter to add class name to a
function add_additional_class_on_a( $atts, $item, $args ) {
    if ( isset( $args->add_a_class ) ) {
        $class = $args->add_a_class;
        if ( isset( $atts[ 'class' ] ) ) {
            $atts[ 'class' ] .= $class;
        }
        else {
            $atts[ 'class' ] = $class;
        }
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_additional_class_on_a', 10, 3 );


/**
 * disable emoji
 */

function disable_wp_emojicons() {
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
    add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'disable_wp_emojicons' );

function disable_emojicons_tinymce( $plugins ) {
    return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
}


/**
 * remove block editor styles from frontend.
 */

function remove_editor_blocks_assets() {
    if ( ! is_admin() ) {
        wp_dequeue_style( 'editor-blocks' );
    }
}
add_action( 'enqueue_block_assets', 'remove_editor_blocks_assets' );

/**
 * remove block library css
 */

function wpassist_remove_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
} 
add_action( 'wp_enqueue_scripts', 'wpassist_remove_block_library_css' );

/**
 * remove more embed stuff (wp-embed.min.js)
 */
 
add_action( 'init', function() {
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    // remove global-styles-inline-css
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
}, PHP_INT_MAX - 1 );


// REMOVED SINCE USING PLUGIN Yoast SEO INSTEAD
/**
 * add Open Graph Meta Tags
 */

// add meta boxes for title & discription, use title & excerpt as fallback
/*
function meta_og() {
    global $post;

    if ( is_single() || is_page() ) {

        $meta = get_post_meta( $post->ID, 'meta_tag', true );

        if ( isset( $meta[ 'meta_title' ] ) && $meta[ 'meta_title' ] != '' ) {
            $title = $meta[ 'meta_title' ];
        }
        else {
            $title = get_the_title();
        }
        
        if ( isset( $meta[ 'meta_description' ] ) && $meta[ 'meta_description' ] != '' ) {
            $description = $meta[ 'meta_description' ];
        }
        else {
            $excerpt = strip_tags( $post->post_content );
            $excerpt_more = '';
            if ( strlen($excerpt ) > 155) {
                $excerpt = substr( $excerpt, 0, 155 );
                $excerpt_more = ' ...';
            }
            $excerpt = str_replace( '"', '', $excerpt );
            $excerpt = str_replace( "'", '', $excerpt );
            $excerptwords = preg_split( '/[\n\r\t ]+/', $excerpt, -1, PREG_SPLIT_NO_EMPTY );
            array_pop( $excerptwords );
            $excerpt = implode( ' ', $excerptwords ) . $excerpt_more;

            $description = $excerpt;
        }

        ?>
<meta name="description" content="<?php echo $description; ?>">
<meta property="og:title" content="<?php echo $title; ?>">
<meta property="og:description" content="<?php echo $description; ?>">
<meta property="og:url" content="<?php echo the_permalink(); ?>">
<meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ); ?>">

        <?php

        if ( has_post_thumbnail( $post->ID ) ) {
            $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
            ?>
<meta property="og:image" content="<?php echo $img_src[0]; ?>">
            <?php 
        } 

        if ( is_single() ) {
            ?>
<meta name="author" content="<?php echo get_the_author(); ?>">
<meta property="og:type" content="article">
            <?php
        }
    } 
    else {
        return;
    }
}
add_action( 'wp_head', 'meta_og', 5 );
*/


/**
 * remove admin bar
 */

function remove_admin_bar() {
    //if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
        show_admin_bar( false );
    //}
}
add_action( 'after_setup_theme', 'remove_admin_bar' );


/**
 * enable featured images
 */

add_theme_support( 'post-thumbnails' );


/**
 * Generate custom search form
 *
 * @param string $form Form HTML.
 * @return string Modified form HTML.
 */
function custom_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
        <div>
            <label class="sr-only" for="s">' . __( 'Search for:' ) . '</label>
            <div class="input-group input-group-lg">
                <input class="form-control" type="text" value="' . get_search_query() . '" name="s" id="s" />
                <div class="input-group-append">
                    <input class="btn btn-primary" type="submit" id="searchsubmit" value="'. esc_attr__( 'Search' ) .'" />
                </div>
            </div>
        </div>
    </form>';
 
    return $form;
}
add_filter( 'get_search_form', 'custom_search_form' );

/**
 * Reduce search to posts (exclude pages)
 */
function reduce_search_only_posts( $query ) {
    if ( $query->is_search ) {
        $query->set( 'post_type', 'post' );
    }
    return $query;
}
add_filter( 'pre_get_posts', 'reduce_search_only_posts' );


// manage allowed block types

// function myplugin_allowed_block_types( $allowed_block_types, $post ) {     
//     if ( $post->post_type !== 'page' || $post->post_type !== 'post' ) {
//         return array( 
//             'core/paragraph', 
//             'core/heading', 
//             'core/list', 
//             'bsx-blocks/banner',
//             'bsx-blocks/buttons', 
//             'bsx-blocks/button', 
//             'bsx-blocks/button-label', 
//             'bsx-blocks/column-row', 
//             'bsx-blocks/column-rows', 
//             'bsx-blocks/container', 
//             'bsx-blocks/groups', 
//             'bsx-blocks/img-gallery', 
//             'bsx-blocks/lazy-img', 
//             'bsx-blocks/col', 
//             'bsx-blocks/row-with-cols', 
//             'bsx-blocks/section', 
//             'bsx-blocks/slider', 
//             'bsx-blocks/wrapper', 
//         );
//     }
 
//     return $allowed_block_types;
// }
 
// add_filter( 'allowed_block_types', 'myplugin_allowed_block_types', 10, 2 );


/**
 * custom global options, add menu with sublevels
 */

function custom_settings_add_menu() {
    add_menu_page( 
        __( 'Theme Settings', 'bsx-wordpress' ), // page title
        __( 'Theme Settings', 'bsx-wordpress' ), // menu title
        'manage_options', // capability
        'custom_options', // menu_slug
        'custom_settings_page', // function to show related content
        null, // icon url
        1 // position
    );
    add_submenu_page( 
        'custom_options', // parent_slug
        __( 'Social Media' ), // page_title
        __( 'Social Media' ), // menu_title
        'manage_options', // capability
        'custom-settings-social-media', // menu_slug, 
        'custom_settings_social_media', // function = '', 
        1 // position = null
    );
    add_submenu_page( 
        'custom_options', // parent_slug
        __( 'Layout' ), // page_title
        __( 'Layout' ), // menu_title
        'manage_options', // capability
        'custom-settings-layout', // menu_slug, 
        'custom_settings_layout', // function = '', 
        99 // position = null
    );
}
add_action( 'admin_menu', 'custom_settings_add_menu' );

function custom_settings_page() { ?>
    <div class="wrap">
        <h2><?php __( 'Theme Settings', 'bsx-wordpress' ); ?></h2>
        <form method="post" action="options.php">
            <?php
                do_settings_sections( 'custom_options_contact' ); // page
                settings_fields( 'custom-settings-contact' ); // option group (may have multiple sections)
                submit_button();
            ?>
        </form>
    </div>
<?php }
function custom_settings_social_media() { ?>
    <div class="wrap">
        <h2><?php __( 'Social Media', 'bsx-wordpress' ); ?></h2>
        <form method="post" action="options.php">
            <?php
                do_settings_sections( 'custom_options_social_media' ); // page
                settings_fields( 'custom-settings-social-media' ); // option group (may have multiple sections)
                submit_button();
            ?>
        </form>
    </div>
<?php }
function custom_settings_layout() { ?>
    <div class="wrap">
        <h2><?php __( 'Layout', 'bsx-wordpress' ); ?></h2>
        <form method="post" action="options.php">
            <?php
                do_settings_sections( 'custom_options_layout' ); // page
                settings_fields( 'custom-settings-layout' ); // option group (may have multiple sections)
                submit_button();
            ?>
        </form>
    </div>
<?php }


/**
 * custom settings, create pages setup
 */

function custom_settings_page_setup() {

    // section
    add_settings_section(
        'custom-settings-section-contact', // id
        __( 'Contact', 'bsx-wordpress' ), // title
        null, // callback function
        'custom_options_contact' // page
    );

    // fields for section
    add_settings_field(
        'owner-name', // id
        __( 'Owner name', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'owner-name',
            'label_for' => 'owner-name'
        ) // args = array()
    );
    add_settings_field(
        'name-2', // id
        __( 'Name 2', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'name-2',
            'label_for' => 'name-2'
        ) // args = array()
    );
    add_settings_field(
        'street', // id
        __( 'Street', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'street',
            'label_for' => 'street'
        ) // args = array()
    );
    add_settings_field(
        'address-additional', // id
        __( 'Adress Additional', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'address-additional',
            'label_for' => 'address-additional'
        ) // args = array()
    );
    add_settings_field(
        'zip', // id
        __( 'Zip', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'zip',
            'label_for' => 'zip'
        ) // args = array()
    );
    add_settings_field(
        'city', // id
        __( 'City', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'city',
            'label_for' => 'city'
        ) // args = array()
    );
    add_settings_field(
        'country', // id
        __( 'Country', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'country',
            'label_for' => 'country'
        ) // args = array()
    );
    add_settings_field(
        'phone', // id
        __( 'Phone', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'phone',
            'label_for' => 'phone'
        ) // args = array()
    );
    add_settings_field(
        'mail', // id
        __( 'Email', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'mail',
            'label_for' => 'mail'
        ) // args = array()
    );
    add_settings_field(
        'service-phone', // id
        __( 'Service Phone', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'service-phone',
            'label_for' => 'service-phone'
        ) // args = array()
    );
    add_settings_field(
        'service-mail', // id
        __( 'Service Email', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'service-mail',
            'label_for' => 'service-mail'
        ) // args = array()
    );
    add_settings_field(
        'additional-url', // id
        __( 'Additional URL', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_contact', // page
        'custom-settings-section-contact', // section = 'default'
        array(
            'additional-url',
            'label_for' => 'additional-url'
        ) // args = array()
    );

    // register each field
    register_setting(
        'custom-settings-contact', // option group
        'owner-name' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'name-2' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'street' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'address-additional' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'zip' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'city' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'country' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'phone' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'mail' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'service-phone' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'service-mail' // option name
    );
    register_setting(
        'custom-settings-contact', // option group
        'additional-url' // option name
    );

    // social media section
    add_settings_section(
        'custom-settings-section-social-media', // id
        __( 'Social Media', 'bsx-wordpress' ), // title
        null, // callback function
        'custom_options_social_media' // page
    );

    // fields for section
    add_settings_field(
        'facebook', // id
        __( 'Facebook', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_social_media', // page
        'custom-settings-section-social-media', // section = 'default'
        array(
            'facebook',
            'label_for' => 'facebook'
        ) // args = array()
    );
    add_settings_field(
        'twitter', // id
        __( 'Twitter', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_social_media', // page
        'custom-settings-section-social-media', // section = 'default'
        array(
            'twitter',
            'label_for' => 'twitter'
        ) // args = array()
    );
    add_settings_field(
        'instagram', // id
        __( 'Instagram', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_social_media', // page
        'custom-settings-section-social-media', // section = 'default'
        array(
            'instagram',
            'label_for' => 'instagram'
        ) // args = array()
    );
    add_settings_field(
        'googleplus', // id
        __( 'Google Plus', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_social_media', // page
        'custom-settings-section-social-media', // section = 'default'
        array(
            'googleplus',
            'label_for' => 'googleplus'
        ) // args = array()
    );
    add_settings_field(
        'xing', // id
        __( 'Xing', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_social_media', // page
        'custom-settings-section-social-media', // section = 'default'
        array(
            'xing',
            'label_for' => 'xing'
        ) // args = array()
    );
    add_settings_field(
        'linkedin', // id
        __( 'LinkedIn', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_social_media', // page
        'custom-settings-section-social-media', // section = 'default'
        array(
            'linkedin',
            'label_for' => 'linkedin'
        ) // args = array()
    );

    // register each field
    register_setting(
        'custom-settings-social-media', // option group
        'facebook' // option name
    );
    register_setting(
        'custom-settings-social-media', // option group
        'twitter' // option name
    );
    register_setting(
        'custom-settings-social-media', // option group
        'instagram' // option name
    );
    register_setting(
        'custom-settings-social-media', // option group
        'googleplus' // option name
    );
    register_setting(
        'custom-settings-social-media', // option group
        'xing' // option name
    );
    register_setting(
        'custom-settings-social-media', // option group
        'linkedin' // option name
    );

    // layout section
    add_settings_section(
        'custom-settings-section-layout', // id
        __( 'Layout', 'bsx-wordpress' ), // title
        null, // callback function
        'custom_options_layout' // page
    );

    // fields for section
    add_settings_field(
        'footer_columns_count', // id
        esc_html__( 'Footer Menu Columns Count (0...5)', 'bsx-wordpress' ), // title
        'render_custom_input_field', // callback, use unique function name
        'custom_options_layout', // page
        'custom-settings-section-layout', // section = 'default'
        array(
            'footer_columns_count',
            'label_for' => 'footer_columns_count'
        ) // args = array()
    );
    add_settings_field(
        'footer_phone_mail_show', // id
        esc_html__( 'Show Phone & Email in footer', 'bsx-wordpress' ), // title
        'render_custom_checkbox', // callback, use unique function name
        'custom_options_layout', // page
        'custom-settings-section-layout', // section = 'default'
        array(
            'footer_phone_mail_show',
            'label_for' => 'footer_phone_mail_show'
        ) // args = array()
    );
    add_settings_field(
        'social_media_colors_use', // id
        esc_html__( 'Use Social Media Brand colors', 'bsx-wordpress' ), // title
        'render_custom_checkbox', // callback, use unique function name
        'custom_options_layout', // page
        'custom-settings-section-layout', // section = 'default'
        array(
            'social_media_colors_use',
            'label_for' => 'social_media_colors_use'
        ) // args = array()
    );
    add_settings_field(
        'footer_menu_names_show', // id
        esc_html__( 'Footer menu names show', 'bsx-wordpress' ), // title
        'render_custom_checkbox', // callback, use unique function name
        'custom_options_layout', // page
        'custom-settings-section-layout', // section = 'default'
        array(
            'footer_menu_names_show',
            'label_for' => 'footer_menu_names_show'
        ) // args = array()
    );


    // register each field
    register_setting(
        'custom-settings-layout', // option group
        'footer_columns_count' // option_name
    );
    register_setting(
        'custom-settings-layout', // option group
        'footer_phone_mail_show' // option_name
    );
    register_setting(
        'custom-settings-layout', // option group
        'social_media_colors_use' // option_name
    );
    register_setting(
        'custom-settings-layout', // option group
        'footer_menu_names_show' // option_name
    );


}
// Shared  across sections
// modified from https://wordpress.stackexchange.com/questions/129180/add-multiple-custom-fields-to-the-general-settings-page
function render_custom_input_field( $args ) {
    $options = get_option( $args[ 0 ] );
    echo '<input type="text" id="'  . $args[ 0 ] . '" name="'  . $args[ 0 ] . '" value="' . $options . '"></input>';
}
function render_custom_checkbox( $args ) {
    $options = get_option( $args[ 0 ] );
    echo '<label><input type="checkbox" id="'  . $args[ 0 ] . '" name="' . $args[ 0 ] . '" value="1"' . ( ( $options ) ? 'checked' : '' ) . ' />' . __( 'Yes', 'bsx-wordpress' ) . '</label>';
}
add_action( 'admin_init', 'custom_settings_page_setup' );


// REMOVED SINCE USING PLUGIN Yoast SEO INSTEAD
/**
 * meta boxes
 */

// page style
function add_page_style_meta_box() {
    $screen = 'page'; // choose 'post' or 'page'
    add_meta_box( 
        'page_style_meta_box', // $id
        __( 'Page Style', 'bsx-wordpress' ), // $title
        'show_page_style_meta_box', // $callback
        $screen, // $screen
        'side', // $context, choose 'normal' or 'side')
        'default', // $priority
        null 
    );
}
add_action( 'add_meta_boxes', 'add_page_style_meta_box' );

function show_page_style_meta_box() {
    global $post;
    $meta = get_post_meta( $post->ID, 'page_style', true ); 
    ?>
        <input type="hidden" name="page_style_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">
        <p>
            <label>
                <input type="checkbox" name="page_style[add_page_top_space]" value="1" <?php if ( is_array( $meta ) && isset( $meta[ 'add_page_top_space' ] ) && $meta[ 'add_page_top_space' ] == 1 ) echo 'checked' ?>><?php echo __( 'Add space on Page top', 'bsx-wordpress' ); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="page_style[wrap_page_with_container]" value="1" <?php if ( is_array( $meta ) && isset( $meta[ 'wrap_page_with_container' ] ) && $meta[ 'wrap_page_with_container' ] == 1 ) echo 'checked' ?>><?php echo  __( 'Wrap Page with Container', 'bsx-wordpress' ); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="page_style[show_breadcrumb]" value="1" <?php if ( is_array( $meta ) && isset( $meta[ 'show_breadcrumb' ] ) && $meta[ 'show_breadcrumb' ] == 1 ) echo 'checked' ?>><?php echo  __( 'Show Breadcrumb', 'bsx-wordpress' ); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="page_style[not_show_bottom_banner]" value="1" <?php if ( is_array( $meta ) && isset( $meta[ 'not_show_bottom_banner' ] ) && $meta[ 'not_show_bottom_banner' ] == 1 ) echo 'checked' ?>><?php echo  __( 'Do not show bottom banner', 'bsx-wordpress' ); ?>
            </label>
        </p>
    <?php 
}
function save_page_style_meta( $post_id ) {
    // verify nonce
    if ( isset( $_POST[ 'page_style_meta_box_nonce' ] ) && ! wp_verify_nonce( $_POST[ 'page_style_meta_box_nonce' ], basename(__FILE__) ) ) {
        return $post_id;
    }
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    // check permissions
    if ( isset( $_POST[ 'post_type' ] ) && 'page' === $_POST[ 'post_type' ] ) {
        if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        } 
        elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
    }
    // cannot check for `isset( $_POST[ 'page_style' ] )` since empty checkboxes would never be saved
    if ( isset( $_POST[ 'page_style_meta_box_nonce' ] ) ) {
        // $old = get_post_meta( $post_id, 'page_style', true );
        $new = $_POST[ 'page_style' ];
        // if ( isset( $new ) && $new !== $old ) {
            update_post_meta( $post_id, 'page_style', $new );
        // } 
        // elseif ( '' === $new && $old ) {
        //     delete_post_meta( $post_id, 'page_style', $old );
        // }
    }
}
add_action( 'save_post', 'save_page_style_meta' );

/*
// meta tag
function add_meta_tag_meta_box() {
    $screen = [ 'page', 'post' ]; // choose 'post' or 'page'
    add_meta_box( 
        'meta_tag_meta_box', // $id
        __( 'Meta Data', 'bsx-wordpress' ), // $title
        'show_meta_tag_meta_box', // $callback
        $screen, // $screen
        'side', // $context, choose 'normal' or 'side')
        'high', // $priority
        null 
    );
}
add_action( 'add_meta_boxes', 'add_meta_tag_meta_box' );

function show_meta_tag_meta_box() {
    global $post;
    $meta = get_post_meta( $post->ID, 'meta_tag', true ); 
    ?>
        <input type="hidden" name="meta_tag_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

        <p>
            <label for="meta_tag[meta_title]"><?php echo __( 'Title', 'bsx-wordpress' ); ?> (<b data-bsxui="char-counter">0</b> / 40&hellip;60)</label>
            <br>
            <textarea data-bsxui="counting-input" name="meta_tag[meta_title]" id="meta_tag[meta_title]" rows="2" cols="30" style="width:100%;"><?php if ( isset( $meta['meta_title'] ) ) { echo $meta['meta_title']; } ?></textarea>
        </p>
        <p>
            <label for="meta_tag[meta_title]"><?php echo __( 'Description', 'bsx-wordpress' ); ?> (<b data-bsxui="char-counter">0</b> / 150&hellip;160)</label>
            <br>
            <textarea data-bsxui="counting-input" name="meta_tag[meta_description]" id="meta_tag[meta_description]" rows="5" cols="30" style="width:100%;"><?php if ( isset( $meta['meta_description'] ) ) { echo $meta['meta_description']; } ?></textarea>
        </p>

        <script>
( function( $ ) {
    $( document.currentScript ).parent().find( '[ data-bsxui="counting-input"]' ).each( function() {
        $input = $( this );
        $.fn.updateCount = function() {
            $input = $( this );
            $counter = $input.parent().find( '[data-bsxui="char-counter"]' );
            var charCount = $input.val().length;
            $counter.html( charCount );
        }
        $input.updateCount();
        $input.on( 'change input paste keyup', function() {
            $( this ).updateCount();
        } );
    } );
} )( jQuery );
        </script>
    <?php 
}
function save_meta_tag_meta( $post_id ) {
    // verify nonce
    if ( isset( $_POST[ 'meta_tag_meta_box_nonce' ] ) && ! wp_verify_nonce( $_POST[ 'meta_tag_meta_box_nonce' ], basename(__FILE__) ) ) {
        return $post_id;
    }
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    // check permissions
    if ( isset( $_POST[ 'post_type' ] ) && 'page' === $_POST[ 'post_type' ] ) {
        if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        } 
        elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
    }
    if ( isset( $_POST[ 'meta_tag' ] ) ) {
        $old = get_post_meta( $post_id, 'meta_tag', true );
        $new = $_POST[ 'meta_tag' ];
        if ( $new && $new !== $old ) {
            update_post_meta( $post_id, 'meta_tag', $new );
        } 
        elseif ( '' === $new && $old ) {
            delete_post_meta( $post_id, 'meta_tag', $old );
        }
    }
}
add_action( 'save_post', 'save_meta_tag_meta' );
*/


/**
 * shortcodes
 */

// consent trigger button, use shortcode block with [consent-trigger-button]
function add_consent_button_shortcode() {
  $content = 'Missing method: Consent_Popup_Manager::popupTriggerHtml()';
  if ( class_exists( 'Consent_Popup_Manager' ) && method_exists( 'Consent_Popup_Manager', 'popupTriggerHtml' ) ) {
    $content = Consent_Popup_Manager::popupTriggerHtml();
  }
  return $content;
}
add_shortcode( 'consent-trigger-button', 'add_consent_button_shortcode' );




/**
 * banner custom post
 */
$file = dirname(__FILE__).'/inc/banner/custom-post-type.php';
if ( file_exists( $file ) ) {
    require $file;
}
/**
 * meta boxes for banner custom post
 */
$file = dirname( __FILE__ ) . '/inc/banner/meta-box.php';
if ( file_exists( $file ) ) {
    require $file;
    ( new BannerMeta() )->init();
}



/**
 * faq custom post
 */
$file = dirname(__FILE__).'/inc/faq/custom-post-type.php';
if ( file_exists( $file ) ) {
    require $file;
}
$file = dirname(__FILE__).'/inc/faq/custom-post-type-2.php';
if ( file_exists( $file ) ) {
    require $file;
}
$file = dirname(__FILE__).'/inc/faq/custom-post-type-3.php';
if ( file_exists( $file ) ) {
    require $file;
}
/**
 * faq list shortcode
 */
$file = dirname( __FILE__ ) . '/inc/faq/shortcode.php';
if ( file_exists( $file ) ) {
    require $file;
    ( new FaqShortcode() )->init();
}



/*
 * Set post views count using post meta
 */
function countPostViews( $postID ) {
    $countKey = 'post_views_count';
    $count = get_post_meta( $postID, $countKey, true );
    if ( $count == '' ) {
        $count = 0;
        delete_post_meta( $postID, $countKey );
        add_post_meta( $postID, $countKey, '0' );
    }
    else {
        $count++;
        update_post_meta( $postID, $countKey, $count);
    }
}





/*
 * Breadcrumb
 */

/*
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Library</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data</li>
  </ol>
</nav>
*/
class Bsx_Breadcrumb {

    static private function start_list() {
        $html = '<nav aria-label="breadcrumb">';
        $html .= '<ol class="breadcrumb">';
        return $html;
    }

    static private function end_list() {
        $html = '</ol>';
        $html .= '</nav>';
        return $html;
    }

    private function add_item( $title, $url = '', $data_id = '' ) {
        $html = '';
        if ( ! empty( $url ) ) {
            $html .= sprintf( 
                '<li class="breadcrumb-item"%s><a href="%s">%s</a></li>',
                ( ! empty( $data_id ) ? ' data-id="' . $data_id . '"' : '' ),
                $url,
                $title,
            );
        }
        else {
            // is current item
            $html .= sprintf( 
                '<li class="breadcrumb-item active" aria-current="page">%s</li>',
                $title,
            );
        }
        return $html;
    }

    public function makeHtml() {

        if ( is_front_page() ) {
            return;
        }

        global $post;
        $post_type = get_post_type();
        $custom_taxonomy  = ''; // if custom taxonomy place here

        $home_page_id = get_option( 'page_on_front' );
        $home_page_title = get_the_title( $home_page_id );

        $current_path = $_SERVER[ 'REQUEST_URI' ]; // path after domain

        $html = '';

        // start list
        $html .= $this::start_list();

        // add home item
        $html .= $this->add_item( $home_page_title, get_home_url() );

        // add other items
        if ( is_single() ) {
            // echo ' (SINGLE) ';

            // If post type is not post
            if ( $post_type != 'post' ) {
                $post_type_object   = get_post_type_object( $post_type );
                // $post_type_url     = get_post_type_archive_link( $post_type );
                // fix since above is empty
                $post_type_url = get_home_url() . '/' . $post_type_object->rewrite[ 'slug' ] . '/';

                $html .= $this->add_item( $post_type_object->labels->name, $post_type_url );
            }

            // Get categories
            $category = get_the_category( $post->ID );

            // If category not empty
            if ( ! empty( $category ) ) {
                // echo ' (CAT NOT EMPTY) ';

                $home_page_url = get_the_permalink( $home_page_id );
                $posts_page_id = get_option( 'page_for_posts' );
                $posts_page_url = get_the_permalink( $posts_page_id );

                // Arrange category parent to child
                $category_values = array_values( $category );
                $get_last_category = end( $category_values );
                // $get_last_category = $category[count($category) - 1];
                $get_parent_category = rtrim( get_category_parents( $get_last_category->term_id, true, ',' ), ',' );
                $cat_parent = explode( ',', $get_parent_category );

                // store prarent categories
                $store_parent_cats = '';

                $loop_count = 0;

                foreach ( $cat_parent as $p ) {

                    // extract url and title from $p

                    $has_matches = preg_match( '/href=["\']?([^"\'>]+)["\']?/', $p, $match );
                    $info = parse_url( $match[ 1 ] ); // returning array of scheme, host  & path
                    $url = $info[ "scheme" ] . "://" . $info[ "host" ] . $info[ "path" ];

                    $expl = explode( '</', $p );
                    $expl = explode( '>', $expl[ 0 ] );
                    $title = isset( $expl[ 1 ] ) ? $expl[ 1 ] : '';

                    // check if url is duplicate of blog page url when using %category% for blog permalink
                    // MY_WEBPAGE_HOME_URL/category/MY_BLOG_HOME_FOLDER/
                    $category_str = '/category/';
                    if ( strpos( $url, $category_str ) !== false ) {
                        $url_split = explode( $category_str, $url );
                        if ( $posts_page_url === $home_page_url . $url_split[ 1 ] ) {
                            // change url to blog home url (having equal contents but shorter url)
                            $url = $posts_page_url;
                        }
                    }

                    $store_parent_cats .=  $this->add_item( $title, $url );

                    $loop_count++;
                }

            }

            // ifcustom post type within custom taxonomy
            $taxonomy_exists = taxonomy_exists( $custom_taxonomy );

            if ( empty( $get_last_category ) && ! empty( $custom_taxonomy ) && $taxonomy_exists ) {
                // echo ' (CUST TAX) ';

                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id = $taxonomy_terms[ 0 ]->term_id;
                $cat_link = get_term_link($taxonomy_terms[ 0 ]->term_id, $custom_taxonomy);
                $cat_name = $taxonomy_terms[ 0 ]->name;

            }

            // Check if the post is in a category
            if ( ! empty( $get_last_category ) ) {
                // echo ' (GET LAST CAT) ';

                $html .= $store_parent_cats;
                $html .= $this->add_item( get_the_title() );
            } 
            elseif( ! empty( $cat_id ) ) {
                // echo ' (CAT ID) ';

                $html .= $this->add_item( $cat_name, $cat_link );
                $html .= $this->add_item( get_the_title() );
            }
            else {
                // echo ' (ELSE CAT ID) ';

                $html .= $this->add_item( get_the_title() );
            }

        } 
        elseif ( is_archive() ) {
            // echo ' (ARCH) ';

            if ( is_tax() ) {
                // if post type is not post
                if ( $post_type != 'post' ) {
                    $post_type_object = get_post_type_object( $post_type );
                    $post_type_link = get_post_type_archive_link( $post_type );

                    if ( isset( $post_type_object->labels ) && isset( $post_type_object->labels->name ) ) {
                        $html .= $this->add_item( $post_type_object->labels->name, $post_type_link, 'archive-tax' );
                    }
                    else {
                        $html .= '<!-- archive object empty -->';
                    }

                }
                $custom_tax_name = get_queried_object()->name;
                $html .= $this->add_item( $custom_tax_name );
            } 
            elseif ( is_category() ) {
                // echo ' (CAT) ';

                $parent = get_queried_object()->category_parent;

                if ( $parent !== 0 ) {
                    $parent_category = get_category( $parent );
                    $category_link   = get_category_link( $parent );
                    $html .= $this->add_item( $parent_category->name, esc_url( $category_link ) );
                }
                $html .= $this->add_item( single_cat_title( '', false ) );
            } 
            elseif ( is_tag() ) {
                // echo ' (TAG) ';

                // get tag information
                $term_id = get_query_var('tag_id');
                $taxonomy = 'post_tag';
                $args = 'include=' . $term_id;
                $terms = get_terms( $taxonomy, $args );
                $get_term_name = $terms[ 0 ]->name;

                $html .= $this->add_item( $get_term_name );
            } 
            elseif ( is_day() ) {
                // echo ' (DAY) ';

                // year
                $html .= $this->add_item( get_the_time('Y'), get_year_link( get_the_time('Y') ) );
                // month
                $html .= $this->add_item( get_the_time('M'), get_month_link( get_the_time('Y') ) );
                // day
                $html .= $this->add_item( get_the_time('jS') .' '. get_the_time('M') );
            } 
            elseif ( is_month() ) {
                // echo ' (MONTH) ';

                // year
                $html .= $this->add_item( get_the_time('Y'), get_year_link( get_the_time('Y') ) );

                // month
                $html .= $this->add_item( get_the_time('M') );
            } 
            elseif ( is_year() ) {
                // echo ' (YEAR) ';

                // year
                $html .= $this->add_item( get_the_time('Y') );
            } 
            elseif ( is_author() ) {
                // echo ' (AUTHOR) ';

                // auhor

                // get author information
                global $author;
                $userdata = get_userdata( $author );

                $html .= $this->add_item( $userdata->display_name );
            } 
            else {
                $html .= $this->add_item( post_type_archive_title() );
            }

        } 
        elseif ( is_page() ) {
            // echo ' (PAGE) ';

            // parents
            if ( $post->post_parent ) {

                // ff child page, get parents
                $anc = get_post_ancestors( $post->ID );

                // get parents in right order
                $anc = array_reverse( $anc );

                // parents loop
                foreach ( $anc as $ancestor ) {
                    $html .= $this->add_item( get_the_title( $ancestor ), get_permalink( $ancestor ) );
                }

            }

            // current page
            $html .= $this->add_item( get_the_title() );
        } 
        elseif ( is_search() ) {
            // echo ' (SEARCH) ';

            // $html .= $this->add_item( __( 'Search', 'bsx-wordpress' ) . ': ' . get_search_query() );
            // do not show query string since already shown below
            $html .= $this->add_item( __( 'Search', 'bsx-wordpress' ) );
        } 
        elseif ( is_404() ) {
            // echo ' (404) ';

            $html .= $this->add_item( __( 'Error 404', 'bsx-wordpress' ) );
        }
        else if ( get_post_type( $post ) === 'post' ) {
            // echo ' (IS POST PAGE) ';

            $posts_page_id = get_option( 'page_for_posts' );
            $html .= $this->add_item( get_the_title( $posts_page_id ) );
        }
        else {
            // echo ' (ELSE – NOTHING) ';
        }

        // end list
        $html .= $this::end_list();

        return $html;
    }

    public function print() {

        $html = $this->makeHtml();

        echo $html;
    }
}
// /class Bsx_Breadcrumb


/**
 * breadcrumb shortcode
 */

// creates breadcrumb [breadcrumb]
// TODO: add config styled=true
function add_bsx_breadcrumb_shortcode() {
    $content = 'Missing method: Bsx_Breadcrumb->makeHtml()';
    if ( class_exists( 'Bsx_Breadcrumb' ) && method_exists( 'Bsx_Breadcrumb', 'makeHtml' ) ) {
        $content = ( new Bsx_Breadcrumb )->makeHtml();
    }
    return $content;
}
add_shortcode( 'breadcrumb', 'add_bsx_breadcrumb_shortcode' );


/**
 * include mail form
 */

require_once( __DIR__ . '/src/libs/form/class-bsx-mail-form.php' );
if ( class_exists( 'Bsx_Mail_Form' ) && method_exists( 'Bsx_Mail_Form' , 'init' ) ) {
    ( new Bsx_Mail_Form() )->init();
}


/**
 * references custom post
 */
$file = dirname( __FILE__ ).'/inc/references/custom-post-type.php';
if ( file_exists( $file ) ) {
    require $file;
}
/**
 * references meta box
 */
$file = dirname( __FILE__ ) . '/inc/references/meta-box.php';
if ( file_exists( $file ) ) {
    require $file;
    ( new ReferencesMeta() )->init();
}
/**
 * references shortcode
 */
$file = dirname( __FILE__ ) . '/inc/references/shortcode.php';
if ( file_exists( $file ) ) {
    require $file;
    ( new ReferencesShortcode() )->init();
}


/**
 * tutorials
 */
// custom post type
$file = dirname( __FILE__ ).'/inc/tutorials/custom-post-type.php';
if ( file_exists( $file ) ) {
    require $file;
}
// shortcode
$file = dirname( __FILE__ ) . '/inc/tutorials/shortcode.php';
if ( file_exists( $file ) ) {
    require $file;
    ( new TutorialsShortcode() )->init();
}
// // tax meta box
// $file = dirname( __FILE__ ) . '/inc/tutorials/taxonomy-meta-box.php';
// if ( file_exists( $file ) ) {
//     require $file;
//     ( new Tutorials_Tax_Meta )->init();
// }
// // taxonomy
// $file = dirname( __FILE__ ) . '/inc/tutorials/taxonomy.php';
// if ( file_exists( $file ) ) {
//     require $file;
//     ( new Tutorials_Taxonomy )->init();
// }


