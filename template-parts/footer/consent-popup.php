<?php

$consent_data = array(
  // matomo
  array(
    'cat' => 'analytics',
    'cat_label' => esc_html__( 'Internal analytics (no third party)', 'bsx-wordpress' ),
    'items' => array(
      array(
        'type' => 'script-content',
        'position' => 'header',
        'code' => "
        console.log( 'Analytics script 1' );
        "
      )
    )
  ),
);


if ( class_exists( 'Consent_Popup_Manager' ) ) {

  $consent_popup_manager = new Consent_Popup_Manager( $consent_data );

  ?>

    <!-- consent popup -->    
    <div class="fixed-banner fixed-banner-bottom fixed-banner-closable bg-dark border-top shadow d-none" id="consent-popup" role="dialog" tabindex="-1" hidden data-fn="cookie-related-elem" data-tg="data-processing-popup" data-fn-options="{ cookieName: 'dataProcessingConsentBannerHidden', cookieExpiresDays: 365, hiddenCookieValue: '1', hiddenClass: 'd-none', remoteOpenable: true }">
          
      <div class="container py-3">

        <div class="mb-2"><?php echo esc_html__( 'Please help us to improve our products and our website by accepting our data processing. Thank you!', 'bsx-wordpress' ); ?>&nbsp;<i aria-hidden="true" class="fa fa-heart text-primary"></i></div>

        <div class="row">
          <div class="col-sm">
            <form data-fn="data-processing-form" data-fn-options="{ cookieName: 'dataProcessingConsent', cookieExpiresDays: 365, categoryInputSelector: '[data-g-tg=category-input]' }">

              <div class="d-inline-block align-middle py-1">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="data-processing-consent-essential" value="essential" checked disabled data-g-tg="category-input">
                  <label class="form-check-label" for="data-processing-consent-essential"><?php echo esc_html__( 'Essential', 'bsx-wordpress' ); ?></label>
                </div>

                <?php
                  if ( method_exists( $consent_popup_manager, 'printCheckboxes' ) ) {
                    $consent_popup_manager->printCheckboxes();
                  }
                ?>
              </div>

              <div class="d-inline-block align-middle py-1">
                <button class="btn btn-outline-primary btn-sm mr-2" type="submit" data-fn="cookie-related-elem-close" data-g-fn="save"><?php echo esc_html__( 'Save', 'bsx-wordpress' ); ?></button><!--

                -->
                <button class="btn btn-outline-primary btn-sm mr-2" data-fn="cookie-related-elem-close" data-g-fn="reject-all"><?php echo esc_html__( 'Reject all', 'bsx-wordpress' ); ?></button><!--

                --><button class="btn btn-primary btn-sm" data-fn="cookie-related-elem-close" data-g-fn="allow-all"><?php echo esc_html__( 'Allow all', 'bsx-wordpress' ); ?></button>
              </div>

            </form>
          </div>
          <?php
          // markup for optional menu (needs to be implementend in functions.php & configured in WordPress)

          // <div class="col-sm col-sm-auto d-flex align-items-end">
          //   <div class="w-100 small text-right pt-1">
          //     <a class="footer-link" href="#datenschutz" target="_blank">Datenschutz</a> | <a class="footer-link" href="#datenschutz" target="_blank">Impressum</a>
          //   </div>
          // </div>
          ?>

          <div class="col-sm col-sm-auto d-flex align-items-end">
            <?php
              echo '<!-- Consent Popup Menu -->';
              wp_nav_menu( 
                array( 
                  'theme_location' => 'consent-popup-menu',
                  'menu' => '',
                  'container' => '',
                  'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                  'menu_class' => 'bsx-footer-bottom-nav list-unstyled w-100 small text-right pt-1 mb-0',
                  'menu_id' => '',
                  'add_li_class' => 'footer-bottom-menu-li', // custom filter add_additional_class_on_li(), see functions.php 
                  'add_a_class' => 'footer-link' // custom filteradd_additional_class_on_a(), see functions.php 
                ) 
              ); 
            ?>
          </div>

        </div>
        
      </div>
      
    </div>

  <?php

  if ( method_exists( $consent_popup_manager, 'printData' ) ) {
    // build consent data
    $consent_popup_manager->printData();
  }

}

