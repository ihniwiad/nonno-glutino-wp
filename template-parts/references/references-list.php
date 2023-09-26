<?php

// check if polylang plugin available
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


if ( isset( $args[ 'custom_query' ] ) ) {
    $custom_query = $args[ 'custom_query' ];
}
else {
    $custom_query = [];
}



if ( ( ! empty( $custom_query ) && $custom_query->have_posts() ) ) :

    // print_r( $args );
    // print_r( $args[ 'data' ] );
    $filter_active = isset( $args[ 'data' ] ) && isset( $args[ 'data' ][ 'filter' ] ) && $args[ 'data' ][ 'filter' ];
    $filter_parent_cat_found = false;

    if ( $filter_active ) {
        // use filter

        $tagfilter_id = ( isset( $args[ 'data' ][ 'filter-id' ] ) && $args[ 'data' ][ 'filter-id' ] ) ? 'ref-' . $args[ 'data' ][ 'filter-id' ] : 'ref'; // avoid duplication on page

        if ( isset( $args[ 'data' ][ 'cat-id' ] ) && $args[ 'data' ][ 'cat-id' ] ) {
            // use faq cat id from shortcode
            $ref_cat_id = $args[ 'data' ][ 'cat-id' ];
            $filter_parent_cat_found = true;
        }
        else {
            // get references cat id by searching for parent cat slug 'ref-parent'
            $ref_cat_id_obj = get_category_by_slug( 'ref-parent' );
            if ( $ref_cat_id_obj instanceof WP_Term ) {
                $ref_cat_id = $ref_cat_id_obj->term_id;
                $filter_parent_cat_found = true;
            }
            else {
                echo '<!-- filter activated but missing filter parent category -->';
            }
        }

        // echo '<div>' . $ref_cat_id . '</div><hr>';

        if ( $filter_parent_cat_found ) {

            // get children categories to group faq
            $ref_child_cat_ids = get_term_children( $ref_cat_id, 'category' );

            ?>
                <div class="line-label mb-1">
                    <span><?php echo esc_html__( 'Filter', 'bsx-wordpress' ) ?></span>
                </div>

                <form class="bsx-tgf-form text-center" data-bsx="tgf" data-tgf-conf="{ bsxTarget: 'tgf-tar-<?php echo $tagfilter_id; ?>' }">

                    <ul class="list-inline">
                        <?php
                            $polylang_is_active = is_plugin_active( 'polylang/polylang.php' );

                            foreach( $ref_child_cat_ids as $key => $ref_child_cat_id ) {

                                $cat_obj = get_term_by( 'id', $ref_child_cat_id, 'category' );

                                // default lang cat name
                                $cat_name = $cat_obj->name;

                                // get lang, translate name
                                if ( $polylang_is_active ) {
                                    $default_lang = pll_default_language();
                                    $current_lang = pll_current_language();

                                    if ( $current_lang != $default_lang ) {
                                        // translate only name, keep slugs for filtering unchanged

                                        // pll_get_term( $term_id, $slug );  // $slug: polylang lang slug, e.g. `en`
                                        // read more here: https://polylang.pro/doc/function-reference/#pll_get_term
                                        $translated_ref_child_cat_id = pll_get_term( $ref_child_cat_id, $current_lang );

                                        $translated_cat_obj = get_term_by( 'id', $translated_ref_child_cat_id, 'category' );
                                        $cat_name = $translated_cat_obj->name;
                                    }
                                }

                                ?><li class="list-inline-item" data-cat-id="<?php echo $ref_child_cat_id; ?>">
                                    <input class="bsx-tgf-trigger" id="tgf-<?php echo $ref_child_cat_id . '-' . $key; ?>" type="checkbox" value="<?php echo $cat_obj->slug; ?>" data-tgf-tri><label class="bsx-tgf-label" for="tgf-<?php echo $ref_child_cat_id . '-' . $key; ?>"><?php echo $cat_name; ?></label>
                                </li><?php

                            }
                        ?><li class="list-inline-item"><input class="bsx-tgf-reset btn btn-primary" type="reset" value="<?php echo esc_html__( 'Reset', 'bsx-wordpress' ) ?>"></li><!--
                        --><li class="list-inline-item"><input class="bsx-tgf-submit" type="submit" value="<?php echo esc_html__( 'Filter', 'bsx-wordpress' ) ?>"></li>
                    </ul>

                </form>

                <hr class="mb-4">

            <?php

        } // if $filter_parent_cat_found

    } // if $filter_active

?>
    <div class="row justify-content-around"<?php if ( $filter_active && $filter_parent_cat_found ) { echo ' data-bsx="tgf-tar-' . $tagfilter_id . '"'; } ?>>
        <?php

            while ( $custom_query->have_posts() ) : 

                    $custom_query->the_post();

                if ( get_the_content() ) :

                    $post_id = get_the_ID();
                    $cats = get_the_category();

                    // print_r($cats);

                    // get only 1st cat
                    // $cat = $cats[ 0 ];
                    // $cat_slug = $cat->slug;

                    // allow multiple cats
                    $cat_slugs_array = array();

                    foreach ( $cats as $cat ) {
                        $cat_slugs_array[] = $cat->slug;
                    }
                    // make space-separated list of cat slugs
                    $cat_slugs = implode( ' ', $cat_slugs_array );

                    $meta = get_post_meta( $post_id, 'references', true );
                    $name = isset( $meta[ 'name' ] ) ? $meta[ 'name' ] : '';
                    $link = isset( $meta[ 'link' ] ) ? $meta[ 'link' ] : '';
                    $job_title = isset( $meta[ 'job_title' ] ) ? $meta[ 'job_title' ] : '';

                    // get post thumbnail (logo)
                    $attachment_id = get_post_thumbnail_id( $post_id ); 
                    $alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
                    $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'middle' ); // returns array( $url, $width, $height )

                    // get additional (custom) post image (portrait)
                    $additional_attachment_id = isset( $meta[ 'image' ] ) ? $meta[ 'image' ] : '';
                    $additional_alt = get_post_meta( $additional_attachment_id, '_wp_attachment_image_alt', true );
                    $additional_image_attributes = wp_get_attachment_image_src( $additional_attachment_id, 'thumbnail' ); // returns array( $url, $width, $height )

                    ?>
                        <div class="col-md-6 mb-4<?php if ( $filter_active && $filter_parent_cat_found ) { echo ' bsx-tgf-target-item is-grayscale'; } ?>"<?php if ( $filter_active && $filter_parent_cat_found ) { echo ' data-tgf-id="' . $cat_slugs . '"'; } ?> data-post-id="<?php echo $post_id; ?>">
                            <div class="mb-4 pt-4 pb-2 px-3 bg-info text-dark rounded speech-bubble speech-bubble-sm">
                                <div class="text-italic"><?php the_content(); ?></div>
                            </div>
                            <div class="">
                                <?php /* if ( $additional_image_attributes ) : ?>
                                    <div class="col-2 mb-3">
                                        <?php
                                            $img_data = array(
                                                'img' => array(
                                                    'url' => $additional_image_attributes[ 0 ],
                                                    'width' => ( $additional_image_attributes[ 1 ] > 70 ) ? 70 : $additional_image_attributes[ 1 ],
                                                    'height' => ( $additional_image_attributes[ 2 ] > 70 ) ? 70 : $additional_image_attributes[ 2 ],
                                                    'alt' => $additional_alt,
                                                        'class_name' => 'img-fluid rounded-circle',
                                                ),
                                                'figure' => array(
                                                )
                                            );

                                            if ( class_exists( 'LazyImg' ) && method_exists( 'LazyImg', 'print' ) ) {
                                                ( new LazyImg( $img_data ) )->print();
                                            }
                                        ?>
                                    </div>
                                <?php endif; */ ?>
                                <div class="mb-3">
                                    <?php if ( $image_attributes ) : ?>
                                        <div class="">
                                            <a href="<?php echo $link; ?>" target="_blank" rel="noreferrer noopener nofollow" title="<?php the_title(); ?>">
                                                <?php
                                                    $img_data = array(
                                                        'img' => array(
                                                            'url' => $image_attributes[ 0 ],
                                                            'width' => $image_attributes[ 1 ],
                                                            'height' => $image_attributes[ 2 ],
                                                            'alt' => $alt,
                                                        ),
                                                        'figure' => array(
                                                            'class_name' => 'text-center limited-logo-sm w-auto of-hidden align-middle mb-0'
                                                        )
                                                    );

                                                    if ( class_exists( 'LazyImg' ) && method_exists( 'LazyImg', 'print' ) ) {
                                                        ( new LazyImg( $img_data ) )->print();
                                                    }
                                                ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <p class="mb-0"><strong><?php echo get_the_title(); ?></strong></p>
                                    <!-- p class="small"><?php echo $job_title; echo ( $job_title ) ? ', ': ''; echo get_the_title(); ?></p -->
                                </div>
                            </div>
                        </div>
                    <?php

                endif;

            endwhile;

            wp_reset_postdata();

        ?>
    </div>
<?php 

else :

    echo '<!-- ' . esc_html__( 'No date available', 'bsx-wordpress' ) . ' -->';

endif;

?>


