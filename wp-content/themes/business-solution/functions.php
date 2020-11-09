<?php
/*
 * Business Solution functions
 * Since Version 1.0
 */
 
if ( ! function_exists( 'business_solution_setup' ) ) :

	function business_solution_setup() {

		load_theme_textdomain( 'business-solution' );

		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'title-tag' );

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 240,
				'width'       => 240,
				'flex-height' => true,
			)
		);

		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 9999 );
		
		$header_args = array(
			'width'           => 1600,
			'flex-width'    => true,
			'uploads'         => true,
			'random-default'  => true,	
			'header-text'     => false,
			
		);
				
		add_theme_support( 'custom-header', $header_args );	
	
		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'business-solution' ),
				'social'  => __( 'Social Links Menu', 'business-solution' ),
			)
		);
		
		$backbround_args = array(
			'default-color'          => '#ffffff',
			'default-image'          => '',
			'default-repeat'         => '',
			'default-position-x'     => '',
			'default-attachment'     => '',
			'wp-head-callback'       => '_custom_background_cb',
			'admin-head-callback'    => '',
			'admin-preview-callback' => ''
		);
		
		add_theme_support( 'custom-background', $backbround_args );		


		/*
		 * Enable support for Post Formats.		
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'status',
				'audio',
				'chat',
			)
		);

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );


		// Indicate widget sidebars can use selective refresh in the Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );
		
	}
	
endif; // business_solution_setup
add_action( 'after_setup_theme', 'business_solution_setup' );


add_action( 'after_setup_theme', 'business_solution_default_header' );
/**
 * Add Default Custom Header Image To
 * @return void
 */
function business_solution_default_header() {

    add_theme_support(
        'custom-header',
        apply_filters(
            'business_solution_custom_header_args',
            array(
                'default-text-color' => '#ffffff',
				'width'              => 1280,
				'height'             => 340,
				'flex-width'         => true,
				'flex-height'        => true,								
            )
        )
    );
}


/**
 *
 * @global int $content_width
 *
 *
 */
function business_solution_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'business_solution_content_width', 840 );
}
add_action( 'after_setup_theme', 'business_solution_content_width', 0 );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function business_solution_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'business-solution-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'business_solution_resource_hints', 10, 2 );

/**
 * Registers a widget area.
 */
function business_solution_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Main Sidebar', 'business-solution' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'business-solution' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'WooCommerce', 'business-solution' ),
			'id'            => 'woocommerce-sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in WooCommerce sidebar.', 'business-solution' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	
	/* Footer widget area */
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'business-solution' ),
			'id'            => 'footer-sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'business-solution' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'business-solution' ),
			'id'            => 'footer-sidebar-2',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'business-solution' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'business-solution' ),
			'id'            => 'footer-sidebar-3',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'business-solution' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);	
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 4', 'business-solution' ),
			'id'            => 'footer-sidebar-4',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'business-solution' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	
}
add_action( 'widgets_init', 'business_solution_widgets_init' );

/**
 * @since 1.0.0
 * An error notice that can be displayed if the Minimum PHP version is not met.
 */
function business_solution_php_not_met_notice() {
	?>
	<div class="notice notice-error is-dismissible" ><p><?php esc_html_e("Unable to activate the theme. Business Solution Theme requires Minimum PHP version 5.6", 'business-solution'); ?></p></div>
	<?php
}


if ( ! function_exists( 'business_solution_fonts_url' ) ) :
	/**
	 * @return string Google fonts URL for the theme.
	 */
	function business_solution_fonts_url() {

		/*
		 * Translators: If there are characters in your language that are not
		 * supported by "Open Sans", sans-serif;, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		$typography = _x( 'on', 'Open Sans font: on or off', 'business-solution' ); 
	
		if ( 'off' !== $typography ) {
			$font_families = array();
			
			$font_families[] = get_theme_mod('heading_font', 'Google Sans').':300,400,500';
			$font_families[] = get_theme_mod('body_font', 'Lora').':300,400,500';
			
	 
			$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
			);
			
			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			
			
		}
	   
		return esc_url( $fonts_url );
	
	}
endif;

/**
 * Handles JavaScript detection.
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 */
function business_solution_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'business_solution_javascript_detection', 0 );

/**
 * @since 1.0.0
 * Switches back to the previous theme if the minimum PHP version is not met.
 */
function business_solution_test_for_min_php() {

	// Compare versions.
	if ( version_compare( PHP_VERSION, business_solution_php_version, '<' ) ) {
		// Site doesn't meet themes min php requirements, add notice...
		add_action( 'admin_notices', 'business_solution_php_not_met_notice' );
		// ... and switch back to previous theme.
		switch_theme( get_option( 'theme_switched' ) );
		return false;

	};
}


/**
 * Enqueues scripts and styles.
 */
function business_solution_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'business-solution-fonts', business_solution_fonts_url(), array(), null );
	
	wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/css/bootstrap.css' ), array(), '3.3.6');

	// Add FontAwesome, used in the main stylesheet.
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/fonts/font-awesome/css/font-awesome.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'business-solution-style', get_stylesheet_uri() );

	// Load the html5 shiv.
	wp_enqueue_script( 'business-solution-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'business-solution-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'business-solution-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20191010', true );

	wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/js/bootstrap.js' ), array( 'jquery' ), '3.3.7', true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'business-solution-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160816' );
	}
	


	wp_enqueue_script( 'business-solution-script', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20191010', true );

	wp_localize_script(
		'business-solution-script',
		'screenReaderText',
		array(
			'expand'   => esc_html__( 'Expand child menu', 'business-solution' ),
			'collapse' => esc_html__( 'Collapse child menu', 'business-solution' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'business_solution_scripts' );



/**
 * Enqueue styles for the block-based editor.
 */
function business_solution_block_editor_styles() {
	// Add custom fonts.
	wp_enqueue_style( 'business-solution-fonts', business_solution_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'business_solution_block_editor_styles' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';




/**
 * @since 1.0.0
 * Set a constant that holds the theme's minimum supported PHP version.
 */
define( 'business_solution_php_version', '5.6' );

/**
 * Immediately after theme switch is fired we we want to check php version and
 * revert to previously active theme if version is below our minimum.
 */
add_action( 'after_switch_theme', 'business_solution_test_for_min_php' );


/**
 * @since 1.0.0
 * Add WooCommerce product support to theme
 */

add_action( 'after_setup_theme', 'business_solution_woocommerce_support' );
function business_solution_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );	
}


/**
 * @since 1.0.0
 * add home link.
 */
function business_solution_nav_wrap() {
  $wrap  = '<ul id="%1$s" class="%2$s">';
  $wrap .= '<li class="hidden-xs"><a href="/"><i class="fa fa-home"></i></a></li>';
  $wrap .= '%3$s';
  $wrap .= '</ul>';
  return $wrap;
}


/**
 *TGM Plugin activation.
*/

require get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'business_solution_activate_recommended_plugins' );

/**
 * Register recommended plugins.
 */

function business_solution_activate_recommended_plugins() {

	$plugins = array(
	
		array(
			'name'     => __( 'Elementor Drag and Drop Page Builder', 'business-solution' ),
			'slug'     => 'elementor',
			'required' => false,
		),
		
		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'required'  => false,
		),		
		
		array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'required'  => false,
		),
		
	);

	$config = array();

	tgmpa( $plugins, $config );

}

/**
 * WordPress Custom control style
 */
function business_solution_customizer_styles() { ?>
	<style>
		.custom-help-control {
			padding:9px; 
			border:1px solid #CCCCCC; 
			background-color:#FFFFFF;
		}	
	</style>
	<?php
}
add_action( 'customize_controls_print_styles', 'business_solution_customizer_styles', 999 );


/*
 * https://developer.wordpress.org/reference/hooks/admin_notices/
 * Displays theme info / quick help 
 */
if ( isset( $_GET['hide_admin_notice'] ) ) {
		update_option('hide_admin_notice', true);
} else {
	$business_solution_pro_info = get_option('hide_admin_notice', false);
	if (!$business_solution_pro_info){ 
		add_action( 'admin_notices', 'business_solution_pro_admin_notice_info' );
	}
}

define ('business_solution_pro_theme_url', 'https://wpfreetheme.space/product/wordpress-business-solution-theme/');
define ('business_solution_pro_tutorial', 'https://wpfreetheme.space/wordpress/');

if(!function_exists('business_solution_pro_admin_notice_info')):

function business_solution_pro_admin_notice_info() {
    $class = 'notice notice-info is-dismissible';
    $message = __( 'Use Appearance > Customize > Theme Options to manage Sliders, Hero Content, Top Banner etc: ', 'business-solution' );
 	$dismiss = __( 'Hide this Notice', 'business-solution');
	$tutorial = __( 'Tutorials', 'business-solution');
	$pro_notice =  __( 'Premium Features | Install Free Demo', 'business-solution');
    printf( '<div class="%1$s"> <p><strong><span>%2$s</span></strong> &nbsp;&nbsp; 
	<strong><a href="%3$s" target="_blank"  class="dismiss-notice" style="color:#1b864a;text-transform: uppercase;">%4$s</a></strong> &nbsp;&nbsp;
	<strong><a href="%5$s" target="_blank"  class="dismiss-notice">%6$s</a></strong> &nbsp;&nbsp;
	<em><a href="?hide_admin_notice" target="_self"  class="dismiss-notice">%7$s</a></em> </p></div>', 
	esc_attr( $class ), 
	esc_html( $message ), 
	esc_url( business_solution_pro_theme_url ), 
	esc_html( $pro_notice ), 
	esc_url( business_solution_pro_tutorial ), 
	esc_html( $tutorial ),
	esc_html( $dismiss ) ); 
}

endif;


// Display fontawesome search icon in menus and toggle search form 
add_filter('wp_nav_menu_items', 'business_solution_search_form', 10, 2);

function business_solution_search_form($items, $args) {
if( $args->theme_location == 'primary' )
       $items .= '<li class="menu-search-popup" tabindex="0" ><a class="search_icon"><i class="fa fa-search"></i></a><div  class="spicewpsearchform" >'. get_search_form(false) .'</div></li></ul>';
       return $items;
}

