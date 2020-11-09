<?php

namespace Elementor;

use ElementsKit_Lite\Libs\Framework\Attr;
use Elementor\ElementsKit_Widget_Google_Map_Handler as Handler;

defined('ABSPATH') || exit;

class ElementsKit_Widget_Google_Map extends Widget_Base {

	public $base;

    public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}

	public function get_script_depends() {
		return ['ekit-google-map-api', 'ekit-google-gmaps'];
	}

	public function get_name() {
		return Handler::get_name();
	}

	public function get_title() {
		return Handler::get_title();
	}

	public function get_icon() {
		return Handler::get_icon();
	}

	public function get_categories() {
		return Handler::get_categories();
	}

	protected function _register_controls() {

		$this->start_controls_section( 'map_settings', [
			'label' => esc_html__( 'Settings', 'elementsKit-lite' )
		]);

		$this->add_control( 'map_type', [
			'label'       	=> esc_html__( 'Map Type', 'elementsKit-lite' ),
			'type' 			=> Controls_Manager::SELECT,
			'default' 		=> 'basic',
			'options' 		=> [
				'basic'  	=> esc_html__( 'Basic', 'elementsKit-lite' ),
				'marker'  	=> esc_html__( 'Multiple Marker', 'elementsKit-lite' ),
				'static'  	=> esc_html__( 'Static', 'elementsKit-lite' ),
				'polyline'  => esc_html__( 'Polyline', 'elementsKit-lite' ),
				'polygon'  	=> esc_html__( 'Polygon', 'elementsKit-lite' ),
				'overlay'  	=> esc_html__( 'Overlay', 'elementsKit-lite' ),
				'routes'  	=> esc_html__( 'With Routes', 'elementsKit-lite' ),
				'panorama'  => esc_html__( 'Panorama', 'elementsKit-lite' ),
			]
		]);

		$this->add_control( 'map_address_type',
			[
				'label' => __( 'Address Type', 'elementsKit-lite' ),
				'type' => Controls_Manager::CHOOSE,
                'default' => 'coordinates',
				'options' => [
					'address' => [
						'title' => __( 'Address', 'elementsKit-lite' ),
						'icon' => 'fa fa-map',
					],
					'coordinates' => [
						'title' => __( 'Coordinates', 'elementsKit-lite' ),
						'icon' => 'fa fa-map-marker',
					],
				],
			]
		);

	   	$this->add_control( 'map_addr',
			[
				'label' => esc_html__( 'Address', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Dhaka, Bangladesh', 'elementsKit-lite' ),
				'condition' => [
					'map_address_type' => ['address']
				]
			]
		);

		$this->add_control( 'map_lat',
			[
				'label' => esc_html__( 'Latitude', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '23.749981', 'elementsKit-lite' ),
				'condition' => [
					'map_address_type' => ['coordinates']
				]
			]
		);

		$this->add_control( 'map_lng',
			[
				'label' => esc_html__( 'Longitude', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '90.365641', 'elementsKit-lite' ),
				'condition' => [
					'map_address_type' => ['coordinates']
				]
			]
		);

		// Start map type static
		// $this->add_control( 'map_static_lat',
		// 	[
		// 		'label' => esc_html__( 'Latitude', 'elementsKit-lite' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'label_block' => false,
		// 		'default' => esc_html__( '23.7808875', 'elementsKit-lite' ),
		// 		'condition' => [
		// 			'map_type' => ['static'],
		// 		]
		// 	]
		// );

		// $this->add_control( 'map_static_lng',
		// 	[
		// 		'label' => esc_html__( 'Longitude', 'elementsKit-lite' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'label_block' => false,
		// 		'default' => esc_html__( '90.2792373', 'elementsKit-lite' ),
		// 		'condition' => [
		// 			'map_type' => ['static'],
		// 		]
		// 	]
		// );

		$this->add_control( 'map_resolution_title',
			[
				'label' => __( 'Map Image Resolution', 'elementsKit-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'map_type' => 'static'
				]
			]
		);

		$this->add_control( 'map_static_width',
			[
				'label' => esc_html__( 'Static Image Width', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 610
				],
				'range' => [
					'px' => [
						'max' => 1400,
					],
				],
				'condition' => [
					'map_type' => 'static'
				]
			]
		);

		$this->add_control( 'map_static_height',
			[
				'label' => esc_html__( 'Static Image Height', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [ 'size' => 300 ],
				'range' => [ 'px' => [ 'max' => 700 ] ],
				'condition' => [
					'map_type' => 'static'
				]
			]
		);
		// End map type static

		// Start map type panoroma
		$this->add_control( 'map_panorama_lat',
			[
				'label' => esc_html__( 'Latitude', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '23.7808875', 'elementsKit-lite' ),
				'condition' => [
					'map_type' => ['panorama'],
				]
			]
		);

		$this->add_control( 'map_panorama_lng',
			[
				'label' => esc_html__( 'Longitude', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '90.2792373', 'elementsKit-lite' ),
				'condition' => [
					'map_type' => ['panorama'],
				]
			]
		);
		// End map type panoroma

		$this->add_control( 'map_overlay_content',
			[
				'label' => __( 'Content', 'elementsKit-lite' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Your content will goes here', 'elementsKit-lite' ),
				'condition' => [
					'map_type' => 'overlay'
				]
			]
		);

		$this->end_controls_section();

		
		$this->start_controls_section(
			'ekit_section_google_map_basic_marker_settings',
			[
				'label' => esc_html__( 'Marker Settings', 'elementsKit-lite' ),
				'condition' => [
					'map_type' => ['basic']
				]
			]
		);
		$this->add_control(
			'map_basic_marker_title',
			[
				'label' => esc_html__( 'Title', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Google Map Title', 'elementsKit-lite' )
			]
		);
		$this->add_control(
			'map_basic_marker_content',
			[
				'label' => esc_html__( 'Content', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Google map content', 'elementsKit-lite' )
			]
		);
		$this->add_control(
			'map_basic_marker_icon_enable',
			[
				'label' => __( 'Custom Marker Icon', 'elementsKit-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'elementsKit-lite' ),
				'label_off' => __( 'No', 'elementsKit-lite' ),
				'return_value' => 'yes',
			]
		);
			$this->add_control(
			'map_basic_marker_icon',
			[
				'label' => esc_html__( 'Marker Icon', 'elementsKit-lite' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					// 'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'map_basic_marker_icon_enable' => 'yes'
				]
			]
		);
		$this->add_control(
			'map_basic_marker_icon_width',
			[
				'label' => esc_html__( 'Marker Width', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 32
				],
				'range' => [
					'px' => [
						'max' => 150,
					],
				],
				'condition' => [
					'map_basic_marker_icon_enable' => 'yes'
				]
			]
		);
		$this->add_control(
			'map_basic_marker_icon_height',
			[
				'label' => esc_html__( 'Marker Height', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 32
				],
				'range' => [
					'px' => [
						'max' => 150,
					],
				],
				'condition' => [
					'map_basic_marker_icon_enable' => 'yes'
				]
			]
		);
		$this->end_controls_section();
			

		$this->start_controls_section(
				'ekit_section_google_map_marker_settings',
				[
					'label' => esc_html__( 'Marker Settings', 'elementsKit-lite' ),
					'condition' => [
						'map_type' => ['marker', 'polyline', 'routes', 'static']
					]
				]
		);

		$this->add_control(
			'map_markers',
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' => [
					[ 
						'map_marker_title' => esc_html__( 'Daffodil International University', 'elementsKit-lite' ),
						'map_marker_lat' => esc_html__( '23.754539', 'elementsKit-lite' ),
						'map_marker_lng' => esc_html__( '90.3769106', 'elementsKit-lite' ),
					],
					[ 
						'map_marker_title' => esc_html__( 'National Parliament House', 'elementsKit-lite' ),
						'map_marker_lat' => esc_html__( '23.7626233', 'elementsKit-lite' ),
						'map_marker_lng' => esc_html__( '90.3777502', 'elementsKit-lite' ),
					],
				],
				'fields' => [
					[
						'name' => 'map_marker_lat',
						'label' => esc_html__( 'Latitude', 'elementsKit-lite' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( '28.948790', 'elementsKit-lite' ),
					],
					[
						'name' => 'map_marker_lng',
						'label' => esc_html__( 'Longitude', 'elementsKit-lite' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( '-81.298843', 'elementsKit-lite' ),
					],
					[
						'name' => 'map_marker_title',
						'label' => esc_html__( 'Tooltrip', 'elementsKit-lite' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Marker Title', 'elementsKit-lite' ),
					],
					[
						'name' => 'map_marker_content',
						'label' => esc_html__( 'Content', 'elementsKit-lite' ),
						'type' => Controls_Manager::TEXTAREA,
						'label_block' => true,
						'default' => esc_html__( 'Marker Content. You can put html here.', 'elementsKit-lite' ),
					],
					[
						'name' => 'map_marker_icon_enable',
						'label' => __( 'Use Custom Icon', 'elementsKit-lite' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => 'no',
						'label_on' => __( 'Yes', 'elementsKit-lite' ),
						'label_off' => __( 'No', 'elementsKit-lite' ),
						'return_value' => 'yes',
					],
					[
						'name' => 'map_marker_icon',
						'label' => esc_html__( 'Custom Icon', 'elementsKit-lite' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [],
						'condition' => [
							'map_marker_icon_enable' => 'yes'
						]
					],
					[
						'name' => 'map_marker_icon_width',
						'label' => esc_html__( 'Icon Width', 'elementsKit-lite' ),
						'type' => Controls_Manager::NUMBER,
						'default' => esc_html__( '32', 'elementsKit-lite' ),
						'condition' => [
							'map_marker_icon_enable' => 'yes'
						]
					],
					[
						'name' => 'map_marker_icon_height',
						'label' => esc_html__( 'Icon Height', 'elementsKit-lite' ),
						'type' => Controls_Manager::NUMBER,
						'default' => esc_html__( '32', 'elementsKit-lite' ),
						'condition' => [
							'map_marker_icon_enable' => 'yes'
						]
					]
				],
				'title_field' => '{{map_marker_title}}',
			]
		);
		$this->end_controls_section();


		
		$this->start_controls_section(
			'ekit_section_google_map_polyline_settings',
			[
				'label' => esc_html__( 'Coordinate Settings', 'elementsKit-lite' ),
				'condition' => [
					'map_type' => ['polyline', 'polygon']
				]
			]
		);

		$this->add_control(
			'map_polylines',
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' => [
					[ 
                        'map_polyline_title' => esc_html__( '#1', 'elementsKit-lite' ),
                        'map_polyline_lat' => esc_html__( '23.749981', 'elementsKit-lite' ),
                        'map_polyline_lng' => esc_html__( '90.365641', 'elementsKit-lite' ),
                    ],
					[ 
                        'map_polyline_title' => esc_html__( '#2', 'elementsKit-lite' ),
                        'map_polyline_lat' => esc_html__( '23.7416692', 'elementsKit-lite' ),
                        'map_polyline_lng' => esc_html__( '90.3622266', 'elementsKit-lite' ),
                    ],
					[ 
                        'map_polyline_title' => esc_html__( '#3', 'elementsKit-lite' ),
                        'map_polyline_lat' => esc_html__( '23.7514466', 'elementsKit-lite' ),
                        'map_polyline_lng' => esc_html__( '90.3967484', 'elementsKit-lite' ),
                    ],
				],
				'fields' => [
					[
						'name' => 'map_polyline_title',
						'label' => esc_html__( 'Title', 'elementsKit-lite' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( '#', 'elementsKit-lite' ),
					],
					[
						'name' => 'map_polyline_lat',
						'label' => esc_html__( 'Latitude', 'elementsKit-lite' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( '28.948790', 'elementsKit-lite' ),
					],
					[
						'name' => 'map_polyline_lng',
						'label' => esc_html__( 'Longitude', 'elementsKit-lite' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( '-81.298843', 'elementsKit-lite' ),
					],
				],
				'title_field' => '{{map_polyline_title}}',
			]
		);
		$this->end_controls_section();

		
		$this->start_controls_section(
				'ekit_section_google_map_routes_settings',
				[
					'label' => esc_html__( 'Routes Coordinate Settings', 'elementsKit-lite' ),
					'condition' => [
						'map_type' => ['routes']
					]
				]
			);
		$this->add_control(
			'map_routes_origin',
			[
				'label' => esc_html__( 'Origin', 'elementsKit-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'map_routes_origin_lat',
			[
				'label' => esc_html__( 'Latitude', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '28.948790', 'elementsKit-lite' ),
			]
		);
		$this->add_control(
			'map_routes_origin_lng',
			[
				'label' => esc_html__( 'Longitude', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '-81.298843', 'elementsKit-lite' ),
			]
		);
		$this->add_control(
			'map_routes_dest',
			[
				'label' => esc_html__( 'Destination', 'elementsKit-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'map_routes_dest_lat',
			[
				'label' => esc_html__( 'Latitude', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '1.2833808', 'elementsKit-lite' ),
			]
		);

		$this->add_control(
			'map_routes_dest_lng',
			[
				'label' => esc_html__( 'Longitude', 'elementsKit-lite' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '103.8585377', 'elementsKit-lite' ),
			]
		);

		$this->add_control(
				'map_routes_travel_mode',
				[
					'label'       	=> esc_html__( 'Travel Mode', 'elementsKit-lite' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'walking',
				'label_block' 	=> false,
				'options' 		=> [
					'walking'  	=> esc_html__( 'Walking', 'elementsKit-lite' ),
					'bicycling' => esc_html__( 'Bicycling', 'elementsKit-lite' ),
					'driving' 	=> esc_html__( 'Driving', 'elementsKit-lite' ),
				]
				]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_map_controls',
			[
				'label'	=> esc_html__( 'Controls', 'elementsKit-lite' )
			]
		);
		$this->add_control(
			'map_zoom',
			[
				'label' => esc_html__( 'Zoom Level', 'elementsKit-lite' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => esc_html__( '14', 'elementsKit-lite' ),
			]
		);
		$this->add_control(
			'ekit_map_streeview_control',
			[
				'label'                 => esc_html__( 'Street View Controls', 'elementsKit-lite' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'true',
				'label_on'              => __( 'On', 'elementsKit-lite' ),
				'label_off'             => __( 'Off', 'elementsKit-lite' ),
				'return_value'          => 'true',
			]
		);
		$this->add_control(
			'ekit_map_type_control',
			[
				'label'                 => esc_html__( 'Map Type Control', 'elementsKit-lite' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'On', 'elementsKit-lite' ),
				'label_off'             => __( 'Off', 'elementsKit-lite' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'ekit_map_zoom_control',
			[
				'label'                 => esc_html__( 'Zoom Control', 'elementsKit-lite' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'On', 'elementsKit-lite' ),
				'label_off'             => __( 'Off', 'elementsKit-lite' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'ekit_map_fullscreen_control',
			[
				'label'                 => esc_html__( 'Fullscreen Control', 'elementsKit-lite' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'On', 'elementsKit-lite' ),
				'label_off'             => __( 'Off', 'elementsKit-lite' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'ekit_map_scroll_zoom',
			[
				'label'                 => esc_html__( 'Scroll Wheel Zoom', 'elementsKit-lite' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'On', 'elementsKit-lite' ),
				'label_off'             => __( 'Off', 'elementsKit-lite' ),
				'return_value'          => 'yes',
			]
		);
		$this->end_controls_section();
			
		
		$this->start_controls_section(
			'ekit_section_google_map_theme_settings',
			[
				'label'		=> esc_html__( 'Theme', 'elementsKit-lite' ),
				'condition' => [
					'map_type!'	=> ['static', 'panorama']
				]
			]
		);
		$this->add_control(
			'map_theme_source',
			[
				'label'		=> __( 'Theme Source', 'elementsKit-lite' ),
				'type'		=> Controls_Manager::CHOOSE,
				'options' => [
					'gstandard' => [
						'title' => __( 'Google Standard', 'elementsKit-lite' ),
						'icon' => 'fa fa-map',
					],
					'snazzymaps' => [
						'title' => __( 'Snazzy Maps', 'elementsKit-lite' ),
						'icon' => 'fa fa-map-marker',
					],
					'custom' => [
						'title' => __( 'Custom', 'elementsKit-lite' ),
						'icon' => 'fa fa-edit',
					],
				],
				'default'	=> 'gstandard'
			]
		);
		$this->add_control(
			'map_gstandards',
			[
				'label'                 => esc_html__( 'Google Themes', 'elementsKit-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'standard',
				'options'               => [
					'standard'     => __( 'Standard', 'elementsKit-lite' ),
					'silver'       => __( 'Silver', 'elementsKit-lite' ),
					'retro'        => __( 'Retro', 'elementsKit-lite' ),
					'dark'         => __( 'Dark', 'elementsKit-lite' ),
					'night'        => __( 'Night', 'elementsKit-lite' ),
					'aubergine'    => __( 'Aubergine', 'elementsKit-lite' )
				],
				'description'           => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s',__( 'Click here', 'elementsKit-lite' ), __( 'to generate your own theme and use JSON within Custom style field.', 'elementsKit-lite' ) ),
				'condition'	=> [
					'map_theme_source'	=> 'gstandard'
				]
			]
		);
		$this->add_control(
			'map_snazzymaps',
			[
				'label'                 => esc_html__( 'SnazzyMaps Themes', 'elementsKit-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'label_block'			=> true,
				'default'               => 'colorful',
				'options'               => [
					'default'		=> __( 'Default', 'elementsKit-lite' ),
					'simple'		=> __( 'Simple', 'elementsKit-lite' ),
					'colorful'		=> __( 'Colorful', 'elementsKit-lite' ),
					'complex'		=> __( 'Complex', 'elementsKit-lite' ),
					'dark'			=> __( 'Dark', 'elementsKit-lite' ),
					'greyscale'		=> __( 'Greyscale', 'elementsKit-lite' ),
					'light'			=> __( 'Light', 'elementsKit-lite' ),
					'monochrome'	=> __( 'Monochrome', 'elementsKit-lite' ),
					'nolabels'		=> __( 'No Labels', 'elementsKit-lite' ),
					'twotone'		=> __( 'Two Tone', 'elementsKit-lite' )
				],
				'description'           => sprintf( '<a href="https://snazzymaps.com/explore" target="_blank">%1$s</a> %2$s',__( 'Click here', 'elementsKit-lite' ), __( 'to explore more themes and use JSON within custom style field.', 'elementsKit-lite' ) ),
				'condition'	=> [
					'map_theme_source'	=> 'snazzymaps'
				]
			]
		);
		$this->add_control(
			'map_custom_style',
			[
				'label'                 => __( 'Custom Style', 'elementsKit-lite' ),
				'description'           => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s',__( 'Click here', 'elementsKit-lite' ), __( 'to get JSON style code to style your map', 'elementsKit-lite' ) ),
				'type'                  => Controls_Manager::TEXTAREA,
				'condition'             => [
					'map_theme_source'     => 'custom',
				],
			]
		);
		$this->end_controls_section(); 
		
		
		$this->start_controls_section(
			'map_style_settings',
			[
				'label' => esc_html__( 'Map Container', 'elementsKit-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control( 'map_max_width',
			[
				'label' => __( 'Width', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1140,
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1400,
						'step' => 10,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-google-map' => 'max-width: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control( 'map_max_height',
			[
				'label' => __( 'Height', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 400,
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1400,
						'step' => 10,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-google-map' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control( 'map_alignment',
			[
				'label' => __( 'Alignment', 'elementsKit-lite' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementsKit-lite' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementsKit-lite' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementsKit-lite' ),
						'icon' => 'fa fa-align-right',
					],
				]
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'map_overlay_style_settings',
			[
				'label' => esc_html__( 'Overlay Style', 'elementsKit-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'map_type' => ['overlay']
				]
			]
		);
		$this->add_responsive_control(
			'map_overlay_width',
			[
				'label' => __( 'Width', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 200,
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1100,
						'step' => 10,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-gmap-overlay' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_control(
			'map_overlay_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementsKit-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .ekit-gmap-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'mapoverlay_padding',
			[
				'label' => esc_html__( 'Padding', 'elementsKit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
						'{{WRAPPER}} .ekit-gmap-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'map_overlay_margin',
			[
				'label' => esc_html__( 'Margin', 'elementsKit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
						'{{WRAPPER}} .ekit-gmap-overlay' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'map_overlay_border',
				'label' => esc_html__( 'Border', 'elementsKit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-gmap-overlay',
			]
		);
		$this->add_responsive_control(
			'map_overlay_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementsKit-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
						'{{WRAPPER}} .ekit-gmap-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'map_overlay_box_shadow',
				'selector' => '{{WRAPPER}} .ekit-gmap-overlay',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'map_overlay_typography',
				'selector' => '{{WRAPPER}} .ekit-gmap-overlay',
			]
		);
		$this->add_control(
			'map_overlay_color',
			[
				'label' => esc_html__( 'Color', 'elementsKit-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .ekit-gmap-overlay' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		
		$this->start_controls_section(
			'ekit_section_google_map_stroke_style_settings',
			[
				'label' => esc_html__( 'Stroke Style', 'elementsKit-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'map_type' => ['polyline', 'polygon', 'routes']
				]
			]
		);
		$this->add_control(
			'map_stroke_color',
			[
				'label' => esc_html__( 'Color', 'elementsKit-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e23a47',
			]
		);
		$this->add_responsive_control(
			'map_stroke_opacity',
			[
				'label' => __( 'Opacity', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.8,
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.2,
						'max' => 1,
						'step' => 0.1,
					]
				],
			]
		);
		$this->add_responsive_control(
			'map_stroke_weight',
			[
				'label' => __( 'Weight', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 4,
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
						'step' => 1,
					]
				],
			]
		);
		$this->add_control(
			'map_stroke_fill_color',
			[
				'label' => esc_html__( 'Fill Color', 'elementsKit-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e23a47',
				'condition' => [
					'map_type' => ['polygon']
				]
			]
		);
		$this->add_responsive_control(
			'map_stroke_fill_opacity',
			[
				'label' => __( 'Fill Opacity', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.4,
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.2,
						'max' => 1,
						'step' => 0.1,
					]
				],
				'condition' => [
					'map_type' => ['polygon']
				]
			]
		);
		$this->end_controls_section();


	}

	protected function ekit_get_map_theme($settings) {

		if($settings['map_theme_source'] == 'custom') {
			return strip_tags($settings['map_custom_style']);
		}else {
			$themes = include('google-map-themes.php');
			if(isset($themes[$settings['map_theme_source']][$settings['map_gstandards']])) {
				return $themes[$settings['map_theme_source']][$settings['map_gstandards']];
			}elseif(isset($themes[$settings['map_theme_source']][$settings['map_snazzymaps']])) {
				return $themes[$settings['map_theme_source']][$settings['map_snazzymaps']];
			}else {
				return '';
			}
		}

	}

	protected function map_render_data_attributes( $settings ) {

		extract($settings);

		return [
			'data-map_type'						=> isset($map_type)							? esc_attr($map_type) 								:	'',
			'data-map_address_type'				=> isset($map_address_type)					? esc_attr($map_address_type)						:	'',
			'data-map_lat'						=> isset($map_lat)							? esc_attr($map_lat)								:	'',
			'data-map_lng'						=> isset($map_lng)							? esc_attr($map_lng)								:	'',
			'data-map_addr'						=> isset($map_addr)							? esc_attr($map_addr)								:	'',
			'data-map_basic_marker_title'		=> isset($map_basic_marker_title)			? esc_attr($map_basic_marker_title)					:	'',
			'data-map_basic_marker_content'		=> isset($map_basic_marker_content)			? esc_attr($map_basic_marker_content)				:	'',
			'data-map_basic_marker_icon_enable'	=> isset($map_basic_marker_icon_enable)		? esc_attr($map_basic_marker_icon_enable)			:	'',
			'data-map_basic_marker_icon'		=> isset($map_basic_marker_icon) 			? esc_attr($map_basic_marker_icon['url']) 			:	'',
			'data-map_basic_marker_icon_width'	=> isset($map_basic_marker_icon_width) 		? esc_attr($map_basic_marker_icon_width['size'])	:	'',
			'data-map_basic_marker_icon_height'	=> isset($map_basic_marker_icon_height)		? esc_attr($map_basic_marker_icon_height['size'])	:	'',
			'data-map_zoom'						=> isset($map_zoom)							? esc_attr($map_zoom)								:	'',
			'data-map_marker_content'			=> isset($map_marker_content)				? esc_attr($map_marker_content) 					:	'',
			'data-map_static_width'				=> isset($map_static_width)					? esc_attr($map_static_width['size'])				:	'',
			'data-map_static_height'			=> isset($map_static_height)				? esc_attr($map_static_height['size'])				:	'',
			//'data-map_static_lat'				=> isset($map_static_lat)					? esc_attr($map_static_lat)							:	'',
			//'data-map_static_lng'				=> isset($map_static_lng)					? esc_attr($map_static_lng)							:	'',
			'data-map_stroke_color'				=> isset($map_stroke_color)					? esc_attr($map_stroke_color)						:	'',
			'data-map_stroke_opacity'			=> isset($map_stroke_opacity)				? esc_attr($map_stroke_opacity['size'])				:	'',
			'data-map_stroke_weight'			=> isset($map_stroke_weight)				? esc_attr($map_stroke_weight['size'])				:	'',
			'data-map_stroke_fill_color'		=> isset($map_stroke_fill_color)			? esc_attr($map_stroke_fill_color)					:	'',
			'data-map_stroke_fill_opacity'		=> isset($map_stroke_fill_opacity)			? esc_attr($map_stroke_fill_opacity['size'])		:	'',
			'data-map_overlay_content'			=> isset($map_overlay_content)				? esc_attr($map_overlay_content)					:	'',
			'data-map_routes_origin_lat'		=> isset($map_routes_origin_lat)			? esc_attr($map_routes_origin_lat)					:	'',
			'data-map_routes_origin_lng'		=> isset($map_routes_origin_lng)			? esc_attr($map_routes_origin_lng)					:	'',
			'data-map_routes_dest_lat'			=> isset($map_routes_dest_lat)				? esc_attr($map_routes_dest_lat)					:	'',
			'data-map_routes_dest_lng'			=> isset($map_routes_dest_lng)				? esc_attr($map_routes_dest_lng)					:	'',
			'data-map_routes_travel_mode'		=> isset($map_routes_travel_mode)			? esc_attr($map_routes_travel_mode)					:	'',
			'data-map_panorama_lat'				=> isset($map_panorama_lat)					? esc_attr($map_panorama_lat)						:	'',
			'data-map_panorama_lng'				=> isset($map_panorama_lng)					? esc_attr($map_panorama_lng)						:	'',

			'data-map_theme'					=> urlencode(json_encode($this->ekit_get_map_theme($settings))),
			'data-map_markers'					=> urlencode(json_encode($map_markers)),
			'data-map_polylines'				=> urlencode(json_encode($map_polylines)),

			'data-map_streeview_control'		=> isset($ekit_map_streeview_control) 	&& $ekit_map_streeview_control 			? 'true': 'false',
			'data-map_type_control'				=> isset($ekit_map_type_control) 		&& $ekit_map_type_control 				? 'true': 'false',
			'data-map_zoom_control'				=> isset($ekit_map_zoom_control) 		&& $ekit_map_zoom_control 				? 'true': 'false',
			'data-map_fullscreen_control'		=> isset($ekit_map_fullscreen_control) 	&& $ekit_map_fullscreen_control 		? 'true': 'false',
			'data-map_scroll_zoom'				=> isset($ekit_map_scroll_zoom) 		&& $ekit_map_scroll_zoom 				? 'true': 'false'
		];
	}

	protected function get_map_render_data_attribute_string($settings) {

		$data_attributes = $this->map_render_data_attributes($settings);
		$data_string = '';

		foreach( $data_attributes as $key => $value ) {
			if( isset($key) && ! empty($value)) {
				$data_string .= ' '.$key.'="'.$value.'"';
			}
		}
		return $data_string;
	}

	protected function get_alignment( $align ){
		if( $align == 'left' ) { return ''; }
		return $align == 'center' 
			? esc_attr('margin-left:auto;margin-right:auto;' )
			: esc_attr('margin-left:auto;margin-right:0;');
	}
    
    protected function render() {
		echo '<div class="ekit-wid-con" >';
		$this->render_raw();
		echo '</div>';
	}

	protected function render_raw() {

		$settings = $this->get_settings_for_display();
        $user_data = Attr::instance()->utils->get_option('user_data', []);

		$hasApiKey = !empty($user_data['google_map']) && '' != $user_data['google_map']['api_key'];
		
		//var_dump($map_alignment);

		$this->add_render_attribute( 'map_wrap', [
			'class'					=> ['ekit-google-map'],
			'id'					=> 'ekit-google-map-'.esc_attr($this->get_id()),
			'data-id'				=> esc_attr($this->get_id()),
			'data-api_key'			=> $hasApiKey ? esc_attr($user_data['google_map']['api_key']) : '',
			'style'					=> $this->get_alignment($settings['map_alignment'])
		]);
	?>

	<?php if( ! empty($settings['map_type']) ) : ?>
		<div <?php echo $this->get_render_attribute_string('map_wrap'), $this->get_map_render_data_attribute_string($settings); ?>></div>
	<?php endif; ?>
		<div class="google-map-notice"></div>
	<?php

	}

}