<section class="col-md-6 col-lg-4 d-flex mb-5" data-id="content">
    <a class="text-inherit no-underline img-hover-zoom-in" href="<?php the_permalink(); ?>">

        <?php
            // load lazy
            $attachment_id = get_post_thumbnail_id( $post ); 
            $alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
            $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'middle' ); // returns array( $url, $width, $height )

            if ( $image_attributes ) {
                $img_data = array(
                    'img' => array(
                        'url' => $image_attributes[ 0 ],
                        'width' => $image_attributes[ 1 ],
                        'height' => $image_attributes[ 2 ],
                        'alt' => $alt
                    ),
                    'figure' => array(
                        'class_name' => 'of-hidden'
                    )
                );

                if ( class_exists( 'LazyImg' ) && method_exists( 'LazyImg', 'print' ) ) {
                    ( new LazyImg( $img_data ) )->print();
                }
            }

        ?>

        <div class="small text-muted text-uppercase mb-2">
        <?php
            // categories
            $categories = wp_get_post_categories( $post->ID );
            if ( count( $categories ) > 0 ) : 
                foreach ( $categories as $cat_obj ) {
                    $cat = get_category( $cat_obj );
                    // $cat_url = get_category_link( get_cat_ID( $cat->name ) );
                    ?>
                        <span class=""><?php echo $cat->name; ?></span>
                    <?php
                } 
            endif;

            // date
            if ( get_the_date() ) : ?>
                <span class="">– <?php echo get_the_date(); ?></span>
            <?php endif;

        ?>
        </div>

        <h3 class="lead font-weight-normal mb-0"><?php the_title(); ?></h3>
    </a>
</section>
