<?php


class Tutorials_Tax_Meta {

    public function init() {
        $this->registerTaxCatMetaBox();
    }

    private function registerTaxCatMetaBox() {

        function add_tutorials_tax_cat_meta_box( $tag ) {

            // list of cat meta items
            $data = [
                // [
                //     'meta_key' => 'internal_id',
                //     'label' => __( 'Internal identifier', 'bsx-wordpress' ),
                //     'hint' => __( 'Not shown in front end or URI.', 'bsx-wordpress' ),
                // ],
                // [
                //     'meta_key' => 'seo_name',
                //     'label' => __( 'SEO Name', 'bsx-wordpress' ),
                //     'hint' => __( 'Shown in Taxonomy single page.', 'bsx-wordpress' ),
                // ],
                [
                    'meta_key' => 'order_position',
                    'label' => __( 'Order position', 'bsx-wordpress' ),
                    'hint' => __( 'If using taxonomy sorting.', 'bsx-wordpress' ),
                ],
            ];

            function print_table_item( $tax_meta, $meta_key, $label, $hint = '' ) {
                // used when edit taxonomy
                ?>
                    <tr class="form-field">
                        <th scope="row" valign="top"><label for="tax_meta[<?php echo $meta_key; ?>]"><?php echo $label; ?></label></th>
                        <td>
                            <input type="text" name="tax_meta[<?php echo $meta_key; ?>]" id="tax_meta[<?php echo $meta_key; ?>]" size="40" value="<?php echo isset( $tax_meta[ $meta_key ] ) ? $tax_meta[ $meta_key ] : ''; ?>"><br />
                            <span class="description"><?php echo $hint; ?></span>
                        </td>
                    </tr>
                <?php
            }
            function print_div_item( $tax_meta, $meta_key, $label, $hint = '' ) {
                // used when create new taxonomy
                ?>
                    <div class="form-field term-slug-wrap">
                        <label for="tax_meta[<?php echo $meta_key; ?>]"><?php echo $label; ?></label>
                        <input  name="tax_meta[<?php echo $meta_key; ?>]" id="tax_meta[<?php echo $meta_key; ?>]" type="text" value="<?php echo isset( $tax_meta[ $meta_key ] ) ? $tax_meta[ $meta_key ] : ''; ?>" size="40">
                        <p><?php echo $hint; ?></p>
                    </div>
                <?php
            }

            if ( $tag instanceof WP_Term ) {
                // term (taxonomy) exists, object is instance of `WP_Term`
                $t_id = $tag->term_id;
                $tax_meta = get_option( "tutorials-cat_$t_id" );

                foreach ( $data as $item ) {
                    print_table_item( $tax_meta, $item[ 'meta_key' ], $item[ 'label' ], $item[ 'hint' ] );
                }
            }
            else {
                // new term (taxonomy) is created, string given
                $tax_meta = [];

                foreach ( $data as $item ) {
                    print_div_item( $tax_meta, $item[ 'meta_key' ], $item[ 'label' ], $item[ 'hint' ] );
                }
            }
        }
        add_action( 'tutorials-cat_edit_form_fields', 'add_tutorials_tax_cat_meta_box' );
        add_action( 'tutorials-cat_add_form_fields', 'add_tutorials_tax_cat_meta_box' );


        function save_tutorials_tax_cat_meta_box( $term_id ) {
            if ( isset( $_POST[ 'tax_meta' ] ) ) {
                $tax_meta = get_option( 'tutorials-cat_' . $term_id );
                $cat_keys = array_keys( $_POST[ 'tax_meta' ] );
                foreach ( $cat_keys as $key ) {
                    if ( isset( $_POST[ 'tax_meta' ][ $key ] ) ) {
                        $tax_meta[ $key ] = $_POST[ 'tax_meta' ][ $key ];
                    }
                }
                //save the option array
                update_option( 'tutorials-cat_' . $term_id, $tax_meta );
            }
        }
        add_action( 'edited_tutorials-cat', 'save_tutorials_tax_cat_meta_box' );
        add_action( 'create_tutorials-cat', 'save_tutorials_tax_cat_meta_box' );

    }

}


