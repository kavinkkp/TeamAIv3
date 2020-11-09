<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package ThinkUpThemes
 */

get_header(); ?>

	<div class="entry-content title-404">

		<h2><?php _e( 'Oops!', 'minamaze' ); ?></h2>
		<p><?php _e( 'Sorry, we could not find the page you are looking for.', 'minamaze' ); ?>
		<br/>
		<?php _e( 'Please try using the search function.', 'minamaze' ); ?>
		</p>

		<?php get_search_form(); ?>

	</div>

<?php get_footer(); ?>