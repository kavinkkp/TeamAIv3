<?php
/**
 * Business Solution Customizer functionality
 */

if ( ! function_exists( 'business_solution_header_style' ) ) :
	/**
	 * Styles the header text displayed on the site.
	 *
	 * Create your own business_solution_header_style() function to override in a child theme.
	 *
	 * @see business_solution_custom_header_and_background().
	 */
	function business_solution_header_style() {
		// If the header text option is untouched, let's bail.
		if ( display_header_text() ) {
			return;
		}

		// If the header text has been hidden.
		?>
		<style type="text/css" id="business-solution-header-css">
		.site-branding {
			margin: 0 auto 0 0;
		}

		.site-branding .site-title,
		.site-description {
			clip: rect(1px, 1px, 1px, 1px);
			position: absolute;
		}
		</style>
		<?php
	}
endif; // business_solution_header_style

add_action( 'customize_controls_enqueue_scripts', function() {

	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script(
		'wptrt-customize-section-button',
		get_theme_file_uri( 'js/customize-controls.js' ),
		[ 'customize-controls' ],
		$version,
		true
	);

	wp_enqueue_style(
		'wptrt-customize-section-button',
		get_theme_file_uri( 'css/customize-controls.css' ),
		[ 'customize-controls' ],
 		$version
	);

} );

/**
 * Adds postMessage support for site title and description for the Customizer.
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function business_solution_customize_register( $wp_customize ) {

/**
 * Customize Section Button Class.
 *
 * Adds a custom "button" section to the WordPress customizer.
 *
 * @author    WPTRT <themes@wordpress.org>
 * @copyright 2019 WPTRT
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://github.com/WPTRT/customize-section-button
 */

class Business_Solution_Button extends WP_Customize_Section {


	public $type = 'wptrt-button';
	public $button_text = '';
	public $button_url = '';
	public $priority = 0;

	public function json() {

		$json       = parent::json();
		$theme      = wp_get_theme();
		$button_url = $this->button_url;

		// Fall back to the `Theme URI` defined in `style.css`.
		if ( ! $this->button_url && $theme->get( 'ThemeURI' ) ) {

			$button_url = $theme->get( 'ThemeURI' );

		// Fall back to the `Author URI` defined in `style.css`.
		} elseif ( ! $this->button_url && $theme->get( 'AuthorURI' ) ) {

			$button_url = $theme->get( 'AuthorURI' );
		}

		$json['button_text'] = $this->button_text ? $this->button_text : $theme->get( 'Name' );
		$json['button_url']  = esc_url( $button_url );

		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function render_template() { ?>

		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">

			<h3 class="accordion-section-title">
				{{ data.title }}

				<# if ( data.button_text && data.button_url ) { #>
					<a href="{{ data.button_url }}" class="button button-secondary alignright" target="_blank" rel="external nofollow noopener noreferrer">{{ data.button_text }}</a>
				<# } #>
			</h3>
		</li>
	<?php }
}

	$wp_customize->register_section_type( Business_Solution_Button::class );

	$wp_customize->add_section(
		new Business_Solution_Button( $wp_customize, 'business-solution', [
			'title'       => __( 'Premium Features', 'business-solution' ),
			'button_text' => __( 'Learn More', 'business-solution' ),
			'button_url'  => 'https://wpfreetheme.space/product/wordpress-business-solution-theme/'
		] )
	);


	/* theme settings */
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'            => '.site-title a',
				'container_inclusive' => false,
				'render_callback'     => 'business_solution_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'            => '.site-description',
				'container_inclusive' => false,
				'render_callback'     => 'business_solution_customize_partial_blogdescription',
			)
		);
	}
	
	require get_template_directory() .'/inc/color-picker/alpha-color-picker.php';
	
	/***************** 
	 * Theme options *
	 ****************/

	$wp_customize->add_panel( 'theme_options' , array(
		'title'      => __( 'Theme Options', 'business-solution' ),
		'priority'   => 1,
	) );
	
	// header and footer
	$wp_customize->add_section( 'theme_header' , array(
		'description' => __( 'Add header social menu :- create a menu with social links and set menu as social.', 'business-solution' ),
		'title'      => __( 'Theme Header', 'business-solution' ),
		'priority'   => 1,
		'panel' => 'theme_options',
	) );
	
	
	// banner image
	$wp_customize->add_setting( 'banner_image' , 
		array(
			'default' 		=> '',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'banner_image' ,
		array(
			'label'          => __( 'Banner Image', 'business-solution' ),
			'description'	=> __('Upload banner image', 'business-solution'),
			'settings'  => 'banner_image',
			'section'        => 'theme_header',
		))
	);
	
	$wp_customize->add_setting('banner_link' , array(
		'default'    => '#',
		'sanitize_callback' => 'esc_url_raw',
	));
	

	$wp_customize->add_control('banner_link' , array(
		'label' => __('Banner Link', 'business-solution' ),
		'section' => 'theme_header',
		'type'=> 'url',
	) );
	
	//header shortcode
	$wp_customize->add_setting('header_shortcode' , array(
		'default'    => '',
		'sanitize_callback' => 'sanitize_text_field',
	));
	
	

	$wp_customize->add_control('header_shortcode' , array(
		'label' => __('Add Header Shortcode', 'business-solution' ),
		'section' => 'theme_header',
		'type'=> 'text',
	) );	
		

	//add settings page
	require get_template_directory() . '/inc/slider-settings.php';	
	
		
	//hero content 

	$wp_customize->add_section( 'hero_section' , array(
		'title'      => __( 'Hero Content', 'business-solution' ),
		'priority'   => 2,
		'panel' => 'theme_options',
	) );
	
	//0
	$wp_customize->add_setting('hero_border' , array(
		'default'    => 0,
		'sanitize_callback' => 'absint',
	));
	
	$wp_customize->add_control('hero_border' , array(
		'label' => __('Top Border (px)', 'business-solution' ),
		'section' => 'hero_section',
		'type'=> 'number',
	) );	
	
	//hero section
	$wp_customize->add_setting('hero_title' , array(
		'default'    => '',
		'sanitize_callback' => 'sanitize_text_field',
	));
	
	$wp_customize->add_control('hero_title' , array(
		'label' => __('Title', 'business-solution' ),
		'section' => 'hero_section',
		'type'=> 'text',
	) );
	
	//
	$wp_customize->add_setting('hero_description' , array(
		'default'    => '',
		'sanitize_callback' => 'sanitize_text_field',
	));
	
	$wp_customize->add_control('hero_description' , array(
		'label' => __('Hero Description', 'business-solution' ),
		'section' => 'hero_section',
		'type'=> 'text',
	) );
	
	//3
	$wp_customize->add_setting('hero_button' , array(
		'default'    => __('Start Here', 'business-solution' ),
		'sanitize_callback' => 'sanitize_text_field',
	));
	
	$wp_customize->add_control('hero_button' , array(
		'label' => __('Button Text', 'business-solution' ),
		'section' => 'hero_section',
		'type'=> 'text',
	) );	
	
		
	//4
	$wp_customize->add_setting('hero_link' , array(
		'default'    => '#',
		'sanitize_callback' => 'esc_url_raw',
	));
	
	$wp_customize->add_control('hero_link' , array(
		'label' => __('Button Link', 'business-solution' ),
		'section' => 'hero_section',
		'type'=> 'url',
	) );	

	// Add page background color setting and control.
	$wp_customize->add_setting(
		'page_background_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'page_background_color',
			array(
				'label'   => __( 'Page Background Color', 'business-solution' ),
				'section' => 'colors',
			)
		)
	);


	// Add link color setting and control.
	$wp_customize->add_setting(
		'link_color',
		array(
			'default'           => '#063d62',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'link_color',
			array(
				'label'   => __( 'Link Color', 'business-solution' ),
				'section' => 'colors',
			)
		)
	);

	// Add main text color setting and control.
	$wp_customize->add_setting(
		'main_text_color',
		array(
			'default'           => '#1a1a1a',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'main_text_color',
			array(
				'label'   => __( 'Main Text Color', 'business-solution' ),
				'section' => 'colors',
			)
		)
	);
	
	// layout 2
	$wp_customize->add_setting( 'header_layout_2' , array(
		'default'    => 0,
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'business_solution_sanitize_checkbox',
	));

	$wp_customize->add_control('header_layout_2' , array(
		'label' => __('Set Header List Layout','business-solution' ),
		'section' => 'theme_header',
		'type'=> 'checkbox',
	));	
	
	// woo menubar background
 
		$wp_customize->add_setting(
			'woocommerce_menubar_color',
			array(
				'default'     => 'rgba(231,173,36,0)',
				'type'        => 'theme_mod',			
				'transport'   => 'refresh',
				'sanitize_callback' => 'business_solution_rgba_sanitization_callback',
			)
		);
		
		// background Alpha Color Picker control
		$wp_customize->add_control(
			new Business_Solution_Customize_Alpha_Color_Control(
				$wp_customize,
				'woocommerce_menubar_color',
				array(
					'label'         =>  __('Menubar Background (Header List Layout)','business-solution' ),
					'section'       => 'theme_header',
					'settings'      => 'woocommerce_menubar_color',
					'show_opacity'  => true, // Optional.
					'palette'	=> business_solution_color_codes(),
				)
			)
		);			
	
	// woo menubar text color
	$wp_customize->add_setting(
		'woocommerce_menubar_text_color',
		array(
			'default'           => '#2b2b2b',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'woocommerce_menubar_text_color',
			array(
				'label'   => __( 'Menu Color (Header List Layout) ', 'business-solution' ),
				'section' => 'theme_header',
			)
		)
	);
	

	// Add header text color setting and control.
	$wp_customize->add_setting(
		'header_text_color',
		array(
			'default'           => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'header_text_color',
			array(
				'label'   => __( 'Headet Text Color', 'business-solution' ),
				'section' => 'theme_header',
			)
		)
	);
	
	// Header text colour
	$wp_customize->add_setting(
		'header_bg_color',
		array(
			'default'           => '#fff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'header_bg_color',
			array(
				'label'   => __( 'Header Background Color', 'business-solution' ),
				'section' => 'theme_header',
			)
		)
	);
	
	//header tel
	$wp_customize->add_setting('header_telephone' , array(
		'default'    => '1-000-123-4567',
		'sanitize_callback' => 'sanitize_text_field',
	));
	
	

	$wp_customize->add_control('header_telephone' , array(
		'label' => __('Tel', 'business-solution' ),
		'section' => 'theme_header',
		'type'=> 'text',
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'header_telephone', array(
		'selector' => '.contact-info',
	) );
	
	//header email
	$wp_customize->add_setting('header_email' , array(
		'default'    => 'edit@mailserver.com',
		'sanitize_callback' => 'sanitize_email',
	));

	$wp_customize->add_control('header_email' , array(
		'label' => __('Email', 'business-solution' ),
		'section' => 'theme_header',
		'type'=> 'text',
	) );
			
	//header address
	$wp_customize->add_setting('header_address' , array(
		'default'    => __('Street, City', 'business-solution'),
		'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('header_address' , array(
		'label' => __('Address', 'business-solution' ),
		'section' => 'theme_header',
		'type'=> 'text',
	) );
	
	
	// 5 Typography

	$wp_customize->add_section( 'typography_section' , array(
		'title'      => __('Typography', 'business-solution' ),			 
		'description'=> __('Change default fonts. Enter any Google Font name.', 'business-solution' ),
		'panel' => 'theme_options',
	));


	$wp_customize->add_setting( 'heading_font' , array(
		'default'    => 'Google Sans',
		'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('heading_font' , array(
		'label' => __('Heading Font Family', 'business-solution' ),
		'section' => 'typography_section',
		'type' => 'text',
	) );
	
	
	$wp_customize->add_setting( 'body_font' , array(
		'default'    => 'Lora',
		'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('body_font' , array(
		'label' => __('Body Font Family', 'business-solution' ),
		'section' => 'typography_section',
		'type' => 'text',
	));	
	
	//'choices' => business_solution_font_family(),
	
	
	// 5 layout section 

	$wp_customize->add_section( 'layout_section' , array(
		'title'      => __('Layout', 'business-solution' ),			 
		'description'=> __('Chanege site layout to fluid / box mode', 'business-solution' ),
		'panel' => 'theme_options',
	));
 
	$wp_customize->add_setting( 'box_layout_mode' , array(
		'default'    => false,
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'business_solution_sanitize_checkbox',
	));

	$wp_customize->add_control('box_layout_mode' , array(
		'label' => __('Set Box Layout mode','business-solution' ),
		'section' => 'layout_section',
		'type'=> 'checkbox',
	));
	
	// sidebar position
	$wp_customize->add_setting( 'woo_sidebar_position' , array(
		'default'    => 'left',
		'sanitize_callback' => 'business_solution_sanitize_select',
	));

	$wp_customize->add_control('woo_sidebar_position' , array(
		'label' => __('WooCommerce Sidebar position', 'business-solution' ),
		'section' => 'layout_section',
		'type' => 'select',
		'choices' => array(
			'right' => __('Right', 'business-solution' ),
			'left' => __('Left', 'business-solution' ),
			'none' => __('No Sidebar', 'business-solution' ),
		),
	) );
	
	
		
	// Add footer color setting and control.
	$wp_customize->add_setting(
		'footer_text_color',
		array(
			'default'           => '#eaeaea',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	
	// 7 footer section
	$wp_customize->add_section( 'theme_footer' , array(
		'title'      => __( 'Theme Footer', 'business-solution' ),
		'panel' => 'theme_options',
	) );	

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'footer_text_color',
			array(
				'label'   => __( 'Footer Text Color', 'business-solution' ),
				'section' => 'theme_footer',
			)
		)
	);
	

	$wp_customize->add_setting('footer_border' , array(
		'default'    => 0,
		'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control('footer_border' , array(
		'label' => __('Footer Border Width', 'business-solution' ),
		'section' => 'theme_footer',
		'type'=> 'number',
	) );	
	
	
	// Add footer background color setting and control.
	$wp_customize->add_setting(
		'footer_bg_color',
		array(
			'default'           => '#035186',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'footer_bg_color',
			array(
				'label'   => __( 'Footer Background Color', 'business-solution' ),
				'section' => 'theme_footer',
			)
		)
	);
	
	
	// footer copyright text
	$wp_customize->add_setting( 'footer_text' , array(
		'default'    => __("Copyright Text", 'business-solution'),
		'sanitize_callback' => 'sanitize_textarea_field',
	));	
	
	$wp_customize->add_control('footer_text' , array(
		'label' => __('Footer Bottom Text', 'business-solution'),
		'section' => 'theme_footer',
		'type'=>'textarea',
	) );
	
	$wp_customize->selective_refresh->add_partial( 'footer_text', array(
		'selector' => '.site-info',
	) );
	
	
	
//end of settings
	
}
add_action( 'customize_register', 'business_solution_customize_register', 11 );

/**
 * Render the site title for the selective refresh partial.
 */
function business_solution_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Business Solution 1.2
 * @see business_solution_customize_register()
 *
 * @return void
 */
function business_solution_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Enqueues front-end CSS for color scheme.
 * @see wp_add_inline_style()
 */
function business_solution_color_scheme_css() {

	$scheme_css = business_solution_get_theme_css();

	wp_add_inline_style( 'business-solution-style', $scheme_css );
}
add_action( 'wp_enqueue_scripts', 'business_solution_color_scheme_css' );


/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 *
 */
function business_solution_customize_preview_js() {
	wp_enqueue_script( 'business-solution-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20160816', true );
}
add_action( 'customize_preview_init', 'business_solution_customize_preview_js' );

/**
 * Theme options
 */

require get_template_directory() . '/inc/custom-css.php';

/*
 * Get product categories
 */

$business_solution_product_categories = business_solution_get_product_categories();

function business_solution_get_product_categories(){

	$args = array(
			'taxonomy' => 'product_cat',
			'orderby' => 'date',
			'order' => 'ASC',
			'show_count' => 1,
			'pad_counts' => 0,
			'hierarchical' => 0,
			'title_li' => '',
			'hide_empty' => 1,
	);

	$categories = get_categories($args);

	$arr = array();
	$arr['0'] = esc_html__('-Select Category-', 'business-solution') ;
	foreach($categories as $category){
		$arr[$category->term_id] = $category->name;
	}
	return $arr;
}


/* 
 * check valid font has been selected 
 */
function business_solution_sanitize_font_family( $value ) {
    if ( array_key_exists($value, business_solution_font_family()) )  {   
    	return $value;
	} else {
		return "Google Sans, Sans Serif";
	}
}

function business_solution_font_family(){

	$google_fonts = array(  "Google Sans" => "Google Sans",
							"Open sans" => "Open sans",
							"Oswald" => "Oswald",
							"Lora" => "Lora",
							"Raleway" => "Raleway",
						);
						
	return ($google_fonts);
}


if( class_exists( 'WP_Customize_Control' ) ):
	class Business_Solution_Customize_Help_Control extends WP_Customize_Control {

		public function render_content() {
		?>
			<label>
				<span class="customize-control-title custom-help-control"><?php echo esc_html( $this->label ); ?></span>
			</label>
		<?php
		}
	}
endif;


/**
 * Sanitize colors.
 *
 * @since 1.0.0
 * @param array $value The color.
 * @return array
 */
function business_solution_rgba_sanitization_callback( $value ) {
	// This pattern will check and match 3/6/8-character hex, rgb, rgba, hsl, & hsla colors.
	$pattern = '/^(\#[\da-f]{3}|\#[\da-f]{6}|\#[\da-f]{8}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/';
	\preg_match( $pattern, $value, $matches );
	// Return the 1st match found.
	if ( isset( $matches[0] ) ) {
		if ( is_string( $matches[0] ) ) {
			return $matches[0];
		}
		if ( is_array( $matches[0] ) && isset( $matches[0][0] ) ) {
			return $matches[0][0];
		}
	}
	// If no match was found, return an empty string.
	return '';
}