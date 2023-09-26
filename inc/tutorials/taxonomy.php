<?php 

class Tutorials_Taxonomy {

    public function init() {
        $this->registerTaxonomy();
    }

    private function registerTaxonomy() {

		function bsxwp_register_tutorials_taxonomy() {

			// cat
			$labels = array(
				'name'              => _x( 'Tutorials Categories', 'taxonomy general name', 'bsx-wordpress' ),
				'singular_name'     => _x( 'Tutorials Category', 'taxonomy singular name', 'bsx-wordpress' ),
				'search_items'      => __( 'Search Tutorials Categories', 'bsx-wordpress' ),
				'all_items'         => __( 'All Tutorials Categories', 'bsx-wordpress' ),
				'parent_item'       => __( 'Parent Tutorials Category', 'bsx-wordpress' ),
				'parent_item_colon' => __( 'Parent Tutorials Category:', 'bsx-wordpress' ),
				'edit_item'         => __( 'Edit Tutorials Category', 'bsx-wordpress' ),
				'update_item'       => __( 'Update Tutorials Category', 'bsx-wordpress' ),
				'add_new_item'      => __( 'Add new Tutorials Category', 'bsx-wordpress' ),
				'new_item_name'     => __( 'New Tutorials Category name', 'bsx-wordpress' ),
				'menu_name'         => __( 'Tutorials Categories', 'bsx-wordpress' ),
			);
			$args   = array(
				'hierarchical'      => true, // make it hierarchical (like categories)
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => [ 'slug' => 'tutorials-cat' ],
				'show_in_rest' 		=> true, // make avaimable in block editor (right column)
			);
			register_taxonomy( 'tutorials-cat', [ 'tutorials-cpt' ], $args );
		}
		add_action( 'init', 'bsxwp_register_tutorials_taxonomy' );

    }

}