<?php
function xolo_edd_archives( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

	$wp_customize->add_panel(
		'xolo_edd', array(
			'priority' => 10,
			'title' => esc_html__( 'Easy Digital Download', 'xolo' ),
		)
	);	

/*=========================================
	Xolo Single Blog
=========================================*/
	$wp_customize->add_section(
        'xolo_edd_product_archive',
        array(
        	'priority'      => 1,
            'title' 		=> __('Product Archive','xolo'),
			'panel'  		=> 'xolo_edd',
		)
    );
	if ( class_exists( 'Xolo_Customize_Control_Radio_Image' ) ) {
		// Default pages
		$wp_customize->add_setting(
			'xolo_edd_archives_layout', array(
				'sanitize_callback' => 'xolo_sanitize_select',
				'default' => 'xolo_rsb',
			)
		);

		$wp_customize->add_control(
			new Xolo_Customize_Control_Radio_Image(
				$wp_customize, 'xolo_edd_archives_layout', array(
					'label'     => esc_html__( 'Layout', 'xolo' ),
					'section'   => 'xolo_edd_product_archive',
					'priority'  => 1,
					'choices'   => array(
						'xolo_lsb' => array(
							'url' => apply_filters( 'xolo_lsb', esc_url(trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/images/lsb.svg' )),
						),
						'xolo_fullwidth' => array(
							'url' => apply_filters( 'xolo_fullwidth', esc_url(trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/images/full-width.svg' )),
						),
						'xolo_rsb' => array(
							'url' => apply_filters( 'xolo_rsb', esc_url(trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/images/rsb.svg' )),
						),
					),
				)
			)
		);
	}
	
	// Product Type // 
	$wp_customize->add_setting( 
		'edd_product_types' , 
			array(
			'default' => __('grid', 'xolo' ),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'xolo_sanitize_select',
		) 
	);

	$wp_customize->add_control(
	'edd_product_types' , 
		array(
			'label'          => __( 'Product Type', 'xolo' ),
			'section'        => 'xolo_edd_product_archive',
			'type'           => 'select',
			'priority'      => 2,
			'choices'        => 
			array(
				'grid'       => __( 'Grid', 'xolo' ),
				'list' => __( 'List', 'xolo' )
			) 
		) 
	);	
	
	// Grid Layout // 
	$wp_customize->add_setting( 
		'edd_archive_column' , 
			array(
			'default' => __('6', 'xolo' ),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'xolo_sanitize_select',
		) 
	);

	$wp_customize->add_control(
	'edd_archive_column' , 
		array(
			'label'          => __( 'Grid Layout', 'xolo' ),
			'section'        => 'xolo_edd_product_archive',
			'type'           => 'select',
			'priority'      => 2,
			'choices'        => 
			array(
				'12'       => __( '1 Column ', 'xolo' ),
				'6' => __( '2 Columns', 'xolo' ),
				'4' => __( '3 Columns', 'xolo' ),
				'3' => __( '4 Columns', 'xolo' )
			) 
		) 
	);	
	
	// Structure
	 $wp_customize->add_setting( 
		'edd_product_structure' , 
			array(
			'default'   => array(
							'feature-image',
							'title',
							'meta',
							'description',
						),
		'sanitize_callback' => 'xolo_sanitize_sortable',
		) 
	);
	
	$wp_customize->add_control( 
	new Xolo_Control_Sortable( $wp_customize, 'edd_product_structure', 
		array(
			'label'      => __( 'Structure', 'xolo' ),
			'section'     => 'xolo_edd_product_archive',
			'priority'      => 3,
			'choices'     => array(				
				'feature-image'   => __( 'Feature Image', 'xolo' ),
				'title'     => __( 'Title', 'xolo' ),
				'meta'     => __( 'Meta', 'xolo' ),
				'description'     => __( 'Description', 'xolo' ),
			),
			
		) ) 
	);	
	
	// Meta Layout
	 $wp_customize->add_setting( 
		'edd_archive_meta_layout' , 
			array(
			'default'   => array(
							'price',
							'category',
							'cart',
						),
		'sanitize_callback' => 'xolo_sanitize_sortable',
		) 
	);
	
	$wp_customize->add_control( 
	new Xolo_Control_Sortable( $wp_customize, 'edd_archive_meta_layout', 
		array(
			'label'      => __( 'Meta', 'xolo' ),
			'section'     => 'xolo_edd_product_archive',
			'priority'      => 3,
			'choices'     => array(
				'price'   => __( 'Price', 'xolo' ),
				'cart'     => __( 'Add to Cart', 'xolo' ),
				'category'     => __( 'Category', 'xolo' ),
				'author'     => __( 'Author', 'xolo' ),
				'date'     => __( 'Date', 'xolo' ),
				'tags'     => __( 'Tags', 'xolo' ),
			),
		) ) 
	);
	
	// Show Author Box
	$wp_customize->add_setting(
		'xolo_enable_edd_sorting_bar'
			,array(
			'default' => '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'xolo_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
	'xolo_enable_edd_sorting_bar',
		array(
			'type' => 'checkbox',
			'label' => __('Enable Sorting Bar','xolo'),
			'section' => 'xolo_edd_product_archive',
			'priority'      => 4,
		)
	);
	
	// Show Author Box
	$wp_customize->add_setting(
		'xolo_enable_edd_grid_list'
			,array(
			'default' => '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'xolo_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
	'xolo_enable_edd_grid_list',
		array(
			'type' => 'checkbox',
			'label' => __('Enable Grid List','xolo'),
			'section' => 'xolo_edd_product_archive',
			'priority'      => 5,
		)
	);
	
	// Product Image Size// 
	if ( class_exists( 'Xolo_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'edd_archive_img_width',
			array(
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'xolo_sanitize_range_value',
				'transport'         => 'postMessage'
			)
		);
		$wp_customize->add_control( 
		new Xolo_Customizer_Range_Control( $wp_customize, 'edd_archive_img_width', 
			array(
				'label'      => __( 'Media Image Width (%)', 'xolo' ),
				'section'  => 'xolo_edd_product_archive',
				'priority'      => 6,
				 'media_query'   => true,
                'input_attr'    => array(
                    'mobile'  => array(
                        'min'           => 0,
                        'max'           => 100,
                        'step'          => 1,
                        'default_value' => 50,
                    ),
                    'tablet'  => array(
                        'min'           => 0,
                        'max'           => 100,
                        'step'          => 1,
                        'default_value' => 50,
                    ),
                    'desktop' => array(
                        'min'           => 0,
                        'max'           => 100,
                        'step'          => 1,
                        'default_value' => 50,
                    ),
                ),
			) ) 
		);
	}

	// Cart button label // 
	$wp_customize->add_setting(
    	'cart_btn_lbl',
    	array(
			'title' 		=> __('Add to Cart','xolo'),
			'priority'      => 11,
			'sanitize_callback' => 'xolo_sanitize_text',
			'capability' => 'edit_theme_options',
			'transport'         => $selective_refresh,
		)
	);	

	$wp_customize->add_control( 
		'cart_btn_lbl',
		array(
		    'label'   => esc_html__('Add to Cart','xolo'),
		    'section' => 'xolo_edd_product_archive',
			'settings'=> 'cart_btn_lbl',
			'type' => 'text',
		)  
	);
	
	// Variable button label // 
	$wp_customize->add_setting(
    	'cart_variable_btn_lbl',
    	array(
			'title' 		=> __('View Details','xolo'),
			'priority'      => 11,
			'sanitize_callback' => 'xolo_sanitize_text',
			'capability' => 'edit_theme_options',
			'transport'         => $selective_refresh,
		)
	);	

	$wp_customize->add_control( 
		'cart_variable_btn_lbl',
		array(
		    'label'   => esc_html__('View Details','xolo'),
		    'section' => 'xolo_edd_product_archive',
			'settings'=> 'cart_variable_btn_lbl',
			'type' => 'text',
		)  
	);
	
	/*=========================================
	Xolo Single Product
	=========================================*/
	$wp_customize->add_section(
        'xolo_edd_product_Single',
        array(
        	'priority'      => 2,
            'title' 		=> __('Product Single','xolo'),
			'panel'  		=> 'xolo_edd',
		)
    );
	if ( class_exists( 'Xolo_Customize_Control_Radio_Image' ) ) {
		// Default pages
		$wp_customize->add_setting(
			'xolo_edd_single_layout', array(
				'sanitize_callback' => 'xolo_sanitize_select',
				'default' => 'xolo_rsb',
			)
		);

		$wp_customize->add_control(
			new Xolo_Customize_Control_Radio_Image(
				$wp_customize, 'xolo_edd_single_layout', array(
					'label'     => esc_html__( 'Layout', 'xolo' ),
					'section'   => 'xolo_edd_product_Single',
					'priority'  => 1,
					'choices'   => array(
						'xolo_lsb' => array(
							'url' => apply_filters( 'xolo_lsb', esc_url(trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/images/lsb.svg' )),
						),
						'xolo_fullwidth' => array(
							'url' => apply_filters( 'xolo_fullwidth', esc_url(trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/images/full-width.svg' )),
						),
						'xolo_rsb' => array(
							'url' => apply_filters( 'xolo_rsb', esc_url(trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/images/rsb.svg' )),
						),
					),
				)
			)
		);
	}
	
	// Structure
	 $wp_customize->add_setting( 
		'edd_product_single_structure' , 
			array(
			'default'   => array(
							'feature-image',
							'content',
						),
		'sanitize_callback' => 'xolo_sanitize_sortable',
		) 
	);
	
	$wp_customize->add_control( 
	new Xolo_Control_Sortable( $wp_customize, 'edd_product_single_structure', 
		array(
			'label'      => __( 'Structure', 'xolo' ),
			'section'     => 'xolo_edd_product_Single',
			'priority'      => 3,
			'choices'     => array(				
				'feature-image'   => __( 'Feature Image', 'xolo' ),
				'content'     => __( 'Content', 'xolo' ),
			),
			
		) ) 
	);	
	
	// Related Item // 
	$wp_customize->add_setting(
		'edd_related'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'xolo_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'edd_related',
		array(
			'type' => 'hidden',
			'label' => __('Related Item','xolo'),
			'section' => 'xolo_edd_product_Single',
			'priority' => 15,
		)
	);
	
	 $wp_customize->add_setting( 'xolo_related_item_type', array(
      'capability'        => 'edit_theme_options',
      'default'           => 'categories',
      'transport'         => 'postMessage',
      'sanitize_callback' => 'xolo_sanitize_select',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize, 'xolo_related_item_type', array(
                'label'       => __( 'Related Item', 'xolo' ),
                'section'     => 'xolo_edd_product_Single',
                'type'        => 'select',
                'priority'    => 16,
                'choices'     => array(
                    'categories'       =>  __( 'EDD Categories', 'xolo' ),
                    'tags'     =>  __( 'EDD Tags', 'xolo' ),
                ),
            )
        )
    );
	
	if ( class_exists( 'Xolo_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'xolo_related_item_no',
			array(
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'xolo_sanitize_range_value',
				'transport'         => 'postMessage'
			)
		);
		$wp_customize->add_control( 
		new Xolo_Customizer_Range_Control( $wp_customize, 'xolo_related_item_no', 
			array(
				'label'      => __( 'Number of Item', 'xolo' ),
				'section'  => 'xolo_edd_product_Single',
				'priority'      => 17,
				 'media_query'   => false,
                'input_attr'    => array(
                    'desktop' => array(
                        'min'           => 0,
                        'max'           => 100,
                        'step'          => 1,
                        'default_value' => 4,
                    ),
                ),
			) ) 
		);
	}
	
	/*=========================================
	Xolo Product Styler
	=========================================*/
	$wp_customize->add_section(
        'xolo_edd_product_styler',
        array(
        	'priority'      => 3,
            'title' 		=> __('Product Styler','xolo'),
			'panel'  		=> 'xolo_edd',
		)
    );
	
	// EDD text color
	$wp_customize->add_setting(
	'archive_edd_txt_clr', 
	array(
		'default' => '#383E41',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color'
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_txt_clr', 
			array(
				'label'      => __( 'Content Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_txt_clr'
			) 
		) 
	);
	
	// EDD link color
	$wp_customize->add_setting(
	'archive_edd_title_clr', 
	array(
		'default' => '#383E41',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color',
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_title_clr', 
			array(
				'label'      => __( 'Title Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_title_clr'
			) 
		) 
	);
	
	// EDD link hover color
	$wp_customize->add_setting(
	'archive_edd_ttl_hov_clr', 
	array(
		'default' => '#381CC5',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color'
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_ttl_hov_clr', 
			array(
				'label'      => __( 'Title Hover Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_ttl_hov_clr'
			) 
		) 
	);
	
	// EDD icon color
	$wp_customize->add_setting(
	'archive_edd_icon_clr', 
	array(
		'default' => '#492cdd',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color',
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_icon_clr', 
			array(
				'label'      => __( 'Meta Icon Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_icon_clr'
			) 
		) 
	);
	
	// EDD meta color
	$wp_customize->add_setting(
	'archive_edd_meta_clr', 
	array(
		'default' => '#383E41',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color'
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_meta_clr', 
			array(
				'label'      => __( 'Meta Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_meta_clr'
			) 
		) 
	);
	
	// Edd Button color
	$wp_customize->add_setting(
	'archive_edd_btn_clr', 
	array(
		'default' => '#ffffff',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color',
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_btn_clr', 
			array(
				'label'      => __( 'Button Text Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_btn_clr'
			) 
		) 
	);
	
	// Edd Button BG color
	$wp_customize->add_setting(
	'archive_edd_btn_bg_clr', 
	array(
		'default' => '#492cdd',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color',
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_btn_bg_clr', 
			array(
				'label'      => __( 'Button Background Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_btn_bg_clr'
			) 
		) 
	);
	
	// Edd Tabs color
	$wp_customize->add_setting(
	'archive_edd_tabs_clr', 
	array(
		'default' => '#ffffff',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color',
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_tabs_clr', 
			array(
				'label'      => __( 'Tabs Text Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_tabs_clr'
			) 
		) 
	);
	
	// Edd Tabs BG color
	$wp_customize->add_setting(
	'archive_edd_tabs_bg_clr', 
	array(
		'default' => '#492cdd',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color',
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_tabs_bg_clr', 
			array(
				'label'      => __( 'Tabs Background Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_tabs_bg_clr'
			) 
		) 
	);
	
	// Edd Bg color
	$wp_customize->add_setting(
	'archive_edd_bg_clr', 
	array(
		'default' => '#',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'xolo_sanitize_alpha_color',
    ));
	
	$wp_customize->add_control( 
		new Xolo_Color_Control
		($wp_customize, 
			'archive_edd_bg_clr', 
			array(
				'label'      => __( 'Product Background Color', 'xolo' ),
				'section'    => 'xolo_edd_product_styler',
				'settings'   => 'archive_edd_bg_clr'
			) 
		) 
	);
}
add_action( 'customize_register', 'xolo_edd_archives' );	