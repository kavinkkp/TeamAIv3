<?php 
$banner_image = get_theme_mod('banner_image', '');
if($banner_image !='') { 

?>
<section id="top-banner">
	<div class="center-text">
		<?php 
			echo '<a href="'.esc_url(get_theme_mod('banner_link', '#')).'" ><img src="'.esc_url($banner_image).'" /></a>';	
		?>
	</div>
</section>

<?php
} 

