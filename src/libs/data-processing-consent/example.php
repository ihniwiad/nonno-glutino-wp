<?php

$consent_data = array(
  array(
    'cat' => 'analytics',
    'cat_label' => esc_html__( 'Analytics', 'bsx-wordpress' ),
    'items' => array(
      array(
        'type' => 'script-src',
        'position' => 'header',
        'code' => 'http://localhost/cookie-related-popup/test/testing.js'
      ),
      array(
        'type' => 'script-content',
        'position' => 'header',
        'code' => "console.log( 'hello from inline script' );"
      ),
      array(
        'type' => 'html',
        'code' => '<div class="container py-3">Hello, im consent-related <span style="background: #fc3;">HTML-content</span>.</div>'
      )
    )
  ),
  array(
    'cat' => 'other-category',
    'cat_label' => esc_html__( 'Customization', 'bsx-wordpress' ),
    'items' => array(
      array(
        'type' => 'script-src',
        'position' => 'header',
        'code' => 'http://localhost/cookie-related-popup/test/testing-2.js'
      ),
      array(
        'type' => 'script-content',
        'position' => 'header',
        'code' => "console.log( 'hello from inline script 2' );"
      )
    )
  ),
  array(
    'cat' => 'empty-category',
    'cat_label' => esc_html__( 'Empty category', 'bsx-wordpress' )
  )
);


if ( class_exists( 'Consent_Popup_Manager' ) ) {
  $consent_popup_manager = new Consent_Popup_Manager( $consent_data );
}




?>

<!-- consent popup -->    
<div class="fixed-banner fixed-banner-bottom fixed-banner-closable bg-light border-top shadow d-none" id="consent-popup" role="dialog" tabindex="-1" hidden data-fn="cookie-related-elem" data-tg="data-processing-popup" data-fn-options="{ cookieName: 'dataProcessingConsentBannerHidden', cookieExpiresDays: 365, hiddenCookieValue: '1', hiddenClass: 'd-none', remoteOpenable: true }">
      
  <div class="container py-3">
    
    <form data-fn="data-processing-form" data-fn-options="{ cookieName: 'dataProcessingConsent', cookieExpiresDays: 365, categoryInputSelector: '[data-g-tg=category-input]' }">
      <div class="form-row align-items-center">

        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" id="data-processing-consent-essential" value="essential" checked disabled data-g-tg="category-input">
          <label class="form-check-label" for="data-processing-consent-essential"><?php echo esc_html__( 'Essential', 'bsx-wordpress' ); ?></label>
        </div>

        <?php
          if ( method_exists( $consent_popup_manager, 'printCheckboxes' ) ) {
            $consent_popup_manager->printCheckboxes();
          }
        ?>

        <div class="col-auto">
          <button class="btn btn-outline-primary btn-sm" type="submit" data-fn="cookie-related-elem-close" data-g-fn="save"><?php echo esc_html__( 'Save', 'bsx-wordpress' ); ?></button>
        </div>

        <div class="col-auto">
          <button class="btn btn-primary btn-sm" data-fn="cookie-related-elem-close" data-g-fn="allow-all"><?php echo esc_html__( 'Allow all', 'bsx-wordpress' ); ?></button>
        </div>

      </div>
    </form>
    
  </div>
  
</div>


<?php
  if ( method_exists( $consent_popup_manager, 'printData' ) ) {
    // build consent data
    $consent_popup_manager->printData();
  }
?>


