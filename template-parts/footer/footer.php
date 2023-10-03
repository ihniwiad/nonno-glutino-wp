<?php 
global $phoneHrefRemovePatterns;
?>  

<!-- FOOTER -->

<footer class="page-footer" data-tg="sticky-container-above">

    <hr class="mt-0">

    <div class="container">

        <div class="text-center mt-5 pb-3">
            <a class="footer-logo-wrapper" href="<?php echo ( is_front_page() ) ? '#top' : get_bloginfo( 'url' ) . '/'; ?>">
                <!-- inline svg logo -->
                <?php 
                    $claimLogoPath = str_replace( 'logo.svg', 'logo-claim.svg', $logoPath );
                    $logo = file_get_contents( $claimLogoPath );
                    echo $logo;
                ?>
            </a>
        </div>

        <div class="row">
            <?php
                // make n cols
                $cols_count = 4;

                $conf_cols_count = get_option( 'footer_columns_count' );
                if ( isset( $conf_cols_count ) && $conf_cols_count != '' ) {
                    $cols_count = number_format( $conf_cols_count );
                }

                $col_class_names = 'col-12 col-sm-6 col-md';
                switch ( $cols_count ) {
                    case 4:
                        $col_class_names = 'col-6 col-md-3';
                        break;
                    case 3:
                        $col_class_names = 'col-12 col-md-4';
                        break;
                }

                for( $i = 0; $i < $cols_count; $i++ ) {
                    ?>
                        <div class="<?php echo $col_class_names; ?>">
                            <?php 
                                // check if show menu name
                                if ( get_option( 'footer_menu_names_show' ) ) : 
                            ?>
                            <div>
                                <?php
                                    $menu_name = wp_get_nav_menu_name( 'footer-column-' . ( $i + 1 ) . '-menu' );
                                    echo '<!-- Footer Column 1 Menu (' . $menu_name . ') -->';
                                ?>
                                <strong><?php echo $menu_name; ?></strong>
                            </div>
                            <hr class="my-1">
                            <?php
                                endif;

                                wp_nav_menu( 
                                    array( 
                                        'theme_location' => 'footer-column-' . ( $i + 1 ) . '-menu',
                                        'menu' => '',
                                        'container' => '',
                                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                        'menu_class' => 'bsx-footer-col-nav list-unstyled',
                                        'menu_id' => '',
                                        'add_li_class' => '', // custom filter add_additional_class_on_li(), see functions.php 
                                        'add_a_class' => 'footer-link' // custom filteradd_additional_class_on_a(), see functions.php 
                                    ) 
                                ); 
                            ?>
                        </div>
                    <?php
                }
            ?>

        </div>

        <div class="text-center pt-3 pb-3">
            <ul class="list-inline mb-0">

                <?php

                        $social_media_list = array(
                            array( 'id' => 'facebook', 'title' => __( 'Facebook', 'bsx-wordpress' ), 'icon' => 'facebook' ),
                            array( 'id' => 'twitter', 'title' => __( 'X Twitter', 'bsx-wordpress' ), 'icon' => 'twitter' ),
                            array( 'id' => 'instagram', 'title' => __( 'Instagram', 'bsx-wordpress' ), 'icon' => 'instagram' ),
                            array( 'id' => 'googleplus', 'title' => __( 'Google Plus', 'bsx-wordpress' ), 'icon' => 'google-plus' ),
                            array( 'id' => 'xing', 'title' => __( 'Xing', 'bsx-wordpress' ), 'icon' => 'xing' ),
                            array( 'id' => 'linkedin', 'title' => __( 'LinkedIn', 'bsx-wordpress' ), 'icon' => 'linkedin-in' ),
                        );

                        function printIconLinkItem( $icon, $icon_type = '', $href, $title, $hover_class_id = '', $link_atts = '' ) {

                            // hover color class name if configured in theme options
                            $hover_class_name = '';
                            if ( get_option( 'social_media_colors_use' ) && $hover_class_id ) {
                                $hover_class_name = 'hover-text-' . $hover_class_id;
                            }

                            ?>
                                <li class="list-inline-item mx-0">
                                    <a class="fa-stack fa-lg<?php if ( $hover_class_name ) : echo ' ' . $hover_class_name; endif ?>"<?php if ( $href ) : echo ' href="' . $href . '"'; endif ?><?php if ( $link_atts ) : echo ' ' . $link_atts ; endif ?>>
                                        <i class="fa fa-circle fa-stack-2x" aria-hidden="true"></i>
                                        <i class="fa<?php if ( ! empty( $icon_type ) ) : echo $icon_type; else : echo 's'; endif; ?> fa-<?php echo $icon; ?> fa-stack-1x text-dark hover-text-inverse" aria-hidden="true"></i>
                                        <span class="sr-only"><?php echo $title; ?></span>
                                    </a>
                                </li>
                            <?php
                        }

                        foreach( $social_media_list as $item ) {
                            $social_media_href = get_option( $item[ 'id' ] );
                            if ( $social_media_href ) {
                                printIconLinkItem( $item[ 'icon' ], 'b', $social_media_href, $item[ 'title' ], $item[ 'id' ], 'target="_blank" rel="nofollow"' );
                            }
                        }

                        $footer_phone_mail_show = get_option( 'footer_phone_mail_show' );
                        $phone = get_option( 'phone' );
                        $mail = get_option( 'mail' );
                    
                        if ( $footer_phone_mail_show ) {
                            if ( $phone ) {
                                // remove unwanted chars
                                $phoneHref = $phone;
                                $patterns = $phoneHrefRemovePatterns;
                                foreach ( $patterns as $pattern ) {
                                    $phoneHref = preg_replace( $pattern, '', $phoneHref );
                                }
                                $phoneHref = 'tel:' . $phoneHref;
                                printIconLinkItem( 'phone-alt', '', $phoneHref, __( 'Phone', 'bsx-wordpress' ), 'dark' );
                            }
                            if ( $mail ) {
                                // make attribute from mail address
                                $atPos = strpos( $mail, "@" );
                                $dotPos = strpos( $mail, "." );

                                $name = substr( $mail, 0, $atPos );
                                $domain = substr( $mail, $atPos + 1, $dotPos - $atPos - 1 );
                                $extension = substr( $mail, $dotPos + 1 );

                                $link_attr = 'data-fn="create-mt" data-mt-n="' . $name . '" data-mt-d="' . $domain .'" data-mt-s="' . $extension . '"';

                                printIconLinkItem( 'envelope', '', '', __( 'Email', 'bsx-wordpress' ), 'dark', $link_attr );
                            }
                        } 
                ?>

            </ul>
        </div>

        <div class="text-center">
            <?php
                echo '<!-- Footer Bottom Menu -->';
                wp_nav_menu( 
                    array( 
                        'theme_location' => 'footer-bottom-menu',
                        'menu' => '',
                        'container' => '',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'menu_class' => 'bsx-footer-bottom-nav list-unstyled',
                        'menu_id' => '',
                        'add_li_class' => 'footer-bottom-menu-li', // custom filter add_additional_class_on_li(), see functions.php 
                        'add_a_class' => 'footer-link' // custom filteradd_additional_class_on_a(), see functions.php 
                    ) 
                ); 
            ?>
        </div>

        <hr class="border-primary mt-1">

        <div class="text-center small mt-3 mb-2">
            &copy; Copyright <?php echo date_format( date_create(), 'Y' ); ?> <a class="footer-link" href="<?php echo get_bloginfo( 'url' ) . '/'; ?>"><?php echo ( get_option( 'owner-name' ) ) ? get_option( 'owner-name' ) : get_bloginfo( 'name' ); ?></a>
        </div>

    </div>
    
</footer>