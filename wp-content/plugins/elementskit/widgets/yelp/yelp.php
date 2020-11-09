<?php

namespace Elementor;

use \Elementor\ElementsKit_Widget_Yelp_Handler as Handler;

if (!defined('ABSPATH')) exit;

class ElementsKit_Widget_Yelp extends Widget_Base
{

    public function get_name()
    {
        return Handler::get_name();
    }

    public function get_title()
    {
        return Handler::get_title();
    }

    public function get_icon()
    {
        return Handler::get_icon();
    }

    public function get_categories()
    {
        return Handler::get_categories();
    }

    private function get_stars_rating($rating) {
		$htm = '';
		for($i = 1; $i <= 5; $i++) {
			$star_icon = $i <= $rating ? 'icon-star-1' : 'icon-star1';
			$htm .= '<span class="mr-1"><i class="icon ' . $star_icon . '"></i></span>';
		}
		return $htm;
    }

    private function get_user_thumbnail($thumbnail) {
		if(!empty($thumbnail)) return $thumbnail;
        return Handler::get_url() . 'assets/images/profile-placeholder.jpg';
	}

	private function get_formatted_text($txt, $additional_flag, $max_len = 120) {
		$len = strlen($txt);
		if($additional_flag === true && $len > $max_len) {
            return
                '<span>'.substr($txt, 0, $max_len).'</span>'.
                '<span
                    class="more"
                    data-collapsed="true"
                    data-text="'.$txt.'"
                > ...More
                </span>'
            ;
		}
		return $txt;
	}

	/**
	 * Convert number or array of number to dimension format
	 *
	 * @param number|array	$value		16 | [0, 0 , 16, 0 ]
	 * @param string		$unit		px | em | rem | % | vh | vw
	 * @param boolean		$linked		true | false
	 * @return array 		
	 *	[ 
	 *		'top' 		=> '16', 		'right' 	=> '16', 
	 *		'bottom' 	=> '16', 		'left' 		=> '16', 
	 *		'unit' 		=> 'px', 		'isLinked' 	=> true 
	 *	];
	 */
	 private function get_dimension( $value = 1, $unit = 'em', $linked = true ){
        $is_arr = is_array( $value );
        return [
			'top'      => strval($is_arr ? $value[0] : $value), 
			'right'    => strval($is_arr ? $value[1] : $value),
			'bottom'   => strval($is_arr ? $value[2] : $value), 
			'left'     => strval($is_arr ? $value[3] : $value),
            'unit'     => $unit, 'isLinked' =>  $linked,
        ];
    }

	private function controls_section( $config, $callback ){

		// New configs
		$newConfig = [ 'label' => $config['label'] ];
		
		// Formatting configs
		if(isset($config['tab'])) $newConfig['tab'] = $config['tab'];
		if(isset($config['condition'])) $newConfig['condition'] = $config['condition'];

		// Start section
		$this->start_controls_section( $config['key'],  $config);

		// Call the callback function
		call_user_func(array($this, $callback));

		// End section
		$this->end_controls_section();
	}

	private function control_border($key, $selectors, $config = [ 'default' => '8', 'unit' => 'px' ]){
		
		$selectors = array_map( function($selector) { return "{{WRAPPER}} " . $selector ;}, $selectors );

		// Border heading
		$this->add_control( $key, [
			'label'     => esc_html__('Border', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		]);

		// Review card border
		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'     => $key . '_type',
				'label'    => esc_html__('Border Type', 'elementsKit-lite'),
				'selector' => implode(',', $selectors)
			]
		);

		$new_selectors = array();
		$border_radius = 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
		foreach ($selectors as $key) { $new_selectors[$key] = $border_radius; }

		// Review card border radius
		$this->add_control( $key . '_radius', [
			'label'			=> esc_html__('Border Radius', 'elementsKit-lite'),
			'type'			=> Controls_Manager::DIMENSIONS,
			'size_units'	=> ['px', '%', 'em'],
			'selectors'		=> $new_selectors,
			'default'    => [
				'top'      => $config['default'], 'right'	=> $config['default'],
				'bottom'   => $config['default'], 'left'	=> $config['default'],
				'unit'     => $config['unit'], 'isLinked' => true,
			]
		]);
	}

	private function control_text( $key, $selector, $exclude = [], $config = [] ){

		// Page name color
		$this->add_control( $key . '_color', [
			'label'     => __('Text Color', 'elementsKit-lite'),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} '. $selector => 'color: {{VALUE}}',
			],
		]);

		if(!in_array("shadow", $exclude)){
			// Page name text shadow
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(), [
					'name' => $key . '_text_shadow',
					'label' => __( 'Text Shadow', 'elementsKit-lite' ),
					'selector' => '{{WRAPPER}} ' . $selector
				]
			);
		}

		if(!in_array("typography", $exclude)){
			// Page name typography
			$this->add_group_control(
				Group_Control_Typography::get_type(), [
					'name'     => $key . '_typography',
					'label'    => __('Typography', 'elementsKit-lite'),
					'selector' => '{{WRAPPER}} ' . $selector
				]
			);
		}

		if(!in_array("margin", $exclude)){ 
			// controls_section_overview_page_name_margin
			$value = '{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';

			$def_margin = isset($config['def_margin']) 
				? $config['def_margin'] : [ 'bottom' => '16', 'unit' => 'px', 'isLinked' => false ];

			$this->add_responsive_control( $key . '_margin', [
				'label'          => esc_html__('Margin', 'elementsKit-lite'),
				'type'           => Controls_Manager::DIMENSIONS,
				'size_units'     => ['px', '%', 'em'],
				'default'        => $def_margin,
				'tablet_default' => $def_margin,
				'mobile_default' => $def_margin,
				'selectors'      => [ '{{WRAPPER}} ' . $selector => 'margin:' . $value ],
			]);
		}
	}

	private function controls_section_layout(){

		// ekit_review_styles
		$this->add_control(
			'ekit_review_styles',
			[
				'label'   => esc_html__('Layout Styles', 'elementsKit-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'reviews',
				'options' => [
					'reviews'   => esc_html__('With Reviews', 'elementsKit-lite'),
					'slideshow' => esc_html__('Slideshow', 'elementsKit-lite'),
				],
			]
		);

        // ekit_yelp_review_only_positive
		$this->add_control(
			'ekit_yelp_review_only_positive',
			[
				'label'     => esc_html__('Review Type', 'elementsKit-lite'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'both',
				'options'   => [
					'both'     => esc_html__('Both', 'elementsKit-lite'),
					'positive' => esc_html__('Only Positive', 'elementsKit-lite'),
				],
				'condition' => [
					'ekit_review_styles!' => 'default',
				],
			]
		);

        // ekit_review_card_type
		$this->add_control(
			'ekit_review_card_type',
			[
				'label'     => esc_html__('Card Type', 'elementsKit-lite'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default' => esc_html__('Box Card', 'elementsKit-lite'),
					'bubble'  => esc_html__('Bubble Card', 'elementsKit-lite'),
				],
				'condition' => [
					'ekit_review_styles!' => 'default',
				],
			]
        );

        // ekit_review_card_appearance
		$this->add_control(
			'ekit_review_card_appearance',
			[
				'label'     => esc_html__('Card Appearance', 'elementsKit-lite'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'masonry',
				'options'   => [
					'grid'    => esc_html__('Grid', 'elementsKit-lite'),
					'masonry' => esc_html__('Masonry', 'elementsKit-lite'),
					'list'    => esc_html__('List', 'elementsKit-lite'),
				],
				'condition' => [
					'ekit_review_styles' => 'reviews',
				],
			]
		);

        // ekit_review_column
		$this->add_responsive_control(
			'ekit_review_column', [
				'label'     => esc_html__('Column Count', 'elementsKit-lite'),
				'type'      => Controls_Manager::SELECT,
				'default' => 'col-3',
                'tablet_default' => 'col-6',
                'mobile_default' => 'col-12',
				'options'   => [
					'col-12' => esc_html__('1', 'elementsKit-lite'),
                    'col-6' => esc_html__('2', 'elementsKit-lite'),
                    'col-4' => esc_html__('3', 'elementsKit-lite'),
                    'col-3' => esc_html__('4', 'elementsKit-lite'),
                    'col-2' => esc_html__('6', 'elementsKit-lite'),
				],
				'condition' => [
					'ekit_review_card_appearance' => 'grid',
					'ekit_review_styles'          => 'reviews',
				],
			]
        );
        
        // Grid column gap
		$this->add_responsive_control( 'ekit_review_grid_column_gap', [
				'label'           => esc_html__('Column Gap', 'elementsKit-lite'),
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => ['px','em'],
				'range'           => [
					'px' => [ 'min'  => 0, 'max'  => 96, 'step' => 2 ],
					'em' => [ 'min'  => 0, 'max'  => 6, 'step' => 0.2 ]
				],
				'devices'         => ['desktop', 'tablet', 'mobile'],
				'tablet_default'  => [ 'size' => 8, 'unit' => 'px' ],
				'mobile_default'  => [ 'size' => 1, 'unit' => 'em' ],
				'default'         => [ 'size' => 24, 'unit' => 'px' ],
				'selectors'       => [
                    '{{WRAPPER}} .ekit-review-cards-facebok.ekit-review-cards-grid .row > div' =>  'padding-left: {{SIZE}}{{UNIT}};
                        padding-right:calc({{SIZE}}{{UNIT}} / 2);margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'ekit_review_styles'          => 'reviews',
					'ekit_review_card_appearance' => 'grid',
				],
			]
        );

        // ekit_review_masonry_column_count
		$this->add_control(
			'ekit_review_masonry_column_count', [
				'label'     => esc_html__('Column Count', 'elementsKit-lite'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 3,
				'options'   => [
					1 => esc_html__('1', 'elementsKit-lite'),
					2 => esc_html__('2', 'elementsKit-lite'),
					3 => esc_html__('3', 'elementsKit-lite'),
					4 => esc_html__('4', 'elementsKit-lite'),
					6 => esc_html__('6', 'elementsKit-lite'),
				],
				'condition' => [
					'ekit_review_styles'          => 'reviews',
					'ekit_review_card_appearance' => 'masonry',
				],
			]
		);

		// Masonry Column gap
		$this->add_responsive_control(
			'ekit_review_masonry_column_gap', [
				'label'           => esc_html__('Column Gap', 'elementsKit-lite'),
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => ['px','em'],
				'range'           => [
					'px' => [ 'min'  => 0, 'max'  => 96, 'step' => 2 ],
					'em' => [ 'min'  => 0, 'max'  => 6, 'step' => 0.2 ]
				],
				'devices'         => ['desktop', 'tablet', 'mobile'],
				'tablet_default'  => [ 'size' => 8, 'unit' => 'px' ],
				'mobile_default'  => [ 'size' => 1, 'unit' => 'em' ],
				'default'         => [ 'size' => 24, 'unit' => 'px' ],
				'selectors'       => [
					'{{WRAPPER}} .ekit-review-cards-facebok.ekit-review-cards-masonry .masonry' => 'column-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-review-cards-facebok.ekit-review-cards-masonry .masonry' .' > div' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'ekit_review_styles'          => 'reviews',
					'ekit_review_card_appearance' => 'masonry',
				],
			]
        );
	}

	private function controls_section_contents(){

		// ekit_review_card_thumbnail_badge
		$this->add_control(
			'ekit_review_card_thumbnail_badge',
			[
				'label'        => esc_html__('Thumbnail Badge', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'ekit_review_styles!' => 'default',
				],
			]
		);

		// ekit_review_card_align_center
		$this->add_control(
			'ekit_review_card_align_center',
			[
				'label'        => esc_html__('Align Content Center', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'ekit_review_card_type' => 'default',
					'ekit_review_styles!'   => 'default',
				],
			]
		);

		// ekit_review_card_thumbnail_left
		$this->add_control(
			'ekit_review_card_thumbnail_left',
			[
				'label'        => esc_html__('Thumbnail Left', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'ekit_review_styles!'              => 'default',
					'ekit_review_card_type'            => 'default',
					'ekit_review_card_align_center!'   => 'yes',
					'ekit_review_card_name_at_bottom!' => 'yes',
				],
			]
		);

		// ekit_review_card_stars_inline
		$this->add_control(
			'ekit_review_card_stars_inline',
			[
				'label'        => esc_html__('Stars Inline', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'ekit_review_styles!'              => 'default',
					'ekit_review_card_type'            => 'default',
					'ekit_review_card_align_center!'   => 'yes',
					'ekit_review_card_name_at_bottom!' => 'yes',
				],
			]
		);

		// ekit_review_card_posted_on
		$this->add_control(
			'ekit_review_card_posted_on',
			[
				'label'        => esc_html__('Bottom Posted On', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'ekit_review_card_type' => 'default',
					'ekit_review_styles!'   => 'default',
				],
			]
		);

		// ekit_review_card_name_at_bottom
		$this->add_control(
			'ekit_review_card_name_at_bottom',
			[
				'label'        => esc_html__('Name at Bottom', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'ekit_review_card_type' => 'default',
					'ekit_review_styles!'   => 'default',
				],
			]
		);

		// ekit_review_card_top_right_logo
		$this->add_control(
			'ekit_review_card_top_right_logo',
			[
				'label'        => esc_html__('Top Right Logo', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

	}

	private function controls_section_slideshow(){

		// Left right spacing
		$this->add_responsive_control(
			'ekit_review_slideshow_left_right_spacing',
			[
				'label'           => esc_html__('Spacing Left Right', 'elementsKit-lite'),
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => ['px'],
				'range'           => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'devices'         => ['desktop', 'tablet', 'mobile'],
				'desktop_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => 10,
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => 10,
					'unit' => 'px',
				],
				'default'         => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .ekit-review-slider-wrapper-yelp .slick-slide' =>
						'margin-right: {{SIZE}}{{UNIT}};margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Slides to show
		$this->add_responsive_control(
			'ekit_review_slideshow_slides_to_show',
			[
				'label'   => esc_html__('Slides To Show', 'elementsKit-lite'),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 5,
				'step'    => 1,
				'default' => 3,
			]
		);

		// Slides to scroll
		$this->add_responsive_control(
			'ekit_review_slideshow_slides_to_scroll',
			[
				'label'   => esc_html__('Slides To Scroll', 'elementsKit-lite'),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 20,
				'step'    => 1,
				'default' => 1,
			]
		);

		// Slideshow speed
		$this->add_control(
			'ekit_review_slideshow_speed',
			[
				'label'   => esc_html__('Speed', 'elementsKit-lite'),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 10000,
				'step'    => 1,
				'default' => 1000,
			]
		);

		// Slideshow autoplay
		$this->add_control(
			'ekit_review_slideshow_autoplay',
			[
				'label'        => esc_html__('Autoplay', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'elementsKit-lite'),
				'label_off'    => esc_html__('No', 'elementsKit-lite'),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		// Show arrows
		$this->add_control(
			'ekit_review_slideshow_show_arrow',
			[
				'label'        => esc_html__('Show arrow', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'elementsKit-lite'),
				'label_off'    => esc_html__('No', 'elementsKit-lite'),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		//Show dot
		$this->add_control(
			'ekit_review_slideshow_show_dot',
			[
				'label'        => esc_html__('Show dots', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'elementsKit-lite'),
				'label_off'    => esc_html__('No', 'elementsKit-lite'),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		// Pause on hover
		$this->add_control(
			'ekit_review_slideshow_pause_on_hover',
			[
				'label'        => esc_html__('Pause on Hover', 'elementsKit-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'elementsKit-lite'),
				'label_off'    => esc_html__('No', 'elementsKit-lite'),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
	}

	private function controls_section_widget(){

		// ekit_review_widget_background
		$this->add_group_control(
			Group_Control_Background::get_type(), [
				'name'     => 'ekit_review_widget_background',
				'label'    => esc_html__('Widget Background', 'elementsKit-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ekit-review-wrapper-yelp',
			]
		);

		// ekit_review_widget_padding_only_overview
		$this->add_responsive_control(
			'ekit_review_widget_padding_only_overview',
			[
				'label'      => esc_html__('Padding', 'elementsKit-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default'    => $this->get_dimension(1),
				'selectors'  => [
					'{{WRAPPER}} .ekit-review-wrapper-yelp' => 
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				"condition"  => [
					'ekit_review_styles' => 'default',
				],
			]
		);

		// ekit_review_widget_padding
		$this->add_responsive_control(
			'ekit_review_widget_padding',
			[
				'label'      => esc_html__('Padding', 'elementsKit-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .ekit-review-wrapper-yelp' => 
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'           => $this->get_dimension(2),
				'tablet_default'    => $this->get_dimension(1),
				'mobile_default'    => $this->get_dimension(8 , 'px'),
				"condition"  => [ 'ekit_review_styles!' => 'default' ],
			]
		);

		// ekit_yelp_review_widget_border
		$this->control_border(
			'ekit_yelp_review_widget_border', 
            [ '.ekit-review-wrapper-yelp' ],
            [ 'default' => '0', 'unit' => 'px' ]
		);

	}

	private function controls_section_header_button(){

		// Header button typography
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'		 => 'ekit_yelp_review_ss_header_button_typography',
			'selector'	 => '{{WRAPPER}} .ekit-review-overview--actions .btn-primary',
		]);

		// Header button border radius
		$this->control_border(
			'ekit_yelp_review_ss_header_button_border', [ '.ekit-review-overview--actions .btn-primary' ],
			[ 'default' => '2', 'unit' => 'em' ]
		);
		
		// Header button tabs
		$this->start_controls_tabs( 'ekit_yelp_review_ss_header_button_tabs' );

		// Header button tab normal
        $this->start_controls_tab(
            'ekit_yelp_review_ss_header_button_tab_normal', [
                'label' => esc_html__( 'Normal', 'elementsKit-lite' ),
            ]
		);

		// Header button background color
		$this->add_group_control(
			Group_Control_Background::get_type(), [
				'name'     => 'ekit_yelp_review_ss_header_button_bg_color_normal',
				'label'    => esc_html__('Background', 'elementsKit-lite'),
				'types'    => ['classic'],
				'selector' => '{{WRAPPER}} .ekit-review-overview--actions .btn-primary',
			]
		);

		// Header button color
		$this->add_control( 'ekit_yelp_review_ss_header_button_color_normal',
			[
				'label'     => __('Text Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-overview--actions .btn-primary' => 'color: {{VALUE}}',
				],
			]
		);

		// Header button border color
		$this->add_control( 'ekit_yelp_review_ss_header_button_border_color_normal',
			[
				'label'     => __('Border Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-overview--actions .btn-primary' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		// Header button tab hover
        $this->start_controls_tab(
            'ekit_yelp_review_ss_header_button_tab_hover', [
                'label' => esc_html__( 'Hover', 'elementsKit-lite' ),
            ]
		);

		// Header button background color hover
		$this->add_group_control(
			Group_Control_Background::get_type(), [
				'name'     => 'ekit_yelp_review_ss_header_button_bg_color_hover',
				'label'    => esc_html__('Background', 'elementsKit-lite'),
				'types'    => ['classic'],
				'selector' => '{{WRAPPER}} .ekit-review-overview--actions .btn-primary' .':hover',
			]
		);

		// Header button color hover
		$this->add_control( 'ekit_yelp_review_ss_header_button_color_hover',
			[
				'label'     => __('Text Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-overview--actions .btn-primary' .':hover' => 'color: {{VALUE}}',
				],
			]
		);

		// Header button border color hover
		$this->add_control( 'ekit_yelp_review_ss_header_button_border_color_hover',
			[
				'label'     => __('Border Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-overview--actions .btn-primary' .':hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

	}

	private function controls_section_overview_buttons(){

		// Overviews buttons typography
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'		 => 'ekit_yelp_review_ss_oo_buttons_typography',
			'selector'	 => '{{WRAPPER}} .ekit-review-card--actions .btn',
		]);

		// See all reviews
		$this->add_control( 'ekit_yelp_review_ss_oo_sar_button', [
			'label'     => esc_html__('Sell all reviews', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING
		]);

		// See all reviews button tabs
		$this->start_controls_tabs( 'ekit_yelp_review_ss_oo_sar_buttons_tabs' );

		// See all reviews tab normal
        $this->start_controls_tab(
            'ekit_yelp_review_ss_oo_sar_button_tab_normal', [
                'label' => esc_html__( 'Normal', 'elementsKit-lite' ),
            ]
		);

		// See all reviews button color
		$this->add_control( 'ekit_yelp_review_ss_oo_sar_button_color_normal',
			[
				'label'     => __('Text Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card--actions .btn:first-child' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		// See all reviews tab hover
        $this->start_controls_tab(
            'ekit_yelp_review_ss_oo_sar_button_tab_hover', [
                'label' => esc_html__( 'Hover', 'elementsKit-lite' ),
            ]
		);

		// See all reviews button color hover
		$this->add_control( 'ekit_yelp_review_ss_oo_sar_button_color_hover',
			[
				'label'     => __('Text Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card--actions .btn:first-child:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Write a review
		$this->add_control( 'ekit_yelp_review_ss_oo_rar_button', [
			'label'     => esc_html__('Write a review', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before'
		]);

		// Start write a review button tabs
		$this->start_controls_tabs( 'ekit_yelp_review_ss_oo_rar_buttons_tabs' );

		// Write a review tab normal
        $this->start_controls_tab(
            'ekit_yelp_review_ss_oo_rar_button_tab_normal', [
                'label' => esc_html__( 'Normal', 'elementsKit-lite' ),
            ]
		);

		// Write a review button color
		$this->add_control( 'ekit_yelp_review_ss_oo_rar_button_color_normal',
			[
				'label'     => __('Text Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card--actions .btn:last-child' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		// Write a review tab hover
        $this->start_controls_tab(
            'ekit_yelp_review_ss_oo_rar_button_tab_hover', [
                'label' => esc_html__( 'Hover', 'elementsKit-lite' ),
            ]
		);

		// Write a review button color hover
		$this->add_control( 'ekit_yelp_review_ss_oo_rar_button_color_hover',
			[
				'label'     => __('Text Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card--actions .btn:last-child:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	private function controls_section_overview_card(){

		// ekit_yelp_review_overview_card_background
		$this->add_group_control(
			Group_Control_Background::get_type(), [
				'name'      => 'ekit_yelp_review_overview_card_background',
				'label'     => esc_html__('Card Background', 'elementsKit-lite'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-overview'
			]
		);

        // Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(), [
				'name' => 'ekit_yelp_review_overview_card_shadow',
				'label' => __( 'Box Shadow', 'elementsKit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-overview'
			]
		);

		// ekit_yelp_review_overview_card_padding
		$this->add_responsive_control( 'ekit_yelp_review_overview_card_padding', [
			'label'      => esc_html__('Padding', 'elementsKit-lite'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => ['px', '%', 'em'],
			'selectors'  => [ 
				'{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-overview' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .ekit-review-card-yelp .ekit-review-card--top-right-logo' => "{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}}"
			],
			'default'	        => $this->get_dimension(2),
			'tablet_default'	=> $this->get_dimension(1),
			'mobile_default'	=> $this->get_dimension(8, 'px')
		]);

		// ekit_yelp_review_overview_card_margin
		$this->add_responsive_control(
			'ekit_yelp_review_overview_card_margin', [
				'label'          => esc_html__('Margin', 'elementsKit-lite'),
				'type'           => Controls_Manager::DIMENSIONS,
				'size_units'     => ['px', '%', 'em'],
				'default'        => $this->get_dimension(0),
				'tablet_default' => $this->get_dimension(0),
				'mobile_default' => $this->get_dimension(0),
				'selectors'      => [ 
					'{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-overview' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		// ekit_yelp_review_overview_card_border
		$this->control_border( 
            'ekit_yelp_review_overview_card_border', [ '.ekit-review-card-yelp.ekit-review-card-overview' ],
            [ 'default' => '0', 'unit' => 'px' ]
        );
    }

    private function controls_section_header_card(){

		// ekit_yelp_review_header_card_background
		$this->add_group_control(
			Group_Control_Background::get_type(), [
				'name'      => 'ekit_yelp_review_header_card_background',
				'label'     => esc_html__('Card Background', 'elementsKit-lite'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '{{WRAPPER}} .ekit-review-overview.ekit-review-overview-yelp'
			]
		);

        // ekit_yelp_review_header_card_box_shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(), [
				'name' => 'ekit_yelp_review_header_card_shadow',
				'label' => __( 'Box Shadow', 'elementsKit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-review-overview.ekit-review-overview-yelp'
			]
		);

		// ekit_yelp_review_header_card_padding
		$this->add_responsive_control( 'ekit_yelp_review_header_card_padding', [
			'label'      => esc_html__('Padding', 'elementsKit-lite'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => ['px', '%', 'em'],
			'selectors'  => [ 
				'{{WRAPPER}} .ekit-review-overview.ekit-review-overview-yelp' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .ekit-review-card-yelp .ekit-review-card--top-right-logo' => "top:{{TOP}}{{UNIT}};right:{{RIGHT}}{{UNIT}};"
			],
			'default'	=> [
				'top'      => '24', 'right'    => '24',
				'bottom'   => '24', 'left'     => '24',
				'unit'     => 'px', 'isLinked' => true,
			],
			'tablet_default'	=> [
				'top'      => '1', 'right'    => '1',
				'bottom'   => '1', 'left'     => '1',
				'unit'     => 'em', 'isLinked' => true,
			],
			'mobile_default'	=> [
				'top'      => '8', 'right'    => '8',
				'bottom'   => '8', 'left'     => '8',
				'unit'     => 'px', 'isLinked' => true,
			]
		]);

		// ekit_yelp_review_header_card_margin
		$this->add_responsive_control(
			'ekit_yelp_review_header_card_margin', [
				'label'          => esc_html__('Margin', 'elementsKit-lite'),
				'type'           => Controls_Manager::DIMENSIONS,
				'size_units'     => ['px', '%', 'em'],
				'default'        => [
					'top'      => '0', 'right'    => '0',
					'bottom'   => '24', 'left'     => '0',
					'unit'     => 'px', 'isLinked' => false,
				],
				'tablet_default' => [
					'top'      => '0', 'right'    => '0',
					'bottom'   => '1', 'left'     => '0',
					'unit'     => 'em', 'isLinked' => false,
				],
				'mobile_default' => [
					'top'      => '0', 'right'    => '0',
					'bottom'   => '8', 'left'     => '0',
					'unit'     => 'px', 'isLinked' => false,
				],
				'selectors'      => [ '{{WRAPPER}} .ekit-review-overview.ekit-review-overview-yelp' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

		// ekit_yelp_review_header_card_border
		$this->control_border( 
            'ekit_yelp_review_header_card_border', [ '.ekit-review-overview.ekit-review-overview-yelp' ], 
            [ 'default' => '0', 'unit' => 'px' ] 
        );

        // Thumbnail heading
		$this->add_control( 'ekit_yelp_review_header_card_thumbnail_heading', [
			'label'     => esc_html__('Thumbnail', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
        ]);

        // ekit_yelp_review_header_card_thumbnail_size
		$this->add_responsive_control(
			'ekit_yelp_review_header_card_thumbnail_size', [
				'label' => __( 'Thumbnail Size', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 64, 'step' => 1 ],
					'em' => [ 'min' => 0, 'max' => 4, 'step' => 0.1 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 40 ],
				'tablet_default' => [ 'unit' => 'px', 'size' => 40 ],
				'mobile_default' => [ 'unit' => 'px', 'size' => 40 ],
				'selectors' => [
					'{{WRAPPER}} .ekit-review-overview.ekit-review-overview-yelp .ekit-review-overview--thumbnail thumbnail' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
        );

        // ekit_yelp_review_header_card_thumbnail_margin_right
		$this->add_responsive_control(
			'ekit_yelp_review_header_card_thumbnail_margin_right', [
				'label' => __( 'Margin Right', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 64, 'step' => 1 ],
					'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 16 ],
				'tablet_default' => [ 'unit' => 'px', 'size' => 16 ],
				'mobile_default' => [ 'unit' => 'px', 'size' => 16 ],
				'selectors' => [
					'{{WRAPPER}} .ekit-review-overview.ekit-review-overview-yelp .ekit-review-overview--thumbnail' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
        );

        // Page name heading
		$this->add_control( 'ekit_yelp_review_header_card_pagename_heading', [
			'label'     => esc_html__('Page Name', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
        ]);

        $this->control_text(
			'ekit_yelp_review_header_card_page_name', 
			'.ekit-review-overview.ekit-review-overview-yelp .ekit-review-overview--title h4 > span', 
			['margin', 'shadow']
		);
        
        // Avg rating heading
		$this->add_control( 'ekit_yelp_review_header_card_rating_avg_heading', [
			'label'     => esc_html__('Rating Average', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
        ]);

        $this->control_text(
			'ekit_yelp_review_header_card_avg_rating', 
			'.ekit-review-overview.ekit-review-overview-yelp .rating-average', 
			['margin', 'shadow']
		);
        
        // Stars heading
		$this->add_control( 'ekit_yelp_review_header_card_stars_heading', [
			'label'     => esc_html__('Stars', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
        ]);

        $this->control_text(
			'ekit_yelp_review_header_card_stars', 
			'.ekit-review-overview.ekit-review-overview-yelp .ekit-review-overview--stars', 
			['margin', 'shadow', 'typography']
		);
        
        // ekit_yelp_review_header_card_stars_margin
		$this->add_responsive_control(
			'ekit_yelp_review_header_card_stars_margin', [
				'label' => __( 'Margin Left Right', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 32, 'step' => 1 ],
					'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 10 ],
				'tablet_default' => [ 'unit' => 'px', 'size' => 10 ],
				'mobile_default' => [ 'unit' => 'px', 'size' => 10 ],
				'selectors' => [
					'{{WRAPPER}} .ekit-review-overview.ekit-review-overview-yelp .ekit-review-overview--stars' => 'margin-right: {{SIZE}}{{UNIT}};margin-left: {{SIZE}}{{UNIT}};',
				],
			]
        );

        // Desc heading
		$this->add_control( 'ekit_yelp_review_header_card_desc_heading', [
			'label'     => esc_html__('Reviews Count', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
        ]);

        $this->control_text(
			'ekit_yelp_review_header_card_desc',
			'.ekit-review-overview.ekit-review-overview-yelp .rating-text', 
			['margin', 'shadow']
		);
    }

    private function controls_section_review_card(){

		// ekit_yelp_review_review_card_background
		$this->add_group_control(
			Group_Control_Background::get_type(), [
				'name'      => 'ekit_yelp_review_review_card_background',
				'label'     => esc_html__('Card Background', 'elementsKit-lite'),
				'types'     => ['classic', 'gradient'],
				'selector'  => '{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp'
			]
		);

        // Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(), [
				'name' => 'ekit_yelp_review_review_card_shadow',
				'label' => __( 'Box Shadow', 'elementsKit-lite' ),
				'selector' => '{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp'
			]
		);

		// ekit_yelp_review_review_card_padding
		$this->add_responsive_control( 'ekit_yelp_review_review_card_padding', [
			'label'      => esc_html__('Padding', 'elementsKit-lite'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => ['px', '%', 'em'],
			'selectors'  => [ 
				'{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp .ekit-review-card--top-right-logo' => "top:{{TOP}}{{UNIT}};right:{{RIGHT}}{{UNIT}};"
			],
			'default'           => $this->get_dimension( 2, 'em' ),
			'tablet_default'	=> $this->get_dimension( 1, 'em' ),
            'mobile_default'	=> $this->get_dimension( 8, 'px' ),
            'condition'         => [ 'ekit_review_card_type' => 'default' ]
        ]);
        //.ekit-wid-con .ekit-review-card-bubble:after

        // ekit_yelp_review_review_card_padding_bubble
		$this->add_responsive_control( 'ekit_yelp_review_review_card_padding_bubble', [
			'label'      => esc_html__('Padding', 'elementsKit-lite'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => ['px'],
			'selectors'  => [ 
				'{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp .ekit-review-card--top-right-logo' => "top:{{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}};",
                '{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-bubble:after' => "left:{{TOP}}{{UNIT}}; top: calc(calc(100% - 116px) + calc({{TOP}}{{UNIT}} - 24px));",
				//'{{WRAPPER}} ' . $comment => "margin-bottom: calc($b - 1rem)",
			],
			'default'           => $this->get_dimension( 24, 'px' ),
			'tablet_default'	=> $this->get_dimension( 16, 'px' ),
            'mobile_default'	=> $this->get_dimension( 8, 'px' ),
            'condition'         => [ 'ekit_review_card_type' => 'bubble' ]
        ]);

		// ekit_yelp_review_review_card_margin
		$this->add_responsive_control( 'ekit_yelp_review_review_card_margin', [
			'label'      => esc_html__('Margin', 'elementsKit-lite'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => ['px', '%', 'em'],
			'selectors'  => [ '{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'default'           => $this->get_dimension( [0, 0, 24, 0], 'px', false ),
			'tablet_default'	=> $this->get_dimension( [0, 0, 16, 0], 'px', false ),
			'mobile_default'	=> $this->get_dimension( [0, 0, 8, 0], 'px', false ),
            'condition' => [ 'ekit_review_card_appearance' => 'list' ]
		]);

		// ekit_yelp_review_review_card_border
        $this->control_border(
			'ekit_yelp_review_review_card_border', 
			[ '.ekit-review-card.ekit-review-card-yelp' ], 
			[ 'default' => '0', 'unit' => 'px' ] 
		);

    }

    private function controls_section_oc_page_pro_pic(){

        // Container heading
		$this->add_control( 'ekit_yelp_review_ss_oc_page_pro_pic_con_heading', [
			'label'     => esc_html__('Container', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
        ]);

        // ekit_yelp_review_ss_oc_page_pro_pic_con_size
        $this->add_responsive_control( 'ekit_yelp_review_ss_oc_page_pro_pic_con_size', [
            'label' => __( 'Container Size', 'elementsKit-lite' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'px' => [ 'min' => 40, 'max' => 128, 'step' => 4 ],
                'em' => [ 'min' => 2, 'max' => 8, 'step' => 0.2 ],
            ],
            'default' => [ 'unit' => 'px', 'size' => 60 ],
            'tablet_default' => [ 'unit' => 'px', 'size' => 60 ],
            'mobile_default' => [ 'unit' => 'px', 'size' => 60 ],
            'selectors' => [
                '{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-overview .ekit-review-card--image' => "height:{{SIZE}}{{UNIT}};width:{{SIZE}}{{UNIT}};min-height:{{SIZE}}{{UNIT}};min-width:{{SIZE}}{{UNIT}};",
            ],
        ]);

        // ekit_yelp_review_ss_oc_page_pro_pic_con_padding
		$this->add_responsive_control( 'ekit_yelp_review_ss_oc_page_pro_pic_con_padding', [
			'label'      => esc_html__('Padding', 'elementsKit-lite'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => ['px', '%', 'em'],
			'selectors'  => [ '{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-overview .ekit-review-card--image' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'default'           => $this->get_dimension( 16, 'px' ),
			'tablet_default'	=> $this->get_dimension( 12, 'px' ),
            'mobile_default'	=> $this->get_dimension( 8, 'px' )
        ]);

        // ekit_yelp_review_ss_oc_page_pro_pic_con_margin_right
        $this->add_responsive_control(
			'ekit_yelp_review_ss_oc_page_pro_pic_con_margin_right', [
				'label' => __( 'Margin Right', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 32, 'step' => 1 ],
					'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 16 ],
				'tablet_default' => [ 'unit' => 'px', 'size' => 16 ],
				'mobile_default' => [ 'unit' => 'px', 'size' => 16 ],
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-overview .ekit-review-card--image' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
        );

        // ekit_yelp_review_ss_oc_page_pro_pic_con_border_radius
		$this->add_control( 'ekit_yelp_review_ss_oc_page_pro_pic_con_border_radius', [
			'label'			=> esc_html__('Border Radius', 'elementsKit-lite'),
			'type'			=> Controls_Manager::DIMENSIONS,
			'size_units'	=> ['px', '%', 'em'],
			'selectors'		=> [ 
                '{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-overview .ekit-review-card--image' => 
                    'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
            ],
			'default'    => [
				'top'      => '50', 'right'	=> '50',
				'bottom'   => '50', 'left'	=> '50',
				'unit'     => '%', 'isLinked' => true,
            ]
        ]);

        // Image heading
		$this->add_control( 'ekit_yelp_review_ss_oc_page_pro_pic_image_heading', [
			'label'     => esc_html__('Profile Picture', 'elementsKit-lite'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before'
        ]);

        // ekit_yelp_review_ss_oc_page_pro_pic_image_border_radius
		$this->add_control( 'ekit_yelp_review_ss_oc_page_pro_pic_image_border_radius', [
			'label'			=> esc_html__('Border Radius', 'elementsKit-lite'),
			'type'			=> Controls_Manager::DIMENSIONS,
			'size_units'	=> ['px', '%', 'em'],
			'selectors'		=> [ 
                '{{WRAPPER}} .ekit-review-card-yelp.ekit-review-card-overview .ekit-review-card--image .thumbnail' => 
                    'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
            ],
			'default'    => [
				'top'      => '50', 'right'	=> '50',
				'bottom'   => '50', 'left'	=> '50',
				'unit'     => '%', 'isLinked' => true,
			]
		]);
    }

    private function controls_section_reviewer_thumbnail(){

        // ekit_yelp_review_reviewer_thumbnail_size
        $this->add_responsive_control(
			'ekit_yelp_review_reviewer_thumbnail_size', [
				'label' => __( 'Thumbnail Size', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 96, 'step' => 4 ],
					'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.2 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 40 ],
				'tablet_default' => [ 'unit' => 'px', 'size' => 40 ],
				'mobile_default' => [ 'unit' => 'px', 'size' => 40 ],
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp .ekit-review-card--thumbnail .thumbnail' => "height:{{SIZE}}{{UNIT}};width:{{SIZE}}{{UNIT}};min-height:{{SIZE}}{{UNIT}};min-width:{{SIZE}}{{UNIT}};",
				],
			]
        );

        // ekit_yelp_review_reviewer_thumbnail_margin_right
        $this->add_responsive_control(
			'ekit_yelp_review_reviewer_thumbnail_margin_right', [
				'label' => __( 'Margin Right', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 32, 'step' => 1 ],
					'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 16 ],
				'tablet_default' => [ 'unit' => 'px', 'size' => 16 ],
				'mobile_default' => [ 'unit' => 'px', 'size' => 16 ],
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp .ekit-review-card--thumbnail' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
			]
        );

        // ekit_yelp_review_reviewer_thumbnail_border_radius
		$this->add_control( 'ekit_yelp_review_reviewer_thumbnail_border_radius', [
			'label'			=> esc_html__('Border Radius', 'elementsKit-lite'),
			'type'			=> Controls_Manager::DIMENSIONS,
			'size_units'	=> ['px', '%', 'em'],
			'selectors'		=> [ 
                '{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp .ekit-review-card--thumbnail .thumbnail' => 
                    'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
            ],
			'default'    => [
				'top'      => '50', 'right'	=> '50',
				'bottom'   => '50', 'left'	=> '50',
				'unit'     => '%', 'isLinked' => true,
			]
		]);
    }

    private function controls_section_reviewer_name(){
		$this->control_text(
			'controls_section_reviewer_name', 
			'.ekit-review-card.ekit-review-card-yelp .ekit-review-card--name', [], 
			[ "def_margin" => [ 'bottom' => '8', 'unit' => 'px', 'isLinked' => false ]
        ]);
    }

    private function controls_section_reviewer_card_date(){
		$this->control_text(
			'controls_section_reviewer_card_date', 
			'.ekit-review-card.ekit-review-card-yelp .ekit-review-card--date', 
			[ 'shadow', 'margin' ]
		);
    }

    private function controls_section_reviewer_card_stars(){
		$this->control_text(
			'controls_section_reviewer_card_stars', 
			'.ekit-review-card.ekit-review-card-yelp .ekit-review-card--stars i', 
			[ 'shadow', 'margin', 'typography' ]
		);
    }

    private function controls_section_reviewer_card_review(){

		$this->control_text(
			'controls_section_reviewer_card_review', 
			'.ekit-review-card.ekit-review-card-yelp .ekit-review-card--comment', [], 
			[
				"def_margin" => [ 'bottom' => '1', 'unit' => 'em', 'isLinked' => false 
			]
        ]);

        // controls_section_reviewer_card_review_padding
		$this->add_responsive_control( 'controls_section_reviewer_card_review_padding', [
			'label'      => esc_html__('Padding', 'elementsKit-lite'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => ['px', '%', 'em'],
			'selectors'  => [ '{{WRAPPER}} .ekit-review-card.ekit-review-card-yelp .ekit-review-card--comment' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			'default'           => [ 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '0', 'unit' => 'em', 'isLinked' => false, ],
			'tablet_default'	=> [ 'top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '0', 'unit' => 'px', 'isLinked' => false, ],
			'mobile_default'	=> [ 'top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '0', 'unit' => 'px', 'isLinked' => false, ]
		]);
    }

	private function controls_section_overview_page_name(){
		$this->control_text(
			'controls_section_overview_page_name', 
			'.ekit-review-card-yelp.ekit-review-card-overview .ekit-review-card--name'
		);
	}

	private function controls_section_overview_desc(){
		$this->control_text(
			'controls_section_overview_desc', 
			'.ekit-review-card-yelp.ekit-review-card-overview .ekit-review-card--desc', 
			['margin']
		);
	}

	private function controls_section_overview_stars(){

		// Overview stars
		$this->add_control(
			'controls_section_overview_average_rating_heading', [
				'label'     => esc_html__('Average Rating', 'elementsKit-lite'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		// Overview average rating text
		$this->control_text(
			'controls_section_overview_average_rating', 
			'.ekit-review-card-yelp.ekit-review-card-overview .ekit-review-card--average', 
			['margin','shadow']
		);

		// Overview stars
		$this->add_control(
			'controls_section_overview_stars_heading', [
				'label'     => esc_html__('Stars', 'elementsKit-lite'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		// Overview average rating text
		$this->control_text(
			'ekit_yelp_review_overview_rating_stars', 
			'.ekit-review-card-yelp.ekit-review-card-overview .ekit-review-card--stars i', 
			['margin','shadow','typography']
		);
	}

	private function controls_section_top_right_logo(){

        // Top right brand icon
        $this->add_control(
            'controls_section_top_right_logo_icons', [
                'label' => esc_html__( 'Header Icon', 'elementsKit-lite' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'controls_section_top_right_logo_icon',
                'default' => [
                    'value' => 'fab fa-yelp',
                    'library' => 'fa-brands',
                ],
                'label_block' => true
            ]
        );

        // Top right brand icon size
		$this->add_responsive_control(
			'controls_section_top_right_logo_size', [
				'label' => __( 'Logo Size', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 96, 'step' => 4 ],
					'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.2 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 20 ],
				'tablet_default' => [ 'unit' => 'px', 'size' => 20 ],
				'mobile_default' => [ 'unit' => 'px', 'size' => 20 ],
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card-yelp .ekit-review-card--top-right-logo i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
        );

        // Top right brand icon color
        $this->add_control(
			'controls_section_top_right_logo_color', [
				'label'     => __('Logo Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card-yelp .ekit-review-card--top-right-logo i' => 'color: {{VALUE}}',
				],
			]
		);
    }

	private function controls_section_bottom_posted_on(){

        // Icon heading
		$this->add_control( 'ekit_yelp_review_card_bottom_posted_on_icon_heading', [
			'label'     => esc_html__('Icon', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
        ]);

        // ekit_yelp_review_posted_on_icons
        $this->add_control(
            'ekit_yelp_review_posted_on_icons', [
                'label' => esc_html__( 'Posted On Icon', 'elementsKit-lite' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'ekit_yelp_review_posted_on_icon',
                'default' => [
                    'value' => 'fab fa-yelp',
                    'library' => 'fa-brands',
                ],
                'label_block' => true
            ]
        );

        // ekit_yelp_review_posted_on_icon_size
		$this->add_responsive_control(
			'ekit_yelp_review_posted_on_icon_size', [
				'label' => __( 'Icon Size', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 96, 'step' => 4 ],
					'em' => [ 'min' => 0, 'max' => 6, 'step' => 0.2 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 32 ],
				'tablet_default' => [ 'unit' => 'px', 'size' => 32 ],
				'mobile_default' => [ 'unit' => 'px', 'size' => 32 ],
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card-yelp .ekit-review-card--posted-on i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
        );

        // ekit_yelp_review_posted_on_icon_color
        $this->add_control(
			'ekit_yelp_review_posted_on_icon_color', [
				'label'     => __('Icon Color', 'elementsKit-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card-yelp .ekit-review-card--posted-on i' => 'color: {{VALUE}}',
				],
			]
        );
        
        // ekit_yelp_review_posted_on_icon_margin_right
		$this->add_responsive_control(
			'ekit_yelp_review_posted_on_icon_margin_right', [
				'label' => __( 'Margin Right', 'elementsKit-lite' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 32, 'step' => 1 ],
					'em' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 12 ],
				'tablet_default' => [ 'unit' => 'px', 'size' => 12 ],
				'mobile_default' => [ 'unit' => 'px', 'size' => 12 ],
				'selectors' => [
					'{{WRAPPER}} .ekit-review-card-yelp .ekit-review-card--posted-on i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
        );

        // Icon heading
		$this->add_control( 'ekit_yelp_review_card_bottom_posted_on_heading', [
			'label'     => esc_html__('Posted On', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
        ]);

        $this->control_text(
			'ekit_yelp_review_card_bottom_posted_on',
			'.ekit-review-card-yelp .ekit-review-card--posted-on p', 
			['margin', 'shadow']
		);

        // Yelp heading
		$this->add_control( 'ekit_yelp_review_card_bottom_posted_on_fb_heading', [
			'label'     => esc_html__('Yelp', 'elementsKit-lite'),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
        ]);

		$this->control_text(
			'ekit_yelp_review_card_bottom_posted_on_yelp',
			'.ekit-review-card-yelp .ekit-review-card--posted-on h5', 
			['margin', 'shadow']
		);
	}

	protected function _register_controls() {

		// Layout section
		$this->controls_section([ 'label' => esc_html__('Layout', 'elementsKit-lite'),          'key' => 'ekit_yelp_review_cs_layout' ],          'controls_section_layout');

        // Contents section
		$this->controls_section([ 'label' => esc_html__('Contents', 'elementsKit-lite'),        'key' => 'ekit_yelp_review_cs_contents' ],        'controls_section_contents');

        // Slideshow section
		$this->controls_section([ 'label' => esc_html__('Slideshow', 'elementsKit-lite'),       'key' => 'ekit_yelp_review_cs_slideshow',         'condition' => [ 'ekit_review_styles' => 'slideshow' ]], 'controls_section_slideshow');

        // Widget section
		$this->controls_section([ 'label' => esc_html__('Widget', 'elementsKit-lite'),          'key' => 'ekit_yelp_review_ss_widget',            'tab' => Controls_Manager::TAB_STYLE ], 'controls_section_widget');

        // Overview card section
		$this->controls_section([ 'label' => esc_html__('Card', 'elementsKit-lite'),            'key' => 'ekit_yelp_review_ss_overview_card',     'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles' => 'default' ]], 'controls_section_overview_card');

        // Reviewer Name
        $this->controls_section([ 'label' => esc_html__('Page Profile Picture', 'elementsKit-lite'), 'key' => 'ekit_yelp_review_ss_oc_page_pro_pic', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles' => 'default' ]], 'controls_section_oc_page_pro_pic');

		// Overview page name section
		$this->controls_section([ 'label' => esc_html__('Page Name', 'elementsKit-lite'),       'key' => 'ekit_yelp_review_ss_overview_page_name', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles' => 'default' ]], 'controls_section_overview_page_name');
        
		// Overview stars section
		$this->controls_section([ 'label' => esc_html__('Rating and Stars', 'elementsKit-lite'), 'key' => 'ekit_yelp_review_ss_overview_stars',   'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles' => 'default' ]], 'controls_section_overview_stars');
        
		// Overview description section
		$this->controls_section([ 'label' => esc_html__('Description', 'elementsKit-lite'),     'key' => 'ekit_yelp_review_ss_overview_desc',     'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles' => 'default' ]], 'controls_section_overview_desc');
        
		// Overview Buttons section
		$this->controls_section([ 'label' => esc_html__('Buttons', 'elementsKit-lite'),         'key' => 'ekit_yelp_review_ss_oo_buttons',        'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles' => 'default' ]], 'controls_section_overview_buttons');
        
        // Header card section
		$this->controls_section([ 'label' => esc_html__('Header Card', 'elementsKit-lite'),     'key' => 'ekit_yelp_review_ss_header_card',       'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles!' => 'default', 'ekit_review_overview_card' => 'yes' ]], 'controls_section_header_card');
        
        // Header button section
		$this->controls_section([ 'label' => esc_html__('Header Button', 'elementsKit-lite'),   'key' => 'ekit_yelp_review_ss_header_button',     'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles!' => 'default', 'ekit_review_overview_card' => 'yes' ]], 'controls_section_header_button');

        // Review card section
		$this->controls_section([ 'label' 	=> esc_html__('Review Card', 'elementsKit-lite'),   'key' => 'ekit_yelp_review_ss_review_card',       'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles!' => 'default' ]], 'controls_section_review_card');
        
        // Reviewer Name
        $this->controls_section([ 'label' => esc_html__('Reviewer Thumbnail', 'elementsKit-lite'), 'key' => 'ekit_yelp_review_ss_reviewer_thumbnail', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles!' => 'default' ]], 'controls_section_reviewer_thumbnail');
        
        // Reviewer Name
		$this->controls_section([ 'label' => esc_html__('Reviewer Name', 'elementsKit-lite'),   'key' => 'ekit_yelp_review_ss_reviewer_name',     'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles!' => 'default' ]], 'controls_section_reviewer_name');

        // Review card date
		$this->controls_section([ 'label' => esc_html__('Review Date', 'elementsKit-lite'),     'key' => 'ekit_yelp_review_ss_review_card_date',  'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles!' => 'default' ]], 'controls_section_reviewer_card_date');

        // Review card stars
		$this->controls_section([ 'label' => esc_html__('Review Stars', 'elementsKit-lite'),    'key' => 'ekit_yelp_review_ss_review_card_stars', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles!' => 'default' ]], 'controls_section_reviewer_card_stars');

        // Review card review
		$this->controls_section([ 'label' => esc_html__('Review Feedback', 'elementsKit-lite'), 'key' => 'ekit_yelp_review_ss_review_card_review', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_styles!' => 'default' ]], 'controls_section_reviewer_card_review');

		// Top right brand logo
        $this->controls_section([ 'label' => esc_html__('Top Right Logo', 'elementsKit-lite'),  'key' => 'ekit_yelp_review_top_right_logo',       'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_card_top_right_logo' => 'yes' ]], 'controls_section_top_right_logo');
        
        // Bottom posted on logo
		$this->controls_section([ 'label' => esc_html__('Posted On', 'elementsKit-lite'),       'key' => 'ekit_yelp_review_bottom_posted_on_section', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => [ 'ekit_review_card_posted_on' => 'yes', 'ekit_review_styles!' => 'default' ]], 'controls_section_bottom_posted_on');

	}

    protected function render( ) {
        echo '<div class="ekit-wid-con" >';
            $this->render_raw();
        echo '</div>';
	}

    public function render_raw(){

        $settings = $this->get_settings_for_display();
		extract($settings);

        $handler_url = Handler::get_url();

        //=================================

        $overview             = isset($ekit_review_overview_card) && $ekit_review_overview_card == 'yes';
        $allOverviews         = isset($ekit_review_show_all_overviews) && $ekit_review_show_all_overviews == 'yes';
        $thumbnail_badge      = isset($ekit_review_card_thumbnail_badge) && $ekit_review_card_thumbnail_badge == 'yes';
        $border               = isset($ekit_review_card_border_type_border) && $ekit_review_card_border_type_border;
        $align_content_center = $ekit_review_card_align_center == 'yes' && $ekit_review_card_type != 'bubble' && $ekit_review_styles != 'default';
        $format_comment       = $ekit_review_card_appearance == 'grid' || $ekit_review_styles == 'slideshow';

        // Start Joining Card Classes
        $card_classes = 'ekit-review-card ekit-review-card-yelp';
        if($align_content_center)                       $card_classes .= ' ekit-review-card-align-center';
        if($ekit_review_styles == 'overviews')          $card_classes .= ' ekit-review-card-overview';
        if($ekit_review_card_stars_inline == 'yes')     $card_classes .= ' ekit_review_card_stars_inline';
        if($ekit_review_card_thumbnail_left == 'yes')   $card_classes .= ' ekit-review-card-thumbnail-left';
        if($ekit_review_card_name_at_bottom == 'yes')   $card_classes .= ' ekit-review-card-name-bottom ekit-review-card-thumbnail-left';
        if($ekit_review_card_type == 'bubble' && $ekit_review_styles != 'overviews') $card_classes .= ' ekit-review-card-bubble';

        // End Joining Card Classes

        $data = Handler::get_data();

        if (isset($data->error)):
            return;
        else:

            $page_Info   = null;

            ?>

            <!-- Start Markup -->
            <div class="ekit-review-wrapper ekit-review-wrapper-yelp">

				<?php if($ekit_review_styles == 'default') :
					require Handler::get_dir() . 'markup/only-overviews.php';
				else:

					if($overview && $page_Info) require Handler::get_dir() . 'markup/overview-card.php';
					if(!empty($data->reviews)):
						if($ekit_yelp_review_only_positive == 'positive') {
							$data->reviews = array_filter($data->reviews, function($item) {
								return $item->rating >= 4 ;
							});
						}
						?>
                        <div class="ekit-review-cards ekit-review-cards-facebok <?php echo "ekit-review-cards-" . $ekit_review_card_appearance ?>">
							<?php
							if(isset($ekit_review_styles) && $ekit_review_styles == 'slideshow'):
								$data_attrs = isset($ekit_review_slideshow_slides_to_show)      ? "data-slidestoshow=" . "'$ekit_review_slideshow_slides_to_show' "     : "";
								$data_attrs .= isset($ekit_review_slideshow_slides_to_scroll)   ? "data-slidestoscroll=" . "'$ekit_review_slideshow_slides_to_scroll' " : "";
								$data_attrs .= isset($ekit_review_slideshow_speed)              ? "data-speed=" . "'$ekit_review_slideshow_speed' "                     : "";
								$data_attrs .= isset($ekit_review_slideshow_autoplay)           ? "data-autoplay=" . "'$ekit_review_slideshow_autoplay' "               : "";
								$data_attrs .= isset($ekit_review_slideshow_show_arrow)         ? "data-showarrow=" . "'$ekit_review_slideshow_show_arrow' "            : "";
								$data_attrs .= isset($ekit_review_slideshow_show_dot)           ? "data-showdot=" . "'$ekit_review_slideshow_show_dot' "                : "";
								$data_attrs .= isset($ekit_review_slideshow_pause_on_hover)     ? "data-pauseonhover=" . "'$ekit_review_slideshow_pause_on_hover' "     : "";
								?>
                                <!-- Start slideshow -->
                                <div class="ekit-review-slider-wrapper ekit-review-slider-wrapper-yelp"
                                    <?php echo \ElementsKit\Utils::render($data_attrs); ?>
                                >
									<?php foreach($data->reviews as $item) :
										$time      = strtotime($item->time_created);
                                        $star_icon = $item->rating == 5 ? 'icon-star-1' : 'icon-star1';
                                    ?>
                                        <div>
                                            <?php require Handler::get_dir() . 'markup/review-card.php'; ?>
                                        </div>
                                    <?php endforeach;?>
                                </div>
                                <!-- End slideshow -->
								<?php

                            elseif($ekit_review_card_appearance == 'grid'): ?>
                                <!-- Start review cards -->
                                <div class="row"> <?php

									foreach($data->reviews as $item) :
										$time = strtotime($item->time_created);
										$star_icon = $item->rating == 5 ? 'icon-star-1' : 'icon-star1'; ?>

                                        <div class='<?php echo esc_attr($ekit_review_column); ?>'>
											<?php require Handler::get_dir() . 'markup/review-card.php'; ?>
                                        </div> <?php

									endforeach; ?>

                                </div>
                                <!-- End review cards -->
								<?php

                            elseif($ekit_review_card_appearance == 'masonry'): ?>
                                <div class="masonry <?php echo "column-count-$ekit_review_masonry_column_count" ?>">
									<?php
									foreach($data->reviews as  $item) :
										$time      = strtotime($item->time_created);
										$star_icon = $item->rating == 5 ? 'icon-star-1' : 'icon-star1';
										require Handler::get_dir() . 'markup/review-card.php';
									endforeach;
									?>
                                </div>
								<?php

							else:
								foreach($data->reviews as $key => $item) :
									$time      = strtotime($item->time_created);
									$star_icon = $item->rating == 5 ? 'icon-star-1' : 'icon-star1';

									require Handler::get_dir() . 'markup/review-card.php';
								endforeach;
							endif; ?>
                        </div>
						<?php

					endif;
				endif ?>

            </div>
            <!-- End Markup -->

            <?php
        endif;
    }
}