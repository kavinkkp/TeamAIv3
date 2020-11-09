<?php
/**
 * Edd Sorting Bar
 * @return boolean
 */
function xolo_edd_sorting() {
    $options  = array(
        'date'       => __('Latest', 'xolo'),
        'date-asc'   => __('Oldest', 'xolo'),
        'a-z'        => __('Alphabet', 'xolo'),
        'z-a'        => __('Alphabet Desc', 'xolo'),
        'price'      => __('Sort by price: low to high', 'xolo'),
        'price-desc' => __('Sort by price: high to low', 'xolo'),
    );
    $selected = isset($_GET['sortby']) ? $_GET['sortby'] : 'date';

    $sorting_html
        = '<div class="sorting-wrap">
            <span>' . esc_html__('Sort By:', 'xolo') . '</span>
            <div class="select-box"> <select name="" id="xolo-edd-select-filter" class="selectpicker">';

    foreach ($options as $key => $val) {
        $sorting_html .= "<option value='" . esc_attr($key) . "' " . selected($key, $selected, false) . "'>";
        $sorting_html .= esc_html($val);
        $sorting_html .= "</option>";
    }

    $sorting_html
        .= '</select>
            </div>
        </div>';
    $sorting_html = apply_filters('xolo_edd_sorting', $sorting_html);
    return $sorting_html;
}


/**
 * Filter pre_get_posts in EDD Archive
 * @return boolean
 */
if (!function_exists('edd_filter_products_by_permalink')) {
    function edd_filter_products_by_permalink($query) {
        if (is_admin()) :
            return $query;
        endif;
        if ($query->is_main_query() && (is_post_type_archive('download') || is_tax('download_category'))
        ) {


            if (isset($_GET['sortby']) && !empty($_GET['sortby'])) {
                $sort = $_GET['sortby'];
                if ($sort === 'date') {
                    $query->set('order', 'DESC');
                    $query->set('orderby ', 'date');
                }
                elseif ($sort === 'date-asc') {
                    $query->set('order', 'ASC');
                    $query->set('orderby ', 'date');

                }
                elseif ($sort === 'a-z') {
                    $query->set('order', 'ASC');
                    $query->set('orderby ', 'title');

                }
                elseif ($sort === 'z-a') {
                    $query->set('order', 'DESC');
                    $query->set('orderby ', 'title');

                }
                elseif ($sort === 'price') {
                    $query->set('order', 'ASC');
                    $query->set('post_type', 'download');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('meta_key', 'edd_price');
                }
                elseif ($sort === 'price-desc') {
                    $query->set('order', 'DESC');
                    $query->set('post_type', 'download');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('meta_key', 'edd_price');

                }

            }

        }
    }

    add_action('pre_get_posts', 'edd_filter_products_by_permalink');
}

/**
 * EDD archive page Cart button markup
 *
 * @return array $output Add to cart button markup
 */
function xolo_edd_cart_button_markup() {
	$add_to_cart_text     = get_theme_mod( 'cart_btn_lbl','Add to Cart' );
	$variable_button_text = get_theme_mod( 'cart_variable_btn_lbl','View Details' );
	$output               = edd_get_purchase_link();
	if ( edd_has_variable_prices( get_the_ID() )) {
		$output  = '<div class="xolo-edd-variable-details-button-wrap">';
		$output .= '<a class="button xolo-edd-variable-btn bt-primary bt-effect-1" href="' . esc_url( get_permalink() ) . '">' . esc_html( $variable_button_text ) . '</a>';
		$output .= '</div>';
	} else {
		if ( ! empty( $add_to_cart_text ) ) {
			$output = edd_get_purchase_link(
				array(
					'price' => false,
					'text'  => esc_html( $add_to_cart_text ),
				)
			);
		}
	}

	return $output;
}


if( ! function_exists( 'xolo_edd_dynamic_style' ) ):
    function xolo_edd_dynamic_style() {
		/**
		 * Archive Media
		 */
		
		$output_css = '';
		
		$output_css   .= xolo_customizer_value( 'edd_archive_img_width', '.edd .xl-image-box img', array( 'width' ), array( 50, 50, 50 ), '%' );
		
		/**
		 * Product Styler
		 */
		$xolo_archive_edd_txt_clr		=	get_theme_mod('archive_edd_txt_clr','#383E41');
		$xolo_archive_edd_title_clr		=	get_theme_mod('archive_edd_title_clr','#383E41');
		$xolo_archive_edd_ttl_hov_clr	=	get_theme_mod('archive_edd_ttl_hov_clr','#381CC5');
		$xolo_archive_edd_icon_clr		=	get_theme_mod('archive_edd_icon_clr','#492cdd');
		$xolo_archive_edd_meta_clr		=	get_theme_mod('archive_edd_meta_clr','#383E41');
		$xolo_archive_edd_btn_clr		=	get_theme_mod('archive_edd_btn_clr','#ffffff');
		$xolo_archive_edd_btn_bg_clr	=	get_theme_mod('archive_edd_btn_bg_clr','#492cdd');
		$xolo_archive_edd_bg_clr		=	get_theme_mod('archive_edd_bg_clr','#ffffff');
		$archive_edd_tabs_clr			=	get_theme_mod('archive_edd_tabs_clr','#ffffff');
		$archive_edd_tabs_bg_clr		=	get_theme_mod('archive_edd_tabs_bg_clr','#492cdd');
		
		 $output_css .=".xl-product-edd, .xl-product-edd p{
				color: " .esc_attr($xolo_archive_edd_txt_clr). ";
			}.xl-product-edd .xl-product-area, .xl-product-area.xl-product-edd, .xl-product-area.xl-product-edd .single-product-content {
				background-color: " .esc_attr($xolo_archive_edd_bg_clr). ";
			}\n";
			
		$output_css .=".xl-product-title a, .xl-product-edd .post-title{
				color: " .esc_attr($xolo_archive_edd_title_clr). ";
			}.xl-product-edd .xl-product-title a:hover {
				color: " .esc_attr($xolo_archive_edd_ttl_hov_clr). ";
			}\n";	
			
		$output_css .=".xl-product-meta i, .xl-product-meta li i, .xl-product-meta a i, .xl-product-edd i,.xl-star-rating .rated i.fa,.widget.widget_price .xl-download-sale i, .widget.widget_price .xl-download-comments i {
				color: " .esc_attr($xolo_archive_edd_icon_clr). ";
			}.xl-product-meta a, .xl-product-category a {
				color: " .esc_attr($xolo_archive_edd_meta_clr). ";
			}\n";

		$output_css .=".edd_checkout a,
			.edd_checkout a:hover,
			.edd_checkout a:focus,
			.edd-submit.button.blue,
			.edd-submit.button.blue:hover,
			.edd-submit.button.blue:focus,
			.edd .bt-primary{
				color: " .esc_attr($xolo_archive_edd_btn_clr). ";
				background: " .esc_attr($xolo_archive_edd_btn_bg_clr). ";
				border-color: " .esc_attr($xolo_archive_edd_btn_bg_clr). ";
			}\n";
			
		$output_css .=".single-product-nav li.active,
		.xl-edd-archive-toolbar .xl-edd-view-switcher span.active, .xl-edd-archive-toolbar .xl-edd-view-switcher span:hover{
				color: " .esc_attr($archive_edd_tabs_clr). ";
			}.single-product-nav li.active,.xl-edd-archive-toolbar .xl-edd-view-switcher span.active, .xl-edd-archive-toolbar .xl-edd-view-switcher span:hover {
				background-color: " .esc_attr($archive_edd_tabs_bg_clr). ";
			}.single-product-nav li,.xl-edd-archive-toolbar .xl-edd-view-switcher span.active, .xl-edd-archive-toolbar .xl-edd-view-switcher span:hover{
				border-color: " .esc_attr($archive_edd_tabs_bg_clr). ";
			}\n";
			
		    wp_add_inline_style( 'xolo-style', $output_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'xolo_edd_dynamic_style' );


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function xolo_edd_body_classes( $classes ) {	
	// Product Type
	$edd_product_types	=	get_theme_mod('edd_product_types','grid');
	$classes[] = 'xl-' . esc_attr($edd_product_types);
	
	return $classes;
}
add_filter( 'body_class', 'xolo_edd_body_classes' );


// Product star rating
function xolo_edd_rating() {
    global $wp_query;
    $postID = $wp_query->post->ID;
    $starRating=null;
    if(function_exists("fw_ext_feedback_stars_get_post_rating")){
        $starRating=fw_ext_feedback_stars_get_post_rating($postID);
        $starRating=$starRating['average'];
        $starRating=(round($starRating*2))*10;
    }
    else{
        if( class_exists( 'EDD_Reviews' ) ) {
            $starRating = edd_reviews()->average_rating(false);
            $starRating=(round($starRating*2))*10;
        }
    } ?>
	<div title="<?php echo esc_attr($starRating/20);?> <?php esc_html_e("out of 5","xolo");?>" class="xl-star-rating">
		<div class="rating">
			<i class="fa fa-star" data-vote="1"></i>
			<i class="fa fa-star" data-vote="2"></i>
			<i class="fa fa-star" data-vote="3"></i>
			<i class="fa fa-star" data-vote="4"></i>
			<i class="fa fa-star" data-vote="5"></i>
		</div>
		<div class="rated" style="width:<?php echo esc_html($starRating);?>%">
			<i class="fa fa-star" data-vote="1"></i>
			<i class="fa fa-star" data-vote="2"></i>
			<i class="fa fa-star" data-vote="3"></i>
			<i class="fa fa-star" data-vote="4"></i>
			<i class="fa fa-star" data-vote="5"></i>
		</div>
	</div>
<?php
}

/**
 * Edd Single Sidebar
 * @return boolean
 */
function xolo_edd_single_sidebar() {
?>	
	<div id="secondary-content" class="xl-column-4">
		<div class="single-sidebar-download">
			<div class="widget widget_price">
				<?php
					if ( edd_has_variable_prices( get_the_ID() ) ){ ?>
						<h2 class="price"><?php echo edd_price_range( get_the_ID() ); ?></h2>
					<?php } else { ?>
						<h2 class="price"><?php edd_price(get_the_ID()); ?></h2>
					<?php }
					echo apply_filters( 'edd_product_details_widget_purchase_button', edd_get_purchase_link( array( 'download_id' => get_the_ID() ) ) );
				?>
				<div class="xl-download-rating">
					<span><?php echo esc_html__( 'Item Rating','xolo' ); ?></span>
					<?php xolo_edd_rating() ?>
				</div>
				<div class="xl-columns-area">
					<div class="xl-column-6">
						<div class="xl-download-sale">
							 <i class="fa fa-fw fa-2x fa-shopping-cart" aria-hidden="true"></i>
							 <span><?php echo edd_get_cart_quantity(); ?></span>
						</div>
					</div>
					<div class="xl-column-6">
						<div class="xl-download-comments">
							<i class="fa fa-fw fa-2x fa-comments" aria-hidden="true"></i>
							<span><?php comments_number( 0, 1, '%' ); ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="widget widget_author">
				<h2 class="widget_title"><?php echo esc_html__( 'Author', 'xolo' ); ?></h2>
				<div class="xl-author-info">
					<div class="xl-author-pic">
						 <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>?author_items=true">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
						</a>                        
					</div>
					<span><?php the_author(); ?></span>
					<ul class="xl-list-inline xl-author-product">
						 <?php $authorProducts = get_posts( array(
								"post_type"=>"download",
								"status"=>"publish",
								"author"=> get_the_author_meta( 'ID' ),
								'showposts'=>3
							) );

							foreach ($authorProducts as $authorProduct) { ?>

							<li class="xl-product-category-item">
								<a href="<?php echo get_permalink($authorProduct); ?>">
									<?php the_post_thumbnail(); ?>
								</a>
							</li>
							<?php echo esc_url( get_post_meta( $authorProduct->ID, 'product_item_thumbnail', 1 ) ) ?>

						<?php } ?>
					</ul>
				</div>
			</div>
			<div class="widget widget_product_info">
				<h2 class="widget_title"><?php echo esc_html__( 'Item Information','xolo' ); ?></h2>
				<table>
					<tbody>
						<tr>
							<td><?php echo esc_html__( 'Last Update:','xolo' ); ?></td>
							<td><span><?php the_modified_date(); ?></span></td>
						</tr>
						<tr>
							<td><?php echo esc_html__( 'Released:','xolo' ); ?></td>
							<td><span><?php echo get_the_date(); ?></span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php if(!empty(get_the_term_list( get_the_ID(), 'download_tag'))): ?>
				<div class="widget widget_tags">
					<h2 class="widget_title"><?php echo esc_html__( 'Tags','xolo' ); ?></h2>
					<?php echo get_the_term_list( get_the_ID(), 'download_tag', '<ul class="xl-list-inline"><li class="xl-list-inline-item">', '</li><li class="xl-list-inline-item">', '</li></ul>' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php	
}