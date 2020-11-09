<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package xolo
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function xolo_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	 //$xolo_site_layout			= get_theme_mod('xolo_site_layout','contained');
	 
	 // Site layout.
	$classes[] = 'xl-layout-' . xolo_get_site_layout();

	 $header_type		= get_theme_mod('header_type','header-default');
	 $classes[] = esc_attr($header_type);
	 
	 if ( is_page_template( 'templates/template-homepage.php' ) && $header_type == 'header-two') {
		 $classes[] = 'xolo-transparent';
	 }
	
	// Menu Active
	$xolo_menu_active	=	get_theme_mod('xolo_menu_active','active-default');
	$classes[] = esc_attr($xolo_menu_active);
	
	return $classes;
}
add_filter( 'body_class', 'xolo_body_classes' );

/**
 * Adds custom classes to the array of post classes.
 */
if ( ! function_exists( 'xolo_post_class' ) ) {

	/**
	 * Adds custom classes to the array of post classes.
	 *
	 * @since 1.0
	 * @param array $classes Classes for the post element.
	 * @return array
	 */
	function xolo_post_class( $classes ) {
		if ( is_archive() || is_home() || is_search() ) {
			$classes[] = 'xl-column-12';
		}

		return $classes;
	}
}
add_filter( 'post_class', 'xolo_post_class' );


if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Backward compatibility for wp_body_open hook.
	 *
	 * @since 1.0.0
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Get sidebar name.
 *
 * @since 1.0.0
 * @return string|boolean
 */
function xolo_get_sidebar() {

	$sidebar = 'xolo-sidebar-primary';

	$sidebar = apply_filters( 'xolo_sidebar_name', $sidebar );

	if ( ! is_active_sidebar( $sidebar ) && ! current_user_can( 'edit_theme_options' ) ) {
		return false;
	}

	return $sidebar;
}


/**
 * Get registered sidebar name by sidebar ID.
 *
 * @since  1.0.0
 * @param  string $sidebar_id Sidebar ID.
 * @return string Sidebar name.
 */
function xolo_get_sidebar_name_by_id( $sidebar_id = '' ) {

	if ( ! $sidebar_id ) {
		return;
	}

	global $wp_registered_sidebars;
	$sidebar_name = '';

	if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
		$sidebar_name = $wp_registered_sidebars[ $sidebar_id ]['name'];
	}

	return $sidebar_name;
}


/**
 * Display Sidebars
 */
if ( ! function_exists( 'xolo_get_sidebars' ) ) {
	/**
	 * Get Sidebar
	 *
	 * @since 1.0.1.1
	 * @param  string $sidebar_id   Sidebar Id.
	 * @return void
	 */
	function xolo_get_sidebars( $sidebar_id ) {
		if ( is_active_sidebar( $sidebar_id ) ) {
			dynamic_sidebar( $sidebar_id );
		} elseif ( current_user_can( 'edit_theme_options' ) ) {
			?>
			<div class="widget">
				<p class='no-widget-text'>
					<a href='<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>'>
						<?php esc_html_e( 'Add Widget', 'xolo' ); ?>
					</a>
				</p>
			</div>
			<?php
		}
	}
}

// Comments Counts
if ( ! function_exists( 'xolo_comment_count' ) ) :
function xolo_comment_count() {
	$xolo_comments_count 	= get_comments_number();
	if ( 0 === intval( $xolo_comments_count ) ) {
		echo esc_html__( '0 Comments', 'xolo' );
	} else {
		/* translators: %s Comment number */
		 echo sprintf( _n( '%s Comment', '%s Comments', $xolo_comments_count, 'xolo' ), number_format_i18n( $xolo_comments_count ) );
	}
} 
endif;

// Header Search
if ( ! function_exists( 'xolo_header_search' ) ) :
function xolo_header_search() {
	$hdr_search_enable		= get_theme_mod('hdr_search_enable');
	?>
	<?php if($hdr_search_enable == '1') : ?>
			<li class="search-button">
				<a href="#" id="view-search-btn" class="header-search-toggle"><i class="fa fa-search"></i></a>
				<!-- Quik search -->
				    <?php if ( function_exists( 'xolo_search' ) ) : xolo_search(); endif; ?>
				<!-- / -->
			</li>
	<?php endif; 
} 
endif;


// Header Button
if ( ! function_exists( 'xolo_header_button' ) ) :
function xolo_header_button() {
	$hdr_btn_enable			= get_theme_mod('hdr_btn_enable');
	$hdr_btn_lbl			= get_theme_mod('hdr_btn_lbl');
	$hdr_btn_link			= get_theme_mod('hdr_btn_link');
	?>
	<?php if($hdr_btn_enable == '1') : ?>	
			 <li class="menu-item header_btn">
				<a href="<?php echo esc_url($hdr_btn_link); ?>" class="bt-primary bt-effect-1"><?php echo esc_html($hdr_btn_lbl); ?></a>
			</li>
	<?php endif; 
} 
endif;


// Xolo Search PopUp
if ( ! function_exists( 'xolo_search' ) ) :
function xolo_search() {
	?>	
	<!-- Quik search -->
	<div class="view-search-btn header-search-popup">
	    <form role="search" method="get" class="xl-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Site Search', 'xolo' ); ?>">
	        <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'xolo' ); ?></span>
	        <input type="search" class="xl-search-field header-search-field" placeholder="<?php esc_attr_e( 'Type To Search', 'xolo' ); ?>" name="s" id="popfocus" value="" autofocus>
	        <a href="#" class="close-style header-search-close"></a>
	    </form>
	</div>
	<!-- / -->
	<?php 
	} 
endif;

// Nav Walker
if ( ! function_exists( 'xolo_nav' ) ) :
function xolo_nav() {
	wp_nav_menu( 
		array(  
			'theme_location' => 'primary_menu',
			'container'  => '',
			'menu_class' => 'menu-wrap',
			'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
			'walker' => new WP_Bootstrap_Navwalker()
			 ) 
		);
} 
endif;


// Logo, title, description
if ( ! function_exists( 'xolo_logo_title_desc' ) ) :
function xolo_logo_title_desc() {
	$sticky_logo 	  = get_theme_mod('sticky_logo','');
		if(has_custom_logo() || $sticky_logo !== '')
		{   
			the_custom_logo();
			if($sticky_logo !== ''){ 
				?>
					<a class="sticky-navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php echo esc_url($sticky_logo); ?>" class="custom-logo" alt="<?php esc_attr(bloginfo("name")); ?>">
					</a>
				<?php
			}
		}
		else { 
		?>
		<a href="<?php echo esc_url(home_url( '/' )); ?>" class="navbar-brand site-title" <?php xolo_schema_markup( 'url' ); ?>>
			<?php
				echo esc_html(bloginfo('name'));
			?>
		</a>
		<?php	
		}
		?>
	<?php
		$description = get_bloginfo( 'description');
		if ($description) : ?>
			<p class="site-description"><?php echo esc_html($description); ?></p>
	<?php endif; 
} 
endif;

// Mobile Logo, title, description
if ( ! function_exists( 'xolo_mbl_logo_title_desc' ) ) :
function xolo_mbl_logo_title_desc() {
		$mobile_logo_on    = get_theme_mod('mobile_logo_on');
		$mobile_logo 	   = get_theme_mod('mobile_logo');
		$sticky_logo 	   = get_theme_mod('sticky_logo','');
		if(has_custom_logo() || $mobile_logo_on == 'true' || $sticky_logo !== '')
		{	
			if($mobile_logo_on == 'true' && $sticky_logo !== ''){ 
			?>
				<a class="sticky-navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo esc_url($sticky_logo); ?>" class="custom-logo" alt="<?php esc_attr(bloginfo("name")); ?>">
				</a>
				<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo esc_url($mobile_logo); ?>" class="custom-logo" alt="<?php esc_attr(bloginfo("name")); ?>">
				</a>
			<?php
			}elseif($sticky_logo !== ''){ 
			?>
				<a class="sticky-navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo esc_url($sticky_logo); ?>" class="custom-logo" alt="<?php esc_attr(bloginfo("name")); ?>">
				</a>
			<?php
			}elseif($mobile_logo_on == 'true'){ 
				?>
					<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php echo esc_url($mobile_logo); ?>" class="custom-logo" alt="<?php esc_attr(bloginfo("name")); ?>">
					</a>
				<?php
			}else{
				the_custom_logo();
			}
		}
		else { 
		?>
		<a href="<?php echo esc_url(home_url( '/' )); ?>" class="navbar-brand site-title" <?php xolo_schema_markup( 'url' ); ?>>
			<?php
				echo esc_html(bloginfo('name')); 
			?>
		</a>
		<?php	
		}
		?>
	<?php
		$description = get_bloginfo( 'description');
		if ($description) : ?>
			<p class="site-description"><?php echo esc_html($description); ?></p>
	<?php endif; 
} 
endif;


// Xolo Mobile Menu
if ( ! function_exists( 'xolo_mobile_menu' ) ) :
function xolo_mobile_menu() {
	$mobile_menu_lbl = get_theme_mod('mobile_menu_lbl'); 
	?>
   <div class="theme-mobile-nav <?php echo esc_attr(xolo_sticky_menu()); ?>">        
		<div class="xl-container">
			<div class="xl-columns-area">
				<div class="xl-column-12">
					<div class="theme-mobile-menu">
						<div class="mobile-logo">
							<?php if ( function_exists( 'xolo_mbl_logo_title_desc' ) ) : xolo_mbl_logo_title_desc(); endif; ?>
						</div>		
						<div class="menu-toggle-wrap">
							<div class="hamburger-menu">
								<a href="#" class="menu-toggle">
									<div class="top-bun"></div>
									<div class="meat"></div>
									<div class="bottom-bun"></div>
								</a>
							</div>
							<?php if($mobile_menu_lbl !== '') : ?>
								<span class="tgl-lbl"><?php echo esc_html($mobile_menu_lbl); ?></span>
							<?php endif; ?>	
						</div>
						<div id="mobile-m" class="mobile-menu">
							<a href="#" class="close-style close-menu"></a>
						</div>
					</div>
				</div>
			</div>
		</div>        
    </div>
	<?php 
	} 
endif;

if (!function_exists('xolo_str_replace_assoc')) {

    /**
     * xolo_str_replace_assoc
     * @param  array $replace
     * @param  array $subject
     * @return array
     */
    function xolo_str_replace_assoc(array $replace, $subject) {
        return str_replace(array_keys($replace), array_values($replace), $subject);
    }
}

// Xolo Footer Group First
if ( ! function_exists( 'xolo_footer_group_first' ) ) :
function xolo_footer_group_first() {
	$footer_bottom_1 			= get_theme_mod('footer_bottom_1','custom');	
	$footer_first_custom 		= get_theme_mod('footer_first_custom','Copyright &copy; [current_year] [site_title] | Powered by [theme_author]');	
		// Custom
		if($footer_bottom_1 == 'custom'): ?>
			<?php  if ( ! empty( $footer_first_custom ) ){ ?>
				<?php 	
					$xolo_copyright_allowed_tags = array(
						'[current_year]' => date_i18n('Y'),
						'[site_title]'   => get_bloginfo('name'),
						'[theme_author]' => sprintf(__('<a href="#">Xolo WordPress Theme</a>', 'xolo')),
					);
				?>
				<p>
					<?php
						echo apply_filters('xolo_footer_copyright', wp_kses_post(xolo_str_replace_assoc($xolo_copyright_allowed_tags, $footer_first_custom)));
					?>
				</p>	
			<?php } ?>
		<?php endif;
		
		// Widget
		 if($footer_bottom_1 == 'widget'): ?>
			<?php  xolo_get_sidebars( 'xolo-footer-layout-first' ); ?>
		<?php endif; 
		
		// Menu
		 if($footer_bottom_1 == 'menu'): ?>
			<?php 
				wp_nav_menu( 
					array(  
						'theme_location' => 'footer_menu',
						'container'  => '',
						'menu_class' => 'menu-wrap',
						'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
						'walker' => new WP_Bootstrap_Navwalker()
						 ) 
					);
			?>
		<?php endif; ?>
	<?php 
	} 
endif;


// Xolo Footer Group Second
if ( ! function_exists( 'xolo_footer_group_second' ) ) :
function xolo_footer_group_second() {
	$footer_bottom_2 			= get_theme_mod('footer_bottom_2','none');	
	$footer_second_custom 		= get_theme_mod('footer_second_custom');
		// Custom
		 if($footer_bottom_2 == 'custom'): ?>
			<?php 	
				$xolo_copyright_allowed_tags = array(
					'[current_year]' => date_i18n('Y'),
					'[site_title]'   => get_bloginfo('name'),
					'[theme_author]' => sprintf(__('<a href="#">Xolo WordPress Theme</a>', 'xolo')),
				);
			?>
			<p>
				<?php
					echo apply_filters('xolo_footer_copyright', wp_kses_post(xolo_str_replace_assoc($xolo_copyright_allowed_tags, $footer_second_custom)));
				?>
			</p>
		<?php endif; 
		
		// Widget
		 if($footer_bottom_2 == 'widget'): ?>
			<?php  xolo_get_sidebars( 'xolo-footer-layout-second' ); ?>
		<?php endif; 
		
		// Menu
		 if($footer_bottom_2 == 'menu'): ?>
			<?php 
				wp_nav_menu( 
					array(  
						'theme_location' => 'footer_menu',
						'container'  => '',
						'menu_class' => 'menu-wrap',
						'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
						'walker' => new WP_Bootstrap_Navwalker()
						 ) 
					);
			?>
		<?php endif; ?>
	<?php 
	} 
endif;	


// Xolo Header
if ( ! function_exists( 'xolo_header' ) ) :
function xolo_header() {
	if (  ! function_exists( 'xolo_header_extends' ) ){
		$xolo_header_type		= get_theme_mod('header_type','header-default');
		get_template_part('template-parts/header/xolo',''.$xolo_header_type);
	}else{
		xolo_header_extends();
	}
} 
endif;
	
// Xolo Header Image
if ( ! function_exists( 'xolo_header_image' ) ) :
function xolo_header_image() {
	if ( get_header_image() ) : ?>
		<div class="header-img">
			<?php 
				$xolo_hdr_img_title	    = get_theme_mod('hdr_img_title');
				$xolo_hdr_img_desc 		= get_theme_mod('hdr_img_desc');	
				$xolo_hdr_img_btn_lbl    = get_theme_mod('hdr_img_btn_lbl');
				$xolo_hdr_img_btn_link	= get_theme_mod('hdr_img_btn_link');
			?>
			<div class="custom-header-img">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="custom-header" rel="home">
					<img src="<?php esc_url(header_image()); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr(get_bloginfo( 'title' )); ?>">
				</a>
			</div>
			<div class="header-content">
				<div class="header-content-inner">
					<?php  if ( ! empty( $xolo_hdr_img_title ) ){ ?>
						<h2 class="section-heading"><?php echo esc_html($xolo_hdr_img_title); ?></h2>
					<?php } ?>	
					<?php  if ( ! empty( $xolo_hdr_img_desc ) ){ ?>
						<p class="section-description"><?php echo esc_html($xolo_hdr_img_desc); ?></p>
					<?php } ?>	
					<?php  if ( ! empty( $xolo_hdr_img_btn_lbl ) ){ ?>
					<a href="<?php echo esc_url($xolo_hdr_img_btn_link); ?>" class="bt-primary bt-effect-1"><?php echo esc_html($xolo_hdr_img_btn_lbl); ?></a>
					<?php } ?>
				</div>
			</div>
			
		</div>	
<?php endif; 
	} 
endif;	


if ( ! function_exists( 'xolo_get_allowed_html_tags' ) ) {
	/**
	 * Array of allowed HTML Tags.
	 *
	 * @since 1.0.0
	 * @param string $type predefined HTML tags group name.
	 * @return array, allowed HTML tags.
	 */
	function xolo_get_allowed_html_tags( $type = 'basic' ) {

		$tags = array();

		switch ( $type ) {

			case 'basic':
				$tags = array(
					'strong' => array(),
					'em'     => array(),
					'b'      => array(),
					'br'     => array(),
					'i'      => array(
						'class' => array(),
					),
					'img'    => array(
						'src'    => array(),
						'alt'    => array(),
						'width'  => array(),
						'height' => array(),
						'class'  => array(),
						'id'     => array(),
					),
					'span'   => array(
						'class' => array(),
					),
					'a'      => array(
						'href'   => array(),
						'rel'    => array(),
						'target' => array(),
						'class'  => array(),
						'role'   => array(),
						'id'     => array(),
					),
				);
				break;

			case 'button':
				$tags = array(
					'strong' => array(),
					'em'     => array(),
					'span'   => array(
						'class' => array(),
					),
					'i'      => array(
						'class' => array(),
					),
				);
				break;

			case 'span':
				$tags = array(
					'span' => array(
						'class' => array(),
					),
				);
				break;

			case 'icon':
				$tags = array(
					'i'    => array(),
					'span' => array(),
					'img'  => array(),
				);
				break;

			default:
				$tags = array(
					'strong' => array(),
					'em'     => array(),
					'b'      => array(),
					'i'      => array(),
					'img'    => array(
						'src'    => array(),
						'alt'    => array(),
						'width'  => array(),
						'height' => array(),
						'class'  => array(),
						'id'     => array(),
					),
					'span'   => array(),
					'a'      => array(
						'href'   => array(),
						'rel'    => array(),
						'target' => array(),
						'class'  => array(),
						'role'   => array(),
						'id'     => array(),
					),
				);
				break;
		}

		return apply_filters( 'xolo_allowed_html_tags', $tags, $type );
	}
}


if ( ! function_exists( 'xolo_get_pro_url' ) ) :
	/**
	 * Returns an URL with utm tags
	 * the admin settings page.
	 *
	 * @param string $url    URL fo the site.
	 * @param string $source utm source.
	 * @param string $medium utm medium.
	 * @param string $campaign utm campaign.
	 * @return mixed
	 */
	function xolo_get_pro_url( $url, $source = '', $medium = '', $campaign = '' ) {

		$xolo_pro_url = trailingslashit( $url );

		// Set up our URL if we have a source.
		if ( isset( $source ) ) {
			$xolo_pro_url = add_query_arg( 'utm_source', sanitize_text_field( $source ), $url );
		}
		// Set up our URL if we have a medium.
		if ( isset( $medium ) ) {
			$xolo_pro_url = add_query_arg( 'utm_medium', sanitize_text_field( $medium ), $url );
		}
		// Set up our URL if we have a campaign.
		if ( isset( $campaign ) ) {
			$xolo_pro_url = add_query_arg( 'utm_campaign', sanitize_text_field( $campaign ), $url );
		}

		return esc_url( apply_filters( 'xolo_get_pro_url', $xolo_pro_url, $url ) );
	}

endif;

/**
 * Check if schema is enabled.
 *
 * @since 1.0.43
 * @return boolean
 */
function xolo_is_schema_enabled() {

	$enabled 	= get_theme_mod( 'enable_schema_markup','1'); 

	return apply_filters( 'xolo_is_schema_enabled', $enabled );
}

if ( ! function_exists( 'xolo_get_schema_markup' ) ) :
	/**
	 * Return correct schema markup.
	 *
	 * @since 1.0.43
	 * @param string $location Location for schema parameters.
	 */
	function xolo_get_schema_markup( $location = '' ) {

		// Check if schema is enabled.
		if ( ! xolo_is_schema_enabled() ) {
			return;
		}

		// Return if no location parameter is passed.
		if ( ! $location ) {
			return;
		}

		$schema = '';

		if ( 'url' === $location ) {
			
			$schema = 'itemprop="url"';
			
		} elseif ( 'name' === $location ) {
			
			$schema = 'itemprop="name"';
			
		} elseif ( 'text' === $location ) {
			
			$schema = 'itemprop="text"';
			
		} elseif ( 'headline' === $location ) {
			
			$schema = 'itemprop="headline"';
			
		} elseif ( 'image' === $location ) {
			
			$schema = 'itemprop="image"';
			
		} elseif ( 'header' === $location ) {
			
			$schema = 'itemtype="https://schema.org/WPHeader" itemscope="itemscope"';
			
		} elseif ( 'site_navigation' === $location ) {
			
			$schema = 'itemtype="https://schema.org/SiteNavigationElement" itemscope="itemscope"';
			
		} elseif ( 'logo' === $location ) {
			
			$schema = 'itemprop="logo"';
			
		} elseif ( 'description' === $location ) {
			
			$schema = 'itemprop="description"';
			
		} elseif ( 'organization' === $location ) {
			
			$schema = 'itemtype="https://schema.org/Organization" itemscope="itemscope" ';
			
		} elseif ( 'breadcrumblist' === $location ) {
			
			$schema = 'itemtype="http://schema.org/BreadcrumbList" itemscope="itemscope"';
		
		} elseif ( 'footer' === $location ) {
			
			$schema = 'itemtype="http://schema.org/WPFooter" itemscope="itemscope"';
			
		} elseif ( 'sidebar' === $location ) {
			
			$schema = 'itemtype="http://schema.org/WPSideBar" itemscope="itemscope"';
			
		} elseif ( 'main' === $location ) {
			
			$schema = 'itemtype="http://schema.org/WebPageElement" itemprop="mainContentOfPage"';

			if ( is_singular( 'post' ) ) {
				$schema = 'itemscope itemtype="http://schema.org/Blog"';
			}
			
		} elseif ( 'author' === $location ) {
			
			$schema = 'itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person"';
			
		} elseif ( 'name' === $location ) {
			
			$schema = 'itemprop="name"';
			
		} elseif ( 'datePublished' === $location ) {
			
			$schema = 'itemprop="datePublished"';
			
		} elseif ( 'dateModified' === $location ) {
			
			$schema = 'itemprop="dateModified"';
			
		} elseif ( 'article' === $location ) {
			
			$schema = 'itemscope="" itemtype="https://schema.org/CreativeWork"';
			
		} elseif ( 'comment' === $location ) {
			
			$schema = 'itemprop="comment" itemscope="" itemtype="https://schema.org/Comment"';
			
		} elseif ( 'html' === $location ) {
			
			if ( is_singular() ) {
				$schema = 'itemscope itemtype="http://schema.org/WebPage"';
			} else {
				$schema = 'itemscope itemtype="http://schema.org/Article"';
			}
			
		}

		$schema = ' ' . trim( apply_filters( 'xolo_schema_markup', $schema, $location ) );

		return $schema;
	}
endif;

if ( ! function_exists( 'xolo_schema_markup' ) ) :
	/**
	 * Outputs correct schema markup
	 *
	 * @since 1.0.43
	 * @param string $location Location for schema parameters.
	 */
	function xolo_schema_markup( $location ) {
		echo xolo_get_schema_markup( $location ); // phpcs:ignore
	}
endif;



/**
 * 
 *Xolo Default Page Layout
 * 
 */
 
 if ( ! function_exists( 'xolo_dafault_page_layout' ) ) :
	function xolo_dafault_page_layout() {
		
		$xolo_customizer_default_pg_layout 		= get_theme_mod('xolo_default_pg_layout', 'xolo_rsb'); 
		$xolo_page_sidebar_meta_layout 			= get_post_meta( get_the_ID(), 'xolo_sidebar_position', true );
		
		if($xolo_page_sidebar_meta_layout !== ''){
			$xolo_default_pg_layout = $xolo_page_sidebar_meta_layout;
		}else{
			$xolo_default_pg_layout = $xolo_customizer_default_pg_layout;
		}
		return $xolo_default_pg_layout;
	}
endif;

/**
 * 
 *Xolo Default Page Breadcrumb
 * 
 */
 
 if ( ! function_exists( 'xolo_dafault_page_breadcrumb' ) ) :
	function xolo_dafault_page_breadcrumb() {
		
		$xolo_bread_enable_customizer			= get_theme_mod('breadcrumb_enable_default_pg','1');
		$xolo_bread_meta_pg 					= get_post_meta( get_the_ID(), 'xolo_disable_breadcrumbs', true );
		
		if($xolo_bread_meta_pg !=='customizer'){
			$xolo_default_pg_breadcrumb = $xolo_bread_meta_pg;
		}else{
			$xolo_default_pg_breadcrumb = $xolo_bread_enable_customizer;
		}
		return $xolo_default_pg_breadcrumb;
	}
endif;

if ( ! function_exists( 'xolo_get_the_id' ) ) {

	/**
	 * Get post ID.
	 *
	 * @since  1.0.50
	 * @return string Current post/page ID.
	 */
	function xolo_get_the_id() {

		$post_id = 0;

		if ( is_home() && 'page' === get_option( 'show_on_front' ) ) {
			$post_id = get_option( 'page_for_posts' );
		} elseif ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) {
			$post_id = get_option( 'page_on_front' );
		} elseif ( is_singular() ) {
			$post_id = get_the_ID();
		}

		return apply_filters( 'xolo_get_the_id', $post_id );
	}
}

 if ( ! function_exists( 'xolo_get_site_layout' ) ) :
	function xolo_get_site_layout( $post = null ) {

		// Default site layout from Customizer.
		$default_site_layout = get_theme_mod('xolo_site_layout','contained');
		$site_layout         = $default_site_layout;

		$post = is_null( $post ) ? xolo_get_the_id() : $post;

		if ( $post ) {

			$post = get_post( $post );

			// Singular pages have meta settings for content layout.
			if ( ! empty( $post ) ) {
				$site_layout = get_post_meta( $post->ID, 'xolo_site_layout_meta', true );

				if ( empty( $site_layout ) ) {
					$site_layout = $default_site_layout;
				}
			}
		}

		return apply_filters( 'xolo_site_layout', $site_layout );
	}
endif;