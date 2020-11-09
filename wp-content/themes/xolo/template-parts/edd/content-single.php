<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Xolo
 */

get_header();
$xolo_edd_single_layout 				= get_theme_mod('xolo_edd_single_layout', 'xolo_rsb'); 
$xolo_edd_product_single_structure 		= get_theme_mod( 'edd_product_single_structure', array( 'feature-image', 'content' )); 
$xolo_edd_single_meta_layout	    	= get_theme_mod( 'edd_single_meta_layout', array( 'price', 'category')); 
?>
  <!-- Blog Start -->
	 <div class="main-content-part edd edd-single">
        <section class="blog-section">
            <div class="xl-container">
                    <!-- Blog Content -->
					<?php if($xolo_edd_single_layout == 'xolo_lsb'): xolo_edd_single_sidebar(); endif; ?>
					<?php if($xolo_edd_single_layout == 'xolo_fullwidth'): ?>
						<div class="xl-column-12 mb-5 mb-xl-0">
					<?php else: ?>	
					   <div id="primary-content" class="xl-column-8 mb-5 mb-xl-0">
					<?php endif; ?>   
						<?php if( have_posts() ): ?>
							<?php while( have_posts() ): the_post(); ?>
							<div class="xl-column-12">
								<article id="download-<?php the_ID(); ?>" <?php post_class('xl-product-area single-product-area xl-product-edd'); ?>>
									<?php foreach ( $xolo_edd_product_single_structure as $data_order ) : ?>
										<?php if ( 'feature-image' === $data_order ) : ?>
											<div class="single-product-thumbnail">
												<?php if ( has_post_thumbnail() ) { ?>
													<?php the_post_thumbnail('full'); ?>
												<?php } ?>										
											</div>
										<?php elseif ( 'content' === $data_order ) : ?>
										<ul class="single-product-nav" role="tablist">
											<li class="nav-link active" data-tab="item-details"><?php echo esc_html__( 'Item Details', 'xolo' ); ?></li>
											<li class="nav-link" data-tab="item-category"><?php echo esc_html__( 'Category', 'xolo' ); ?></li>
											<li class="nav-link" data-tab="item-comments"><?php echo esc_html__( 'Comments', 'xolo' ); ?></li>
										</ul>
										<div class="single-product-content">
											<div class="tab-content">
												<div id="item-details" class="tab-panel active">
													<?php     
														if ( is_single() ) :
														
														the_title('<h5 class="post-title blog-single-title">', '</h5>' );
														
														else:
														
														the_title( sprintf( '<h5 class="post-title blog-single-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h5>' );
														
														endif; 
													?>
													<span class="single-product-description"><?php the_content(); ?></span>
												</div>
												 <div id="item-category" class="tab-panel">
												   <ul class="xl-product-category">
														<?php echo '<li class="xl-product-category-item">'. wp_kses_post(get_the_term_list(get_the_ID(), 'download_category', '<i class="fa fa-folder-open "></i> ', '</li><li class="xl-product-category-item"><i class="fa fa-folder-open "></i> ', '</li>')); ?>
													</ul>
												</div>
												<div id="item-comments" class="tab-panel">
													<?php 
														if( $post->comment_status == 'open' ) { 
															comments_template( '', true ); // show comments 
														}
													?>	
												</div>
											</div>
										</div>
									<?php  endif; endforeach; ?>	
								</article>
							</div>
							<?php endwhile; ?>
							<?php else: ?>
								<?php get_template_part('template-parts/content/content','none'); ?>
							<?php endif; ?>
							<?php 
								// $xolo_enable_author_box		= get_theme_mod('xolo_enable_author_box','1');
								// if($xolo_enable_author_box == '1'){
									// get_template_part('template-parts/content/content-author','meta'); 
								// }
							?>
						<?php do_action('xolo_after_edd_single', get_the_ID()); ?>
	                    </div>
	                    <!-- Sidebar -->
	                    <?php if($xolo_edd_single_layout == 'xolo_rsb'):  xolo_edd_single_sidebar();  endif; ?> 
	                    
            </div>
        </section>
	</div>	
        <!-- Blog End -->
    </main>
    <!-- Main End -->
<?php get_footer(); ?>
