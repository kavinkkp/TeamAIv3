<?php
 $business_trust_title = get_theme_mod('hero_title', '');
 $business_trust_description = get_theme_mod('hero_description', '');
 $business_trust_button_text = get_theme_mod('hero_button', '');
 $business_trust_link = get_theme_mod('hero_link', '');
 if($business_trust_title != "" || $business_trust_description != ""){
?>
<div id="header-hero-section">
	 <div class="hero-callout">
	 
		<?php if($business_trust_title !=''): ?>
		<h2 class="callout-title"><?php echo esc_html( $business_trust_title  ); ?></h2>
		<?php endif; ?>
		
		<?php if($business_trust_description!=''): ?>
		<div class="callout-section-desc "><?php echo esc_html( $business_trust_description  ); ?></div>
		<?php endif; ?> 
			
		<?php if($business_trust_link !=''): ?>
		<a href="<?php echo esc_url( $business_trust_link ); ?>" tabindex="0"><span class="call-to-action" ><?php echo esc_html($business_trust_button_text); ?></span></a>
		<?php endif; ?>
		
    </div> 
</div>
<?php }