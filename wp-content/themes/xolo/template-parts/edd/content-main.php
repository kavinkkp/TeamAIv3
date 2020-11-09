<?php
/**
 * Template Part of edd archive
 *
 * Main Content
 *
 * @package Xolo
 * @subpackage Xolo
 */  
	$xolo_edd_product_structure 	= get_theme_mod( 'edd_product_structure', array('feature-image', 'title', 'meta', 'description')); 
	$edd_archive_meta_layout		= get_theme_mod( 'edd_archive_meta_layout', array( 'price', 'category', 'cart' )); 
    $xolo_enable_edd_sorting_bar  	= get_theme_mod('xolo_enable_edd_sorting_bar','1');
    $xolo_enable_edd_grid_list   	= get_theme_mod('xolo_enable_edd_grid_list','1');
	$xolo_edd_archives_layout   	= get_theme_mod('xolo_edd_archives_layout','xolo_rsb');
	$xolo_edd_archive_column   		= get_theme_mod('edd_archive_column','6');

?>
 <!-- Blog Start -->
 <div class="main-content-part edd">
    <section class="blog-section">
      <div class="xl-container">
                <!-- Blog Content -->
				<?php if($xolo_edd_archives_layout == 'xolo_lsb'): ?>
					<div id="secondary-content" class="xl-column-4">
						<div class="sidebar">
							<?php dynamic_sidebar('xolo-edd-sidebar-primary'); ?>                 
						</div>
					</div>
				<?php endif; ?>
                <?php if($xolo_edd_archives_layout == 'xolo_fullwidth'): ?>
					<div class="xl-column-12 mb-5 mb-xl-0">
				<?php else: ?>	
				   <div id="primary-content" class="xl-column-8 mb-5 mb-xl-0">
				<?php endif; ?>   
                    <div class="xl-columns-area">
						<?php if ($xolo_enable_edd_grid_list || $xolo_enable_edd_sorting_bar ) { ?>
							<div class="xl-column-12">
								<div class="xl-edd-archive-toolbar">
									<?php
									if ($xolo_enable_edd_grid_list) {
										?>
										<div class="xl-edd-view-switcher">
											<span class="xl-trigger-grid fa fa-th"></span>
											<span class="xl-trigger-list fa fa-th-list"></span>
										</div>
										<?php
									}
									if ($xolo_enable_edd_sorting_bar) {
										echo xolo_edd_sorting();
									}
									?>
								</div>
							</div>
						<?php } ?>
						<?php 
							$xolo_paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
							$args = array( 'post_type' => 'post','paged'=>$xolo_paged );	
						?>
						<?php if( have_posts() ): ?>
							<?php while( have_posts() ) : the_post(); 
									?>
									<div id="download-<?php the_ID(); ?>" class="xl-column-<?php echo esc_attr($xolo_edd_archive_column); ?> xl-product-edd">
										<article class="xl-product-area">											
											<div class="xl-image-box xl-list-image-box">
												<div class="xl-elements">
													<?php the_post_thumbnail('full'); ?>
												</div>
								            </div>
											<div class="xl-product-wrapup">
												<?php foreach ( $xolo_edd_product_structure as $data_order ) : ?>
												<?php if ( 'feature-image' === $data_order ) : ?>
												<?php if ( has_post_thumbnail() ) { ?>
													<div class="xl-product-img">
														<?php the_post_thumbnail('full'); ?>
													</div>
												<?php } ?>
												<?php elseif ( 'title' === $data_order ) : ?>
													<?php     
														if ( is_single() ) :
														
														the_title('<h5 class="xl-product-title">', '</h5>' );
														
														else:
														
														the_title( sprintf( '<h5 class="xl-product-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h5>' );
														
														endif; 
												?> 	
												<?php elseif ( 'meta' === $data_order ) : ?>	
													<div class="xl-product-meta">
														<?php foreach ( $edd_archive_meta_layout as $meta_order ) : ?>
															<?php if ( 'author' === $meta_order ) : ?>
															
																<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>" class="xl-author"><i class="fa fa-user"></i> <?php esc_html(the_author()); ?></a>
																
															<?php elseif ( 'date' === $meta_order ) : ?>
															
																<a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>" class="xl-date"><i class="fa fa-calendar"></i><?php echo esc_html(get_the_date('j M Y')); ?></a>
																
															<?php elseif ( 'comments' === $meta_order ) : ?>	
															
																<a href="<?php echo esc_url(get_comments_link( $post->ID )); ?>" class="xl-comment"><i class="fa fa-comment"></i> <?php echo esc_html(get_comments_number($post->ID)); ?> <?php esc_html_e('Comments','xolo'); ?></a>
																
															<?php elseif ( 'category' === $meta_order ) : ?>
																<ul class="xl-product-category">
																	<?php echo '<li class="xl-product-category-item">'. wp_kses_post(get_the_term_list(get_the_ID(), 'download_category', '<i class="fa fa-folder-open "></i> ', '</li><li class="xl-product-category-item"><i class="fa fa-folder-open "></i> ', '</li>')); ?>
																</ul>
																
															<?php elseif ( 'tags' === $meta_order  ) : ?>

															<ul class="xl-product-tag">
																<?php echo '<li class="xl-product-tag-item">'. wp_kses_post(get_the_term_list(get_the_ID(), 'download_tag', '<i class="fa fa-tags "></i> ', '</li><li class="xl-product-tag-item"><i class="fa fa-tags"></i> ', '</li>')); ?>
															</ul>
															
															
														<?php  endif; endforeach; ?>
														<?php foreach ( $edd_archive_meta_layout as $meta_order ) : ?>
															<?php if ( 'price' === $meta_order ) : ?>
															<ul class="xl-list-inline">
															    <li class="xl-list-inline-item">
															     	<b class="xl-price"><?php
															     	if (!edd_has_variable_prices(get_the_ID())) {
																		echo "<i class='fa fa-money'></i>   ";
																		echo esc_html(edd_get_download_price(get_the_ID()));
																	}
																	?></b>
																</li>
															    <li class="xl-list-inline-item float-xl-right float-none"> 
															     	<?php xolo_edd_rating() ?>
																</li>
															</ul>
															<?php elseif ( 'cart' === $meta_order  ) :

																 echo xolo_edd_cart_button_markup();
															?>
														<?php  endif; endforeach; ?>
													</div>
												<?php elseif ( 'description' === $data_order ) : ?>	
													<?php 
														the_content();
													?>
												<?php  endif; endforeach; ?>
											</div>
										</article>
									</div>	
									<?php  
							endwhile; ?>			
                    
							<!-- Pagination -->
								<?php								
								// Previous/next page navigation.
								the_posts_pagination( array(
								'prev_text'          => '<i class="fa fa-angle-double-left"></i>',
								'next_text'          => '<i class="fa fa-angle-double-right"></i>',
								) ); ?>
						<!-- Pagination -->	
						
						<?php else: ?>
							<?php get_template_part('template-parts/content/content','none'); ?>
						<?php endif; ?>
					</div>
					</div>
				<?php if($xolo_edd_archives_layout == 'xolo_rsb'): ?>
					<div id="secondary-content" class="xl-column-4">
						<div class="sidebar">
							<?php dynamic_sidebar('xolo-edd-sidebar-primary'); ?>                 
						</div>
					</div>
				<?php endif; ?>
      </div>
    </section>
    <!-- Blog End -->
</div>
 </main>