<?php

/*

Examples:
    - [faq] (will use faq-id 1)
    - [faq faq-id="2"] (will use faq-id 2)

*/

class FaqShortcode {

    public function init(){
        $this->registerShortcode();
    }

    private function registerShortcode(){
        add_shortcode( 'faq', function( $atts ) {

            $data = shortcode_atts( array(
                'faq-id' => '', // faq id (since using multiple faq custom post types)
            ), $atts );

            return $this->getHtml( $data );
        } );
    }

    private function getHtml( $data ){

        $args = array( 
            'post_type' => 'faq' . ( isset( $data[ 'faq-id' ] ) && ! empty( $data[ 'faq-id' ] ) && $data[ 'faq-id' ] != '1' ? '-' . $data[ 'faq-id' ] : '' ) . '-cpt',
            'order'=>'ASC',
            'posts_per_page' => -1,
        );

        $faq_query = new WP_Query( $args );
        ob_start();
        get_template_part( "template-parts/faq/faq-list", "", array( "custom_query" => $faq_query ) );
        $html = ob_get_clean();
        return $html;
    }

}
