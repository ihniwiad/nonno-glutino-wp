<div class="">
  <?php
    echo '<!-- Consent Popup Menu -->';
    wp_nav_menu( 
      array( 
        'theme_location' => 'mobile-fixed-menu',
        'menu' => '',
        'container' => '',
        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'menu_class' => 'bsx-mobile-fixed-nav list-unstyled mb-0',
        'menu_id' => '',
        'add_li_class' => 'bsx-mobile-fixed-nav-li', // custom filter add_additional_class_on_li(), see functions.php 
        'add_a_class' => 'bsx-mobile-fixed-nav-a' // custom filteradd_additional_class_on_a(), see functions.php 
      ) 
    ); 
  ?>
</div>