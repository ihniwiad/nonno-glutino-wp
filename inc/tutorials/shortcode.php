<?php

/*

Examples:
    - [tutorials] (will use faq-id 1)

*/

class TutorialsShortcode {

    public function init() {
        $this->registerShortcode();
    }

    private function registerShortcode() {
        add_shortcode( 'tutorials', function( $atts ) {

            $data = shortcode_atts( array(
                // 'foo' => '',
            ), $atts );

            return $this->getHtml( $data );
        } );
    }

    private function getHtml( $data ) {

        $args = array( 
            'post_type' => 'tutorials-cpt',
            'orderby' => 'menu_order', // menu_order (position), title, post_modified, ID, date, ... – read more here: https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters 
            'order'=>'ASC',
            'posts_per_page' => -1,
        );

        $faq_query = new WP_Query( $args );
        ob_start();
        get_template_part( "template-parts/tutorials/tutorials-list", "", array( "custom_query" => $faq_query ) );
        $html = ob_get_clean();
        return $html;
    }

}
