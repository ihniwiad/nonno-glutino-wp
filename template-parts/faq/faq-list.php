<?php

if ( isset( $args[ 'custom_query' ] ) ) {
    // is qustom query

    $custom_query = $args[ 'custom_query' ];

    // get faq from post object
    if ( $custom_query->have_posts() ) {
        $faqs = $custom_query->get_posts();
    }
    else {
        $faqs = array();
    }
}


if ( ! empty( $faqs ) ): 
    ?>
        <div itemscope itemtype="https://schema.org/FAQPage">
            <ul class="list-unstyled faq-list" data-acc>
                <?php 

                    // $item_id = 0; // use post id instead of counter

                    foreach( $faqs as $faq ): setup_postdata( $faq );

                        // print '<pre>';
                        // print_r( $faqs );
                        // print '</pre>';

                        $id = $faq->ID;
                        $title = $faq->post_title;
                        $faq_data = get_post_meta( $id, 'faq', true );
                        $content = $faq->post_content;

                        // print '<pre>';
                        // print_r( $faq_data );
                        // print '</pre>';

                        // TEST
                        // echo '<div>' . $date . ' – ' . $time . ': ' . $title . ' (ID ' . $id . ')</div>';

                        // $item_id ++;

                        ?>
                            <li class="faq-list-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" data-acc-itm data-id="<?php echo $id; ?>">
                                <section class="faq-section">
                                    <h3 class="faq-item-heading lead my-0">
                                        <button class="acc-header faq-item-heading-button" id="acc-faq-<?php echo $id; ?>-trig" aria-controls="acc-faq-<?php echo $id; ?>-cont" aria-expanded="false" data-bsx="acc">
                                            <span class="acc-header-text" itemprop="name"><?php echo $title; ?></span>
                                            <span class="acc-header-icon"></span>
                                        </button>
                                    </h3>
                                    <?php
                                        // removes attr aria-labeledby to make validator happy
                                        //aria-labeledby="acc-faq-<?---php echo $id; ?--->-trig"
                                    ?>
                                    <div class="bsx-acc-content faq-item-content" id="acc-faq-<?php echo $id; ?>-cont" role="region" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                        <div class="bsx-acc-content-inner" itemprop="text" data-acc-cnt-inr>
                                            <?php echo $content; ?>
                                        </div>
                                    </div>
                                </section><!-- /.faq-section -->
                            </li>
                        <?php

                    endforeach; 
                    wp_reset_postdata();
                ?>
            </ul>
        </div>
    <?php
else: 
    ?>
        <!-- currently no faq configured -->
    <?php
endif;
