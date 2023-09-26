<?php 
	get_header(); 

	// increment view counter
	countPostViews( get_the_ID() );
?>

<div data-id="single">

    <div class="container-fluid bg-light pt-4 mb-lg-4">
    	<div class="text-column text-center mt-5 pt-2 pb-3">
	        <?php
	            // breadcrumb
	        	if ( class_exists( 'Bsx_Breadcrumb' ) && method_exists( 'Bsx_Breadcrumb', 'print' ) ) {
                	( new Bsx_Breadcrumb )->print();
	        	}
	        ?>
	    </div>
    </div>

	<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content/content-single', get_post_format() );

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; endif;
	?>

    <?php

        // popular posts
        get_template_part( 'template-parts/banner/popular-posts' );

        // banner
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

            if ( $banner_type === 'blog' ) {
                // remove Gutenberg block comments
                $post_content = preg_replace( '/<!--(.|s)*?-->/', '', get_the_content() );
                echo $post_content;
            }

        endwhile;

    ?>

</div>
<!-- /[data-id="single"] -->

<?php get_footer(); ?>