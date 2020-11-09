<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Xolo
 */
get_header();
 if ( function_exists( 'xolo_breadcrumbs_style' ) ) : xolo_breadcrumbs_style(); endif;  

get_template_part('template-parts/edd/content','single'); 

get_footer();