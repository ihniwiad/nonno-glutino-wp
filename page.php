<?php get_header(); ?>

<main id="main" data-id="page">

	<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content/content-page', get_post_format() );

		endwhile; endif;


        // banner

        $meta = get_post_meta( get_the_ID(), 'page_style', true );
        $not_show_bottom_banner = isset( $meta[ 'not_show_bottom_banner' ] ) && $meta[ 'not_show_bottom_banner' ];

        if ( ! $not_show_bottom_banner ) {

            $args = array(
                'post_type' => 'banner-custom-post',
                'posts_per_page' => -1,
                'order' => 'DESC'
            );

            $custom_query = new WP_Query( $args );

            while ( $custom_query->have_posts() ) : 

                $custom_query->the_post();

                $post_id = get_the_ID();
                $banner_type = '';
                $meta = get_post_meta( $post_id, 'banner', true );
                if ( isset( $meta ) && isset( $meta[ 'banner_type' ] ) ) {
                    $banner_type = $meta[ 'banner_type' ];
                }

                if ( $banner_type === 'page' ) {
                    echo get_the_content();
                }

            endwhile;

        }
	?>

</main>

<?php get_footer(); ?>