<?php

if ( isset( $args[ 'custom_query' ] ) ) {
    $custom_query = $args[ 'custom_query' ];
}
else {
    $custom_query = [];
}



if ( ( ! empty( $custom_query ) && $custom_query->have_posts() ) ) :

    $data = ( isset( $args[ 'data' ] ) ) ? $args[ 'data' ] : [];
    $variant = ( isset( $data[ 'variant' ] ) ) ? intval( $data[ 'variant' ] ) : '';

?>
    <div class="owl-carousel owl-theme logo-list-item has-grayscale-color-img outer-nav nav-circle<?php if ( empty( $variant ) || $variant == 1 ) : echo ' bsx-slider-fadeout'; endif ?> mb-0" data-fn="owl-carousel" data-fn-options="{ navClass: [ 'btn btn-outline-primary is-prev', 'btn btn-outline-primary is-next' ], lazyLoad: true, responsive: { 0: { items: 1 }, 480: { items: 2 }, 768: { items: 3 }, 992: { items: 4 } }, encodeUriNavText: [ '%3Ci%20class=%22fa%20fa-chevron-left%22%20aria-label=%22<?php echo urlencode( __( 'Previous', 'bsx-wordpress' ) ); ?>%22%3E%3C/i%3E', '%3Ci%20class=%22fa%20fa-chevron-right%22%20aria-label=%22<?php echo urlencode( __( 'Next', 'bsx-wordpress' ) ) ; ?>%22%3E%3C/i%3E' ], autoplayTimeout: 5500, autoplaySpeed: 1200, dots: false }"<?php if ( ! empty( $variant ) ) : echo ' data-variant="' . $variant . '"'; endif ?>>
        <?php

            while ( $custom_query->have_posts() ) : 

                $custom_query->the_post();
                $post_id = get_the_ID();

                $meta = get_post_meta( $post_id, 'references', true );
                $link = isset( $meta[ 'link' ] ) ? $meta[ 'link' ] : '';

                // load lazy
                $attachment_id = get_post_thumbnail_id( $post_id ); 
                $alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
                $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'middle' ); // returns array( $url, $width, $height )

                // echo '<div class="mb-4">' . get_the_title() . '<br>' . $link . '</div>';

                ?>
                    <div class="item" data-post-id="<?php echo $post_id; ?>">
                        <a class="logo-list-inner d-block text-center<?php if ( $variant == 2 ) : echo ' bg-white py-3'; endif ?>" href="<?php echo $link; ?>" target="_blank" rel="noreferrer noopener nofollow" title="<?php the_title(); ?>">
                            <div class="limited-logo-md">
                                <?php
                                    if ( $image_attributes ) {
                                        $img_data = array(
                                            'img' => array(
                                                'url' => $image_attributes[ 0 ],
                                                'width' => $image_attributes[ 1 ],
                                                'height' => $image_attributes[ 2 ],
                                                'alt' => $alt,
                                                'class_name' => 'logo-list-img owl-lazy'
                                            ),
                                            'figure' => array(
                                                'class_name' => 'mb-0'
                                            )
                                        );

                                        if ( class_exists( 'LazyImg' ) && method_exists( 'LazyImg', 'print' ) ) {
                                            ( new LazyImg( $img_data ) )->print();
                                        }
                                    }
                                ?>
                            </div>
                        </a>
                    </div>

                <?php

            endwhile;

            wp_reset_postdata();

        ?>
    </div>
<?php 

else :

    echo '<!-- ' . esc_html__( 'No date available', 'bsx-wordpress' ) . ' -->';

endif;

?>


