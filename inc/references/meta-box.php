<?php

class ReferencesMeta {

    public function init() {
        $this->registerMetaBox();
    }

    private function registerMetaBox() {

        function add_references_meta_box() {
            $screen = 'ref-custom-post'; // choose 'post' or 'page' or custom post
            add_meta_box( 
                'references_meta_box', // $id
                __( 'References Settings', 'bsx-wordpress' ), // $title
                'show_references_meta_box', // $callback
                $screen, // $screen
                'side', // $context, choose 'normal' or 'side'
                'high', // $priority
                null 
            );
        }
        add_action( 'add_meta_boxes', 'add_references_meta_box' );
        function show_references_meta_box() {
            global $post;
            global $functions_file_basename;
            $meta = get_post_meta( $post->ID, 'references', true ); 
            ?>
                <input type="hidden" name="references_meta_box_nonce" value="<?php echo wp_create_nonce( $functions_file_basename ); ?>">
                <p>
                    <label for="references[name]"><?php echo __( 'Name', 'bsx-wordpress' ); ?></label>
                    <br>
                    <input type="text" name="references[name]" id="references[name]" value="<?php if ( isset( $meta['name'] ) ) { echo $meta['name']; } ?>" style="width: 100%;"/>
                </p>
                <p>
                    <label for="references[job_title]"><?php echo __( 'Job title', 'bsx-wordpress' ); ?></label>
                    <br>
                    <input type="text" name="references[job_title]" id="references[job_title]" value="<?php if ( isset( $meta['job_title'] ) ) { echo $meta['job_title']; } ?>" style="width: 100%;"/>
                </p>
                <p>
                    <label for="references[link]"><?php echo __( 'Link', 'bsx-wordpress' ); ?></label>
                    <br>
                    <input type="text" name="references[link]" id="references[link]" value="<?php if ( isset( $meta['link'] ) ) { echo $meta['link']; } ?>" style="width: 100%;"/>
                </p>
                
                <div class="editor-post-featured-image" data-uifn="form-item">
                    <div class="ui-sidebar-input-label">
                        <?php echo __( 'Thumbnail', 'bsx-wordpress' ); ?>
                    </div>
                    <?php 
                        // check if has image
                        $image_url = '';
                        if ( is_array( $meta ) && isset( $meta[ 'image' ] ) && $meta[ 'image' ] != '' ) {
                            // get url from attachment id
                            $attachment_id = $meta[ 'image' ];
                            $image_attributes = wp_get_attachment_image_src( $attachment_id, 'thumbnail' ); // returns array( $url, $width, $height )
                            $image_url = $image_attributes[ 0 ];
                            // $image_width = $image_attributes[ 1 ];
                            // $image_height = $image_attributes[ 2 ];
                            // $image_placeholder_height = $image_width / 150 * $image_height;
                        }
                    ?>
                    <div class="editor-post-featured-image__container">
                        <input class="ui-sidebar-img-button" data-uifn="meta-img-input" type="hidden" name="references[image]" id="references[image]" value="<?php if ( is_array( $meta ) && isset( $meta['image'] ) ) { echo $meta['image']; } ?>">
                        <button class="components-button<?php if ( $image_url != '' ) { echo ' editor-post-featured-image__preview'; } else { echo ' editor-post-featured-image__toggle'; } ?>" data-uifn="browse-img-btn">
                            <span class="<?php if ( $image_url != '' ) { echo 'hidden'; } ?>" data-uifn="browse-btn-label"><?php echo __( 'Select poster image', 'bsx-wordpress' ); ?></span>
                            <img class="<?php if ( $image_url == '' ) { echo 'hidden'; } ?>" data-uifn="meta-img" src="<?php echo $image_url; ?>">
                        </button>
                    </div>
                    <button class="components-button is-secondary<?php if ( $image_url == '' ) { echo ' hidden'; } ?>" data-uifn="browse-btn"><?php echo __( 'Replace image', 'bsx-wordpress' ); ?></button>
                    <button class="components-button is-link is-destructive" data-uifn="img-delete"><?php echo __( 'Remove poster image', 'bsx-wordpress' ); ?></button>

                    <script>
( function( $ ) {
    $( document.currentScript ).parent().parent().find( '[data-uifn="form-item"]' ).each( function() {
        var $metaItemWrapper = $( this );

        var $imgInput = $metaItemWrapper.find( '[data-uifn="meta-img-input"]' );
        var $imgDisplay = $metaItemWrapper.find( '[data-uifn="meta-img"]' );
        var $browseImgButton = $metaItemWrapper.find( '[data-uifn="browse-img-btn"]' );
        var $browseButton = $metaItemWrapper.find( '[data-uifn="browse-btn"]' );
        var $browseButtonLabel = $browseImgButton.find( '[data-uifn="browse-btn-label"]' );
        var $deleteButton = $metaItemWrapper.find( '[data-uifn="img-delete"]' );

        var meta_image_frame


        $.fn.browseImage = function() {
            // If the frame already exists, re-open it.
            if ( meta_image_frame ) {
                meta_image_frame.open();
                return;
            }
            // Sets up the media library frame
            // meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            meta_image_frame = wp.media.frames.file_frame = wp.media( {
                // title: 'Select or Upload Media Of Your Chosen Persuasion',
                // button: {
                //  text: 'Use this media'
                // },
                multiple: false // Set to true to allow multiple files to be selected
                // id: $imgInput.val()
            } );
            // Runs when an image is selected.
            meta_image_frame.on( 'select', function() {
                // Grabs the attachment selection and creates a JSON representation of the model.
                var media_attachment = meta_image_frame.state().get( 'selection' ).first().toJSON();

                // console.log( 'media_attachment.id: ' + media_attachment.id );

                $imgInput.val( media_attachment.id );
                $browseButtonLabel.addClass( 'hidden' );
                $browseButton.removeClass( 'hidden' );
                $browseImgButton.addClass( 'editor-post-featured-image__preview' ).removeClass( 'editor-post-featured-image__toggle' );
                $imgDisplay.attr( 'src', media_attachment.sizes.thumbnail.url ).removeClass( 'hidden' );
            } );
            // preselect selected images
            meta_image_frame.on( 'open', function() {
                var selection = meta_image_frame.state().get( 'selection' );
                var idsString = $imgInput.val();

                if ( idsString.length > 0 ) {
                    var ids = idsString.split( ',' );

                    ids.forEach( function( id ) {
                        attachment = wp.media.attachment( id );
                        attachment.fetch();
                        selection.add( attachment ? [ attachment ] : [] );
                    } );
                 }
            } );
            // Opens the media library frame.
            meta_image_frame.open();

            // TODO: find .attachments-wrapper li[data-id="123"], click() on it
        }

        $browseImgButton.on( 'click', function( event ) {
            event.preventDefault();
            $( this ).browseImage();
        } );
        $browseButton.on( 'click', function( event ) {
            event.preventDefault();
            $( this ).browseImage();
        } );

        // remove image button
        $( '[data-uifn="img-delete"]' ).click( function() {
            $imgInput.val( '' );
            $browseButtonLabel.removeClass( 'hidden' );
            $browseButton.addClass( 'hidden' );
            $browseImgButton.removeClass( 'editor-post-featured-image__preview' ).addClass( 'editor-post-featured-image__toggle' );
            $imgDisplay.attr( 'src', '' ).addClass( 'hidden' );
        } );
    } );
} )( jQuery );
                    </script>
                </div>

            <?php 
        }
        function save_references_meta_box( $post_id ) {
            global $functions_file_basename;
            // verify nonce
            if ( isset( $_POST['references_meta_box_nonce'] ) && !wp_verify_nonce( $_POST['references_meta_box_nonce'], $functions_file_basename ) ) {
                return $post_id;
            }
            // check autosave
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return $post_id;
            }
            // check permissions
            if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
                if ( !current_user_can( 'edit_page', $post_id ) ) {
                    return $post_id;
                } 
                elseif ( !current_user_can( 'edit_post', $post_id ) ) {
                    return $post_id;
                }
            }
            if ( isset( $_POST[ 'references_meta_box_nonce' ] ) ) {
                update_post_meta( $post_id, 'references', $_POST[ 'references' ] );
            }
        }
        add_action( 'save_post', 'save_references_meta_box' );

    }

}