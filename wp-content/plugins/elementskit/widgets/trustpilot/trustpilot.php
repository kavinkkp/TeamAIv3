<?php

namespace Elementor;

use \Elementor\ElementsKit_Widget_Trustpilot_Handler as Handler;
use \ElementsKit_Lite\Modules\Controls\Controls_Manager as ElementsKit_Controls_Manager;

if (!defined('ABSPATH')) exit;

class ElementsKit_Widget_Trustpilot extends Widget_Base
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

    private function get_rating_type( $rating ){
        return ($rating <= 1 ? 'bad' : ($rating <=3 ? 'average' : 'good'));
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

    protected function _register_controls() {

        // ==============================
        // Start Layout Section
        // ==============================

        // Section heading
        $this->start_controls_section(
           'ekit_review_layout', [
              'label' => esc_html__( 'Layout', 'elementsKit-lite' ),
           ]
        );

        // ekit_review_layout_type
        $this->add_control(
           'ekit_review_layout_type',[
                'label' =>esc_html__( 'Layout Type', 'elementsKit-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => [
                    'grid' => esc_html__( 'Grid', 'elementsKit-lite' ),
                    'slideshow' => esc_html__( 'Slideshow', 'elementsKit-lite' ),
                    'masonry' => esc_html__( 'Masonry', 'elementsKit-lite' ),
                    'list' => esc_html__( 'List', 'elementsKit-lite' ),
                ],
           ]
        );

        // ekit_review_card_style
        $this->add_control(
           'ekit_review_card_style', [
              'label' => esc_html__('Choose Style', 'elementsKit-lite'),
              'type' => ElementsKit_Controls_Manager::IMAGECHOOSE,
              'default' => 'default',
              'options' => [
                 'default' => [
                    'title' => esc_html__( 'Default', 'elementsKit-lite' ),
                    'imagelarge' => Handler::get_url() . 'assets/images/default.png',
                    'imagesmall' => Handler::get_url() . 'assets/images/default.png',
                    'width' => '25%'
                 ],
                 'style-2' => [
                    'title' => esc_html__( 'Style 2', 'elementsKit-lite' ),
                    'imagelarge' => Handler::get_url() . 'assets/images/style-2.png',
                    'imagesmall' => Handler::get_url() . 'assets/images/style-2.png',
                    'width' => '25%'
                 ],
                 'style-3' => [
                    'title' => esc_html__( 'Style 3', 'elementsKit-lite' ),
                    'imagelarge' => Handler::get_url() . 'assets/images/style-3.png',
                    'imagesmall' => Handler::get_url() . 'assets/images/style-3.png',
                    'width' => '25%'
                 ],
                 'style-4' => [
                    'title' => esc_html__( 'Style 4', 'elementsKit-lite' ),
                    'imagelarge' => Handler::get_url() . 'assets/images/style-4.png',
                    'imagesmall' => Handler::get_url() . 'assets/images/style-4.png',
                    'width' => '25%'
                 ],
              ],
           ]
        );

        // ekit_review_show_overview
        // $this->add_control(
        //    'ekit_review_show_overview', [
        //         'label' => esc_html__( 'Show Overview', 'elementsKit-lite' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'yes'
        //    ]
        // );

        // ekit_review_column
        $this->add_responsive_control(
			'ekit_review_column',
			[
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
                    'ekit_review_layout_type' => 'grid'
                ]
			]
		);

        // Masonry column count
        $this->add_control(
			'ekit_review_masonry_column_count',
			[
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
                    'ekit_review_layout_type' => 'masonry'
                ],
			]
		);

		// ekit_review_masonry_column_gap
		$this->add_responsive_control(
			'ekit_review_masonry_column_gap',
			[
				'label'           => esc_html__('Column Gap', 'elementsKit-lite'),
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
					'size' => 32,
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => 24,
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => 16,
					'unit' => 'px',
				],
				'default'         => [
					'size' => 32,
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .ekit-review-cards-trustpilot.ekit-review-cards-masonry .masonry' =>
						'column-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'ekit_review_layout_type' => 'masonry'
				],
			]
		);

        $this->end_controls_section();

        // ==============================
        // End Layout Section
        // ==============================


        // ==============================
        // Start Slideshow Section
        // ==============================

        // Section label
        $this->start_controls_section(
           'ekit_review_slideshow_settings', [
              'label' => esc_html__( 'Slide Show', 'elementsKit-lite' ),
              'condition' => [
                 'ekit_review_layout_type' => 'slideshow'
              ]
           ]
        );

        // Left right spacing
        $this->add_responsive_control(
            'ekit_review_slideshow_left_right_spacing', [
                'label' => esc_html__( 'Spacing Left Right', 'elementsKit-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ekit-review-slider-wrapper-trustpilot .slick-slide' => 'margin-right: {{SIZE}}{{UNIT}};margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Slides to show
        $this->add_responsive_control(
            'ekit_review_slideshow_slides_to_show', [
                'label' => esc_html__( 'Slides To Show', 'elementsKit-lite' ),
                'type' =>  Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'default' => 3,
            ]
        );

        // Slides to scroll
        $this->add_responsive_control(
            'ekit_review_slideshow_slides_to_scroll', [
                'label' => esc_html__( 'Slides To Scroll', 'elementsKit-lite' ),
                'type' =>  Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 1,
            ]
        );

        // Slideshow speed
        $this->add_control(
            'ekit_review_slideshow_speed', [
                'label' => esc_html__( 'Speed', 'elementsKit-lite' ),
                'type' =>  Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10000,
                'step' => 1,
                'default' => 1000,
            ]
        );

        // Slideshow autoplay
        $this->add_control(
            'ekit_review_slideshow_autoplay', [
                'label' => esc_html__( 'Autoplay', 'elementsKit-lite' ),
                'type' =>  Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementsKit-lite' ),
                'label_off' => esc_html__( 'No', 'elementsKit-lite' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show arrows
        $this->add_control(
              'ekit_review_slideshow_show_arrow', [
                  'label' => esc_html__( 'Show arrow', 'elementsKit-lite' ),
                  'type' =>   Controls_Manager::SWITCHER,
                  'label_on' => esc_html__( 'Yes', 'elementsKit-lite' ),
                  'label_off' => esc_html__( 'No', 'elementsKit-lite' ),
                  'return_value' => 'yes',
                  'default' => '',
              ]
        );

        //Show dot
        $this->add_control(
            'ekit_review_slideshow_show_dot', [
                'label' => esc_html__( 'Show dots', 'elementsKit-lite' ),
                'type' =>   Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementsKit-lite' ),
                'label_off' => esc_html__( 'No', 'elementsKit-lite' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        // Pause on hover
        $this->add_control(
           'ekit_review_slideshow_pause_on_hover', [
              'label' => esc_html__( 'Pause on Hover', 'elementsKit-lite' ),
              'type' => Controls_Manager::SWITCHER,
              'label_on' => esc_html__( 'Yes', 'elementsKit-lite' ),
              'label_off' => esc_html__( 'No', 'elementsKit-lite' ),
              'return_value' => 'yes',
              'default' => 'yes',
           ]
        );

        $this->end_controls_section();
        // ==============================
        // End Slideshow Section
        // ==============================


        // ==============================
		// Start widget basic styles
		// ==============================

		$this->start_controls_section(
			'ekit_review_widget_style',
			[
				'label' => esc_html__('Widget', 'elementsKit-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// ekit_review_widget_background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'ekit_review_widget_background',
				'label'    => esc_html__('Widget Background', 'elementsKit-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ekit-review-wrapper-trustpilot',
			]
		);

        // widget padding
		$this->add_responsive_control(
			'ekit_review_widget_padding', [
				'label'      => esc_html__('Padding', 'elementsKit-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default'    => [
					'top'      => '2', 'right'    => '2',
					'bottom'   => '2', 'left'     => '2',
					'unit'     => 'em', 'isLinked' => true,
                ],
                'tablet_default'  => [
                    'top' => '1.5', 'right' => '1.5',
                    'bottom' => '1.5', 'left' => '1.5',
                    'unit' => 'em', 'isLinked' => true,
                ],
                'mobile_default'  => [
                    'top' => '1', 'right' => '1',
                    'bottom' => '1', 'left' => '1',
                    'unit' => 'em', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ekit-review-wrapper-trustpilot' =>
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


        // ==============================
		// Start review card styles
		// ==============================
        $this->start_controls_section(
           'ekit_review_card_style_section', [
                'label' => esc_html__( 'Review Card', 'elementsKit-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
           ]
        );

        // Card name color
        $this->add_control(
            'ekit_review_card_name_color', [
                'label' => __( 'Name Color', 'elementsKit-lite' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ekit-review-wrapper-trustpilot .ekit-review-card--name' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'ekit_review_card_style!' => 'default'
                ]
            ]
        );

        // Card name typpgraphy
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'ekit_review_card_name_typography',
                'label' => __( 'Name Typography', 'elementsKit-lite' ),
                'selector' => '{{WRAPPER}} .ekit-review-wrapper-trustpilot .ekit-review-card--name',
                'condition' => [
                    'ekit_review_card_style!' => 'default'
                ]
            ]
        );

        // ekit_review_card_background
        $this->add_group_control(
            Group_Control_Background::get_type(), [
                'name'      => 'ekit_review_card_background',
                'label'     => esc_html__( 'Card Background', 'elementsKit-lite' ),
                'types'     => [ 'classic', 'gradient' ],
                'selector'  => '{{WRAPPER}} .ekit-review-card-trustpilot'
            ]
        );

        // Card dimensions
        $this->add_control(
            'ekit_review_card_heading_dimensions', [
               'label' => esc_html__( 'Dimensions', 'elementsKit-lite' ),
               'type' => Controls_Manager::HEADING,
               'separator' => 'before'
            ]
        );

        // ekit_review_card_padding
        $this->add_responsive_control(
            'ekit_review_card_padding',
            [
                'label'      => esc_html__( 'Padding', 'elementsKit-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ekit-review-card-trustpilot' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ekit-review-overview-trustpilot' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'  => [
                    'top' => '2', 'right' => '2',
                    'bottom' => '2', 'left' => '2',
                    'unit' => 'em', 'isLinked' => true,
                ],
            ]
        );

        // ekit_review_card_margin
        $this->add_responsive_control(
           'ekit_review_card_margin', [
                'label' => esc_html__( 'Margin', 'elementsKit-lite' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'  => [
                    'top' => '0', 'right' => '0',
                    'bottom' => '2', 'left' => '0',
                    'unit' => 'em', 'isLinked' => false,
                ],
                'tablet_default'  => [
                    'top' => '0', 'right' => '0',
                    'bottom' => '1.5', 'left' => '0',
                    'unit' => 'em', 'isLinked' => false,
                ],
                'mobile_default'  => [
                    'top' => '0', 'right' => '0',
                    'bottom' => '1', 'left' => '0',
                    'unit' => 'em', 'isLinked' => false,
                ],
                'selectors'       => [
                    '{{WRAPPER}} .ekit-review-card-trustpilot' =>
                        'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
           ]
        );
        // ==============================
        // End Review card basic styles
        // ==============================


        // ==========================
        // Start Review card border
        // ==========================
        $this->add_control(
           'ekit_review_card_heading_border', [
              'label' => esc_html__( 'Border', 'elementsKit-lite' ),
              'type' => Controls_Manager::HEADING,
              'separator' => 'before'
           ]
        );

        // Review card border
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name'     => 'ekit_review_card_border_type',
                'label'    => esc_html__( 'Border Type', 'elementsKit-lite' ),
                'selector' => '{{WRAPPER}} .ekit-review-card-trustpilot, {{WRAPPER}} .ekit-review-wrapper-trustpilot .review-header-card',
            ]
        );
        // Review card border radius
        $this->add_control(
            'ekit_review_card_border_radius', [
                'label' => esc_html__( 'Border Radius', 'elementsKit-lite' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ekit-review-card-trustpilot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ekit-review-wrapper-trustpilot .review-header-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // ==========================
        // End Review card border
        // ==========================


        // ==========================
        // Start Comment section
        // ==========================
        $this->add_control(
           'ekit_review_card_comment', [
              'label' => esc_html__( 'Comment', 'elementsKit-lite' ),
              'type' => Controls_Manager::HEADING,
              'separator' => 'before'
           ]
        );

        $this->add_control(
            'ekit_review_card_comment_color', [
                'label' => __( 'Text Color', 'elementsKit-lite' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ekit-review-card-trustpilot .ekit-review-card--comment' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'ekit_review_card_comment_typography',
                'label' => __( 'Typography', 'elementsKit-lite' ),
                'selector' => '{{WRAPPER}} .ekit-review-card-trustpilot .ekit-review-card--comment',
            ]
        );

        // ekit_review_card_comment_padding
        $this->add_responsive_control(
            'ekit_review_card_comment_padding', [
                'label'      => esc_html__( 'Padding', 'elementsKit-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .ekit-review-card-trustpilot .ekit-review-card--comment' =>
                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'  => [
                    'top' => 1,  'right' => 1,
                    'bottom' => 1,  'left' => 0,
                    'unit' => 'em', 'isLinked' => false,
                ],
            ]
        );

        // ekit_review_card_comment_margin
        $this->add_responsive_control(
            'ekit_review_card_comment_margin', [
                'label'      => esc_html__( 'Margin', 'elementsKit-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'  => [
                    'top' => '0', 'right' => '0',
                    'bottom' => '0', 'left' => '0',
                    'unit' => 'em', 'isLinked' => false,
                ],
                'tablet_default'  => [
                    'top' => '0', 'right' => '0',
                    'bottom' => '0', 'left' => '0',
                    'unit' => 'em', 'isLinked' => false,
                ],
                'mobile_default'  => [
                    'top' => '0', 'right' => '0',
                    'bottom' => '0', 'left' => '0',
                    'unit' => 'em', 'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ekit-review-card-trustpilot .ekit-review-card--comment' =>
                        'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();
        // End Review card border

        // // ==============================
		// // Start review cards load more
		// // ==============================
        // $this->start_controls_section(
        //     'ekit_review_cards_load_more', [
        //         'label' => esc_html__( 'Load More', 'elementsKit-lite' ),
        //         'tab'   => Controls_Manager::TAB_STYLE,
        //     ]
        // );

        // // ekit_review_cards_load_more_padding
        // $this->add_responsive_control(
        //     'ekit_review_cards_load_more_padding', [
        //         'label'      => esc_html__( 'Padding', 'elementsKit-lite' ),
        //         'type'       => Controls_Manager::DIMENSIONS,
        //         'size_units' => [ 'px', '%', 'em' ],
        //         'selectors'  => [
        //             '{{WRAPPER}} .ekit-review-cards-trustpilot .ekit-review-cards-trustpilot--load-more' =>
        //                 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        //         ],
        //         'default'  => [
        //             'top' => '2',  'right' => '0',
        //             'bottom' => '0',  'left' => '0',
        //             'unit' => 'em', 'isLinked' => false,
        //         ],
        //     ]
        // );

        // $this->add_control(
        //     'ekit_review_cards_load_more_btn_heading', [
        //        'label' => esc_html__( 'Button', 'elementsKit-lite' ),
        //        'type' => Controls_Manager::HEADING,
        //        'separator' => 'before'
        //     ]
        // );

        // $this->start_controls_tabs( 'ekit_review_cards_load_more_btn_tabs');

        // // Start Normal Tab
        // $this->start_controls_tab(
        //     'ekit_review_cards_load_more_btn_tab_normal', [
        //         'label' => esc_html__( 'Normal', 'elementskit' ),
        //     ]
        // );

        // // ekit_review_cards_load_more_btn_txt_color_normal
        // $this->add_control(
        //     'ekit_review_cards_load_more_btn_txt_color_normal', [
        //         'label' => esc_html__( 'Text Color', 'elementskit' ),
        //         'type' => Controls_Manager::COLOR,
        //         'default' => '',
        //         'selectors' => [
        //             '{{WRAPPER}} .ekit-review-cards-trustpilot--load-more .btn' => 'color: {{VALUE}};',
        //         ],
        //     ]
        // );

        // // ekit_review_cards_load_more_btn_bg_color_normal
        // $this->add_group_control(
        //     Group_Control_Background::get_type(), [
        //         'name'      => 'ekit_review_cards_load_more_btn_bg_color_normal',
        //         'label'     => esc_html__( 'Background', 'elementsKit-lite' ),
        //         'types'     => [ 'classic', 'gradient' ],
        //         'selector'  => '{{WRAPPER}} .ekit-review-cards-trustpilot--load-more .btn'
        //     ]
        // );

        // $this->end_controls_tab();
        // // End Normal Tab

        // // Start Hover Tab
        // $this->start_controls_tab(
        //     'ekit_review_cards_load_more_btn_tab_hover', [
        //         'label' => esc_html__( 'Hover', 'elementskit' ),
        //     ]
        // );

        // // ekit_review_cards_load_more_btn_txt_color_hover
        // $this->add_control(
        //     'ekit_review_cards_load_more_btn_txt_color_hover', [
        //         'label' => esc_html__( 'Text Color', 'elementskit' ),
        //         'type' => Controls_Manager::COLOR,
        //         'default' => '',
        //         'selectors' => [
        //             '{{WRAPPER}} .ekit-review-cards-trustpilot--load-more .btn:hover' => 'color: {{VALUE}};',
        //         ],
        //     ]
        // );

        // // ekit_review_cards_load_more_btn_bg_color_hover
        // $this->add_group_control(
        //     Group_Control_Background::get_type(), [
        //         'name'      => 'ekit_review_cards_load_more_btn_bg_color_hover',
        //         'label'     => esc_html__( 'Background', 'elementsKit-lite' ),
        //         'types'     => [ 'classic', 'gradient' ],
        //         'selector'  => '{{WRAPPER}} .ekit-review-cards-trustpilot--load-more .btn:hover'
        //     ]
        // );

        // $this->end_controls_tab();
        // // End Normal Tab

        // $this->end_controls_tabs();


        // $this->end_controls_section();
        // // End review cards load more

    }

    protected function render( ) {
        echo '<div class="ekit-wid-con" >';
            $this->render_raw();
        echo '</div>';
	}

    public function render_raw() {

        $settings  = $this->get_settings_for_display();
        extract($settings);

		$show_overview  =    isset($ekit_review_overview_card)           && $ekit_review_overview_card           == 'yes';
        $badge          =    isset($ekit_review_card_thumbnail_badge)    && $ekit_review_card_thumbnail_badge    == 'yes';
        $border         =    isset($ekit_review_card_border_type_border) && $ekit_review_card_border_type_border;
        $format_comment =    $ekit_review_layout_type == 'grid' || $ekit_review_layout_type == 'slideshow';

        //$review_rating = 4;
        //$rating_type = ($review_rating == 1 ? 'bad' : ($review_rating <=4 ? 'average' : 'good'));

        // Start card classes
        $card_classes = 'ekit-review-card ekit-review-card-trustpilot';
        $card_classes .= " $ekit_review_card_style";
        // End card classes

        $BASE_URL =  Handler::get_url();
        $data = Handler::get_data();


        if( !empty($data) && $data->success):
            $overview = null;
            $reviews = $data->result;
        ?>

        <!-- Start Markup  -->
        <div class="ekit-review-wrapper ekit-review-wrapper-trustpilot">

            <!-- Start overview -->
            <?php if( !empty($data->overview) && $show_overview):
                require Handler::get_dir() . 'components/overview-card.php';
            endif ?>
            <!-- Start overview -->

            <div class="ekit-review-cards ekit-review-cards-trustpilot <?php echo "ekit-review-cards-" . $ekit_review_layout_type ?>">
                <?php if( $ekit_review_layout_type == 'slideshow'){

                    $data_attrs   =     isset($ekit_review_slideshow_slides_to_show)    ? "data-slidestoshow='$ekit_review_slideshow_slides_to_show' "      : "";
                    $data_attrs  .= 	isset($ekit_review_slideshow_slides_to_scroll)  ? "data-slidestoscroll= '$ekit_review_slideshow_slides_to_scroll' " : "";
                    $data_attrs  .= 	isset($ekit_review_slideshow_speed)             ? "data-speed= '$ekit_review_slideshow_speed' "                     : "";
                    $data_attrs  .= 	isset($ekit_review_slideshow_autoplay)          ? "data-autoplay= '$ekit_review_slideshow_autoplay' "               : "";
                    $data_attrs  .= 	isset($ekit_review_slideshow_show_arrow)        ? "data-showarrow= '$ekit_review_slideshow_show_arrow' "            : "";
                    $data_attrs  .= 	isset($ekit_review_slideshow_show_dot)          ? "data-showdot= '$ekit_review_slideshow_show_dot' "                : "";
                    $data_attrs  .= 	isset($ekit_review_slideshow_pause_on_hover)    ? "data-pauseonhover= '$ekit_review_slideshow_pause_on_hover' "     : "";

                    ?>

                    <div
                        class="ekit-review-slider-wrapper ekit-review-slider-wrapper-trustpilot"
                            <?php echo \ElementsKit\Utils::render($data_attrs); ?>
                        ><?php foreach ( $reviews as $item ) : ?>
                        <div><?php require Handler::get_dir() . 'components/review-card.php'; ?></div>
                    <?php endforeach ?>
                    </div>

                <?php } elseif($ekit_review_layout_type == 'grid') { ?>
                    <!-- Start Grid -->
                    <div class="row">
                        <?php foreach ( $reviews as $item ) : ?>
                            <div class='<?php echo esc_attr( $ekit_review_column ); ?>'>
                                <?php require Handler::get_dir() . 'components/review-card.php'; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- End Grid -->
                <?php } elseif($ekit_review_layout_type == 'masonry') { ?>
                    <!-- Start Masonry -->
                    <div class="masonry <?php echo "column-count-$ekit_review_masonry_column_count" ?>">
                        <?php foreach ( $reviews as $item ) :
                            require Handler::get_dir() . 'components/review-card.php';
                        endforeach; ?>
                    </div>
                    <!-- End Masonry -->
                <?php } else {
                    foreach ( $reviews as $item ) :
                        require Handler::get_dir() . 'components/review-card.php';
                    endforeach;
                } ?>

                <?php /* if($ekit_review_layout_type != 'slideshow'): */ ?>
                    <!-- <div class="ekit-review-cards-trustpilot--load-more">
                        <a href="#" class="btn">Load More</a>
                    </div> -->
                <?php /* endif */ ?>
            </div>
        </div>
        <!-- End Markup  -->

        <?php else: ?>
            <div>
                <h1><?php echo esc_html__('Data fetch error', 'elementsKit-lite')?></h1>
            </div>
        <?php endif;

    }
}