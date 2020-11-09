<?php

$rating   = intval($page_Info['rating']);
$page_url = 'https://facebook.com/' . $page_Info['pgt'] . '/reviews';
$page_thumbnail = !empty($page_Info['picture']) 
    ? $page_Info['picture'] 
    : $handler_url . esc_html__('assets/images/gift-box.png', 'elementsKit-lite');

?>

<div class="ekit-review-overview ekit-review-overview-facebook">

    <!-- Start Overview Left -->
    <div class='d-flex'>

        <!-- Start Overview Image -->
        <div class="ekit-review-overview--thumbnail">
            <img class="thumbnail" src="<?php echo $page_thumbnail ?>">
        </div>
        <!-- Start Overview Image -->

        <div>
            <div class="ekit-review-overview--title">
                <h4>
                    <span>
                        <?php echo $page_Info['pg_name'] ?>
                    </span>
                    <?php echo esc_html__( ' Rating', 'elementsKit-lite' ); ?>
                </h4>
            </div>

            <!-- Start rating -->
            <div class='ekit-review-overview--rating'>
                
                <span class='rating-average'>
                    <?php echo $page_Info['rating'] ?>
                </span>

                <!-- Start Rating stars -->
                <div class="ekit-review-overview--stars">
                    <?php echo $this->get_stars_rating($rating); ?>
                </div>
                <!-- End Rating stars -->

                <p class='rating-text'>
                    <?php echo intval($page_Info['count']) ?>
                    <?php echo esc_html__( ' reviews', 'elementsKit-lite' ); ?>
                </p>

            </div>
            <!-- Start rating -->
        </div>

    </div>
    <!-- End Overview Left -->

    <!-- Start Action Buttons -->
    <div class="ekit-review-overview--actions">
        <a href='<?php echo $page_url ?>' target='_' class='btn btn-primary btn-pill'>
            <?php echo esc_html__( 'Write a Review', 'elementsKit-lite' ); ?>
        </a>
    </div>
    <!-- End Action Buttons -->

</div>