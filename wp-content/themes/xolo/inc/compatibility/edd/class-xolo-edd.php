<?php
/**
 * Easy Digital Downloads Compatibility File.
 *
 * @link https://easydigitaldownloads.com/
 *
 * @package Xolo
 */

// If plugin - 'Easy_Digital_Downloads' not exist then return.
if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
	return;
}
/*Required Helper File*/
require  get_template_directory().'/inc/compatibility/edd/helper.php';
/**
 * Xolo Easy Digital Downloads Compatibility
 */
if ( ! class_exists( 'Xolo_Edd' ) ) :

	/**
	 * Xolo Easy Digital Downloads Compatibility
	 *
	 * @since 1.0.25
	 */
	class Xolo_Edd {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			
			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ), 2 );
			add_filter('widgets_init', array($this, 'xolo_edd_widget_init'));
			add_action( 'customize_register', array( $this, 'customize_register' ), 2 );
			add_action('xolo_after_edd_single', array($this, 'display_edd_related'), 100, 1);
		}

		
		public function load_assets() {
			/**
			 * Enqueue admin pages stylesheet.
			 */
			wp_enqueue_style(
				'xolo-edd-styles',
				XOLO_PARENT_URI . '/assets/css/compatibility/edd.css',
				false,
				XOLO_THEME_VERSION
			);
			
		}
		/**
		 * Register Customizer sections and panel for Easy Digital Downloads.
		 *
		 * @since 1.0.25
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_register( $wp_customize ) {

			/**
			 * Register Sections & Panels
			 */
			require XOLO_PARENT_INC_DIR . '/compatibility/edd/customizer/edd-settings.php';

		}
		
		/**
         * 
         * EDD Sidebar
         * 
         */
        public function xolo_edd_widget_init() {
           register_sidebar( array(
				'name' => __( 'EDD Sidebar', 'xolo' ),
				'id' => 'xolo-edd-sidebar-primary',
				'description' => __( 'The Primary Widget Area', 'xolo' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h5 class="widget_title">',
				'after_title' => '</h5>',
			) );
        }
		
		 /**
         * Callback Function for xolo_after_edd_single
         * Display EDD Related Post
         *
         * @since    1.0.0
         * @access   public
         *
         * @return void
         */
        public function display_edd_related($post_id) {

            $xolo_related_item_type = get_theme_mod('xolo_related_item_type','categories');
            if (empty($xolo_related_item_type)) {
                return false;
            }

            /* edd single element with sorting */
			$xolo_edd_product_single_structure 		= get_theme_mod( 'edd_product_single_structure', array( 'feature-image', 'content' )); 
            
            $xolo_related_item_no = get_theme_mod('xolo_related_item_no','4');
            if (absint($xolo_related_item_no) == 0) {
                return false;
            }
            $xolo_cat_post_args = array(
                'post__not_in'        => array($post_id),
                'post_type'           => 'download',
                'posts_per_page'      => absint($xolo_related_item_no),
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
            );
            if ($xolo_related_item_type == 'edd-categories') {
                $product_categories_terms = wp_get_object_terms($post_id, 'download_category');
                $category_ids             = array();
                if (!empty($product_categories_terms)) {
                    if (!is_wp_error($product_categories_terms)) {
                        foreach ($product_categories_terms as $term) {
                            $category_ids[] = $term->term_id;
                        }
                    }
                }
                $xolo_cat_post_args['tax_query'] = array(
                    array(
                        'taxonomy'         => 'download_category',
                        'field'            => 'id',
                        'terms'            => $category_ids,
                        'include_children' => true,
                        'operator'         => 'IN'
                    ),
                );
            }
            elseif ($xolo_related_item_type == 'edd-tags') {
                $tag_ids            = array();
                $product_tags_terms = wp_get_object_terms($post_id, 'download_tag');
                $category_ids       = array();
                if (!empty($product_tags_terms)) {
                    if (!is_wp_error($product_tags_terms)) {
                        foreach ($product_tags_terms as $term) {
                            $tag_ids[] = $term->term_id;
                        }
                    }
                }
                $xolo_cat_post_args['tax_query'] = array(
                    array(
                        'taxonomy'         => 'download_tag',
                        'field'            => 'id',
                        'terms'            => $tag_ids,
                        'include_children' => true,
                        'operator'         => 'IN'
                    ),
                );

            }
            $related_post_query = new wp_query($xolo_cat_post_args);
            if ($related_post_query->have_posts()) {
                echo '<div id="related_posts" class="related_posts"><div class="xl-column-12"><h2 class="xl-related-post">' . esc_html__('Related Posts', 'xolo') . '</h2></div>';
                while ($related_post_query->have_posts()) {
                    $related_post_query->the_post();
                    ?>
                	<div class="xl-column-6">
	                    <article id="download-<?php the_ID(); ?>" <?php post_class('xl-product-area'); ?>>
							<div class="xl-product-wrapup">
							<?php foreach ( $xolo_edd_product_single_structure as $data_order ) : ?>
								<?php if ( 'feature-image' === $data_order ) : ?>
									<?php if ( has_post_thumbnail() ) { ?>
										<div class="xl-product-img">
											<?php the_post_thumbnail('full'); ?>
										</div>
									<?php } ?>	
									<?php elseif ( 'content' === $data_order ) : ?>
										<?php     
											if ( is_single() ) :
											
											the_title('<h5 class="xl-product-title">', '</h5>' );
											
											else:
											
											the_title( sprintf( '<h5 class="xl-product-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h5>' );
											
											endif; 
										?> 		
										<div class="xl-product-meta">
											<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>" class="xl-author"><i class="fa fa-user"></i> <?php esc_html(the_author()); ?></a>
										
											<a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>" class="xl-date"><i class="fa fa-calendar"></i><?php echo esc_html(get_the_date('j M Y')); ?></a>
											
											<ul class="xl-product-category">
												<?php echo '<li class="xl-product-category-item">'. wp_kses_post(get_the_term_list(get_the_ID(), 'download_category', '<i class="fa fa-folder-open "></i> ', '</li><li class="xl-product-category-item"><i class="fa fa-folder-open "></i> ', '</li>')); ?>
											</ul>
											<ul class="xl-product-tag">
												<?php echo '<li class="xl-product-tag-item">'. wp_kses_post(get_the_term_list(get_the_ID(), 'download_tag', '<i class="fa fa-tags "></i> ', '</li><li class="xl-product-tag-item"><i class="fa fa-tags"></i> ', '</li>')); ?>
											</ul>
											<ul class="xl-list-inline">
												<li class="xl-list-inline-item">
													<b class="xl-price"><?php
													if (!edd_has_variable_prices(get_the_ID())) {
														echo "<i class='fa fa-money'></i>   ";
														echo esc_html(edd_get_download_price(get_the_ID()));
													}
													?></b>
												</li>
											</ul>
										</div>
									<div>	
										<?php 
											the_content();
										?>
									</div>
								<?php  endif; endforeach; ?>
							</div>
						</article>
					</div>
                    <?php
                }
                echo '</div>';
            }
            wp_reset_query();
        }

	}

endif;

if ( apply_filters( 'xolo_enable_edd_integration', true ) ) {
	Xolo_Edd::get_instance();
}
