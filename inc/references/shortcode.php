<?php

// example: [references cat-id="123"]

class ReferencesShortcode {

    public function init() {
        $this->registerShortcode();
    }

    private function registerShortcode() {
        add_shortcode( 'references', function( $atts ) {

            $data = shortcode_atts( array(
                'type' => '', // list, slider
                'max' => '', // items count, use -1 to show all
                'order' => '', // use wordpress sorting values, default DESC
                'variant' => '', // 1, 2
                'filter' => '', // show filter, oder 1 or true
                'cat-id' => '', // post id of parent category of filter categories
                'filter-id' => '', // unique identifier for multipe tag filters on same page (optional)
            ), $atts );

            return $this->getReferencesHTML( $data );
        } );
    }

    private function getReferencesHTML( $data ) {

        $args = array( 
            'post_type' => 'ref-custom-post',
            'orderby' => 'menu_order', // menu_order (position), title, post_modified, ID, date, ... – read more here: https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters 
            'order' => ( isset( $data[ 'order' ] ) && ! empty( $data[ 'order' ] ) ) ? $data[ 'order' ] : 'DESC',
            'posts_per_page' => ( isset( $data[ 'max' ] ) && ! empty( $data[ 'max' ] ) ) ? intval( $data[ 'max' ] ) : -1,
        );

        $custom_query = new WP_Query( $args );
        $template_type = ( isset( $data[ 'type' ] ) && $data[ 'type' ] != '' ) ? $data[ 'type' ] : 'list';

        ob_start();
        get_template_part( "template-parts/references/references-" . $template_type, "", array( "custom_query" => $custom_query, "data" => $data ) );
        $data = ob_get_clean();
        return $data;
    }

}
