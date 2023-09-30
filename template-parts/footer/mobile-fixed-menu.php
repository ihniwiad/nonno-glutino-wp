<div class="d-md-none position-sticky bg-info header-shadow bottom-0">
  <?php
    echo '<!-- Mobile Fixed Menu -->';
    wp_nav_menu( 
      array( 
        'theme_location' => 'mobile-fixed-menu',
        'menu' => '',
        'container' => '',
        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'menu_class' => 'bsx-mobile-fixed-nav row no-gutters list-unstyled mb-0',
        'menu_id' => '',
        'add_li_class' => 'bsx-mobile-fixed-nav-li col', // custom filter add_additional_class_on_li(), see functions.php 
        'add_a_class' => 'bsx-mobile-fixed-nav-a text-secondary text-center', // custom filter add_additional_class_on_a(), see functions.php 
        'add_title_content' => '<div class="bsx-mobile-fixed-nav-icon pt-2"><span class="fas fa-%description$s fa-lg" aria-hidden="true"></span></div><div class="bsx-mobile-fixed-nav-title pb-1">%title$s</div>' // custom filter add_additional_content_in_a(), see functions.php (use %description$s to insert description, use %title$s to insert title into html)
      ) 
    );
  ?>
</div>