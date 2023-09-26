<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 */

get_header();

?>
<div data-id="search">
  <main class="container below-navbar-content" id="main">

    <div class="mb-4">
        <?php
            // breadcrumb
            if ( class_exists( 'Bsx_Breadcrumb' ) && method_exists( 'Bsx_Breadcrumb', 'print' ) ) {
                ( new Bsx_Breadcrumb )->print();
            }
        ?>
    </div>

    <div class="mb-4">
      <?php get_search_form(); ?>
    </div>

    <header class="">
      <h1 class="">
        <?php
        printf(
          /* translators: %s: search term. */
          esc_html__( 'Results for "%s"', 'bsx-wordpress' ),
          '<span class="page-description search-term">' . esc_html( get_search_query() ) . '</span>'
        );
        ?>
      </h1>
    </header><!-- .page-header -->

    <div class="mb-5">
      <?php
      printf(
        esc_html(
          /* translators: %d: the number of search results. */
          _n(
            'We found %d result for your search.',
            'We found %d results for your search.',
            (int) $wp_query->found_posts,
            'bsx-wordpress'
          )
        ),
        (int) $wp_query->found_posts
      );
      ?>
    </div>

    <?php

      if ( have_posts() ) {
        // Start the Loop.

        ?>
          <div class="row">
            <?php
              while ( have_posts() ) {
                the_post();

                /*
                 * Include the Post-Format-specific template for the content.
                 * If you want to override this in a child theme, then include a file
                 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                 */
                // get_template_part( 'template-parts/content/content-excerpt', get_post_format() );
                get_template_part( 'template-parts/content/content', get_post_format() );

              } // End the loop.
            ?>
          </div><!-- /.row -->
        <?php

        // pagination
        get_template_part( 'template-parts/pagination/search-pagination' );

        // Previous/next page navigation.
        // twenty_twenty_one_the_posts_navigation();

        // If no content, include the "No posts found" template.
      } 
      else {
        get_template_part( 'template-parts/content/content-none' );
      }
    ?>
  </main>

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

</div><!-- /[data-id="search"] -->
<?php

get_footer();
