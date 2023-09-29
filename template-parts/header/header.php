<?php 
global $phoneHrefRemovePatterns;

// check if polylang plugin available
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
?>
<!-- bsx-appnav-navbar-scroll-toggle -->
<header class="bsx-appnav-navbar bsx-appnav-fixed-top bsx-appnav-navbar-scroll-toggle" data-fn="anchor-offset-elem" data-tg="sticky-container-below">

    <?php
        // add spacer .bsx-lang-toggle-spacer if not having lang nav or login
    ?>
    <nav class="bsx-appnav-navbar-container container pl-0 pl-md-3 pr-5 pr-md-3">

        <button class="bsx-appnav-navbar-toggler px-3" id="toggle-navbar-collapse" type="button" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation" data-fn="toggle" data-fn-options="{ bodyOpenedClass: 'nav-open' }" data-fn-target="[data-tg='navbar-collapse']" data-tg="dropdown-multilevel-excluded">
            <span class="sr-only"><?php echo __( 'Menu', 'bsx-wordpress' ); ?></span>
            <i class="fa fa-navicon" aria-hidden="true"></i>
        </button>

        <a class="bsx-appnav-navbar-brand" href="<?php echo get_bloginfo( 'url' ) . '/'; ?>">
            <!-- inline svg logo -->
            <?php 
                $logo = file_get_contents( $logoPath );
                echo $logo;
            ?>
        </a>

        <div class="bsx-appnav-navbar-collapse" id="navbarNavDropdown" data-tg="navbar-collapse">

            <?php
                // true will show configured menu, false will list all pages as menu
                $use_menu = true;

                if ( $use_menu ) :
                    // use menu
                    echo '<!-- Primary Menu: Bsx_Walker_Nav_Menu -->';

                    wp_nav_menu( 
                        array( 
                            'theme_location' => 'primary-menu',
                            'walker' => new Bsx_Walker_Nav_Menu(),
                            'menu' => '',
                            'container' => '',
                            'items_wrap' => '<ul id="%1$s" class="%2$s" aria-labelledby="toggle-navbar-collapse">%3$s</ul>',
                            'menu_class' => 'bsx-appnav-navbar-nav bsx-main-navbar-nav',
                            'menu_id' => '',
                            'before' => '', // in <li> before <a>
                            'after' => '', // in <li> after <a>
                            'link_before' => '', // in <a> before text
                            'link_after' => '', // in <a> after text
                            'create_clickable_parent_link_child' => false,
                        ) 
                    ); 
                else :
                    // use page list instead of menu
                    ?>
                        <ul class="bsx-appnav-navbar-nav bsx-main-navbar-nav" aria-labelledby="toggle-navbar-collapse">
                            <?php 
                                echo '<!-- Primary Menu: Bsx_Walker_Page -->';
                                wp_list_pages(
                                    array(
                                        'match_menu_classes' => true,
                                        'show_sub_menu_icons' => true,
                                        'title_li' => false,
                                        'walker'   => new Bsx_Walker_Page(),
                                    )
                                );
                            ?>
                        </ul>

                    <?php 
                endif;
            ?>

        </div>

        <div class="bsx-appnav-collapse-backdrop" data-fn="remote-event" data-fn-options="{ target: '#toggle-navbar-collapse' }" data-tg="dropdown-multilevel-excluded"></div>

        <ul class="bsx-appnav-navbar-nav bsx-icon-navbar-nav bsx-allmedia-dropdown-nav">
            <?php
                // language switcher

                if ( is_plugin_active( 'polylang/polylang.php' ) ) {
                    ?>
                        <li class="">
                            <a class="bsx-appnav-dropdown-toggle" id="lang-switcher" href="javascript:void( 0 );" data-fn="dropdown-multilevel" aria-haspopup="true" aria-controls="lang-switcher-list" aria-expanded="false"><span class="d-none d-sm-inline pr-1"><i class="fa fa-globe w-auto" aria-hidden="true"></i></span><span><?php echo strtoupper( pll_current_language() ); ?></span></a>
                            <?php
                                wp_nav_menu( 
                                    array( 
                                        'theme_location' => 'language-switcher',
                                        'menu' => '',
                                        'container' => '',
                                        'items_wrap' => '<ul id="lang-switcher-list" class="%2$s" aria-labelledby="lang-switcher">%3$s</ul>',
                                        'menu_class' => 'bsx-lang-nav ul-right',
                                        'menu_id' => '',
                                        'add_current_li_class' => 'active',
                                    ) 
                                ); 
                            ?>
                        </li>
                    <?php
                } 
                else {
                    echo '<!-- no language switcher shown sice multilingual plugin not available -->';
                }
            ?>
        </ul>

    </nav>

</header>


