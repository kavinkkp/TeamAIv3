<?php 
$booking_shortcode = get_theme_mod('header_shortcode', '');
if($booking_shortcode !='') { 

?>
<section id="booking-shortcode">
	<div class="center-text">
		<?php 
			echo do_shortcode( wp_kses_post($booking_shortcode) );	
		?>
	</div>
</section>

<?php
} 

