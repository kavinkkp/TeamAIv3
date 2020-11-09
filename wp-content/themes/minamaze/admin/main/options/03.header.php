<?php
/**
 * Social media functions.
 *
 * @package ThinkUpThemes
 */

/* ----------------------------------------------------------------------------------
	HEADER STYLE
---------------------------------------------------------------------------------- */

function thinkup_input_headerstyle($classes) {

	$classes[] = 'header-style1';
	return $classes;
}
add_action( 'body_class', 'thinkup_input_headerstyle');


/* ----------------------------------------------------------------------------------
	SEARCH - DISABLE SEARCH
---------------------------------------------------------------------------------- */

function thinkup_input_headersearch() {

// Get theme options values.
$thinkup_header_searchswitch = thinkup_var ( 'thinkup_header_searchswitch' );

	if ( $thinkup_header_searchswitch == '1' ) {
		echo '<div id="pre-header-search">',
		     get_search_form(),
		     '</div>';
	}
}


/* ----------------------------------------------------------------------------------
	SOCIAL MEDIA - DISPLAY MESSAGE
---------------------------------------------------------------------------------- */

/* Message Settings */
function thinkup_input_socialmessage(){

// Get theme options values.
$thinkup_header_socialmessage  = thinkup_var ( 'thinkup_header_socialmessage' );
$thinkup_header_facebookswitch = thinkup_var ( 'thinkup_header_facebookswitch' );
$thinkup_header_twitterswitch  = thinkup_var ( 'thinkup_header_twitterswitch' );
$thinkup_header_googleswitch   = thinkup_var ( 'thinkup_header_googleswitch' );
$thinkup_header_linkedinswitch = thinkup_var ( 'thinkup_header_linkedinswitch' );
$thinkup_header_flickrswitch   = thinkup_var ( 'thinkup_header_flickrswitch' );
$thinkup_header_youtubeswitch  = thinkup_var ( 'thinkup_header_youtubeswitch' );
$thinkup_header_rssswitch      = thinkup_var ( 'thinkup_header_rssswitch' );
$thinkup_header_diggswitch     = thinkup_var ( 'thinkup_header_diggswitch' );

	if ( empty( $thinkup_header_facebookswitch ) and empty( $thinkup_header_twitterswitch ) and empty( $thinkup_header_googleswitch ) and empty( $thinkup_header_linkedinswitch ) and empty( $thinkup_header_flickrswitch ) and empty( $thinkup_header_youtubeswitch ) and empty( $thinkup_header_rssswitch ) and empty( $thinkup_header_diggswitch ) ) {	
		return '';
	} else if ( ! empty( $thinkup_header_socialmessage ) ) {	
		return esc_html( $thinkup_header_socialmessage );
	} else if ( empty( $thinkup_header_socialmessage ) ) {
		return '';
	}
}


/* ----------------------------------------------------------------------------------
	SOCIAL MEDIA - CUSTOM ICONS
---------------------------------------------------------------------------------- */

/* Facebook - Custom Icon */
function thinkup_input_facebookicon(){

// Get theme options values.
$thinkup_header_facebookiconswitch = thinkup_var ( 'thinkup_header_facebookiconswitch' );
$thinkup_header_facebookcustomicon = thinkup_var ( 'thinkup_header_facebookcustomicon', 'url' );

	$output = NULL;

	if ( $thinkup_header_facebookiconswitch == '1' and ! empty( $thinkup_header_facebookcustomicon ) ) {
		$output .= '#pre-header-social li.facebook a,';
		$output .= '#pre-header-social li.facebook a:hover {';
		$output .= 'background: url("' . esc_url( $thinkup_header_facebookcustomicon ) . '") no-repeat center;';
		$output .= 'background-size: 25px;';
		$output .= '-webkit-border-radius: 0;';
		$output .= '-moz-border-radius: 0;';
		$output .= '-o-border-radius: 0;';
		$output .= 'border-radius: 0;';
		$output .= '}' . "\n";
		$output .= '#pre-header-social li.facebook i {';
		$output .= 'display: none;';
		$output .= '}' . "\n";
	}
	return $output;
}

/* Twitter - Custom Icon */
function thinkup_input_twittericon(){

// Get theme options values.
$thinkup_header_twittericonswitch = thinkup_var ( 'thinkup_header_twittericonswitch' );
$thinkup_header_twittercustomicon = thinkup_var ( 'thinkup_header_twittercustomicon', 'url' );

	$output = NULL;

	if ( $thinkup_header_twittericonswitch == '1' and ! empty( $thinkup_header_twittercustomicon ) ) {
		$output .= '#pre-header-social li.twitter a,';
		$output .= '#pre-header-social li.twitter a:hover {';
		$output .= 'background: url("' . esc_url( $thinkup_header_twittercustomicon ) . '") no-repeat center;';
		$output .= 'background-size: 25px;';
		$output .= '-webkit-border-radius: 0;';
		$output .= '-moz-border-radius: 0;';
		$output .= '-o-border-radius: 0;';
		$output .= 'border-radius: 0;';
		$output .= '}' . "\n";
		$output .= '#pre-header-social li.twitter i {';
		$output .= 'display: none;';
		$output .= '}' . "\n";
	}
	return $output;
}

/* Google+ - Custom Icon */
function thinkup_input_googleicon(){

// Get theme options values.
$thinkup_header_googleiconswitch = thinkup_var ( 'thinkup_header_googleiconswitch' );
$thinkup_header_googlecustomicon = thinkup_var ( 'thinkup_header_googlecustomicon', 'url' );

	$output = NULL;

	if ( $thinkup_header_googleiconswitch == '1' and ! empty( $thinkup_header_googlecustomicon ) ) {
		$output .= '#pre-header-social li.google-plus a,';
		$output .= '#pre-header-social li.google-plus a:hover {';
		$output .= 'background: url("' . esc_url( $thinkup_header_googlecustomicon ) . '") no-repeat center;';
		$output .= 'background-size: 25px;';
		$output .= '-webkit-border-radius: 0;';
		$output .= '-moz-border-radius: 0;';
		$output .= '-o-border-radius: 0;';
		$output .= 'border-radius: 0;';
		$output .= '}' . "\n";
		$output .= '#pre-header-social li.google-plus i {';
		$output .= 'display: none;';
		$output .= '}' . "\n";
	}
	return $output;	
}

/* LinkedIn - Custom Icon */
function thinkup_input_linkedinicon(){

// Get theme options values.
$thinkup_header_linkediniconswitch = thinkup_var ( 'thinkup_header_linkediniconswitch' );
$thinkup_header_linkedincustomicon = thinkup_var ( 'thinkup_header_linkedincustomicon', 'url' );

	$output = NULL;

	if ( $thinkup_header_linkediniconswitch == '1' and ! empty( $thinkup_header_linkedincustomicon ) ) {
		$output .= '#pre-header-social li.linkedin a,';
		$output .= '#pre-header-social li.linkedin a:hover {';
		$output .= 'background: url("' . esc_url( $thinkup_header_linkedincustomicon ) . '") no-repeat center;';
		$output .= 'background-size: 25px;';
		$output .= '-webkit-border-radius: 0;';
		$output .= '-moz-border-radius: 0;';
		$output .= '-o-border-radius: 0;';
		$output .= 'border-radius: 0;';
		$output .= '}' . "\n";
		$output .= '#pre-header-social li.linkedin i {';
		$output .= 'display: none;';
		$output .= '}' . "\n";
	}
	return $output;
}

/* Flickr - Custom Icon */
function thinkup_input_flickricon(){

// Get theme options values.
$thinkup_header_flickriconswitch = thinkup_var ( 'thinkup_header_flickriconswitch' );
$thinkup_header_flickrcustomicon = thinkup_var ( 'thinkup_header_flickrcustomicon', 'url' );

	$output = NULL;

	if ( $thinkup_header_flickriconswitch == '1' and ! empty( $thinkup_header_flickrcustomicon ) ) {
		$output .= '#pre-header-social li.flickr a,';
		$output .= '#pre-header-social li.flickr a:hover {';
		$output .= 'background: url("' . esc_url( $thinkup_header_flickrcustomicon ) . '") no-repeat center;';
		$output .= 'background-size: 25px;';
		$output .= '-webkit-border-radius: 0;';
		$output .= '-moz-border-radius: 0;';
		$output .= '-o-border-radius: 0;';
		$output .= 'border-radius: 0;';
		$output .= '}' . "\n";
		$output .= '#pre-header-social li.flickr i {';
		$output .= 'display: none;';
		$output .= '}' . "\n";
	}
	return $output;
}

/* YouTube - Custom Icon */
function thinkup_input_youtubeicon(){

// Get theme options values.
$thinkup_header_youtubeiconswitch = thinkup_var ( 'thinkup_header_youtubeiconswitch' );
$thinkup_header_youtubecustomicon = thinkup_var ( 'thinkup_header_youtubecustomicon', 'url' );

	$output = NULL;

	if ( $thinkup_header_youtubeiconswitch == '1' and ! empty( $thinkup_header_youtubecustomicon ) ) {
		$output .= '#pre-header-social li.youtube a,';
		$output .= '#pre-header-social li.youtube a:hover {';
		$output .= 'background: url("' . esc_url( $thinkup_header_youtubecustomicon ) . '") no-repeat center;';
		$output .= 'background-size: 25px;';
		$output .= '-webkit-border-radius: 0;';
		$output .= '-moz-border-radius: 0;';
		$output .= '-o-border-radius: 0;';
		$output .= 'border-radius: 0;';
		$output .= '}' . "\n";
		$output .= '#pre-header-social li.youtube i {';
		$output .= 'display: none;';
		$output .= '}' . "\n";
	}
	return $output;
}

/* RSS - Custom Icon */
function thinkup_input_rssicon(){

// Get theme options values.
$thinkup_header_rssiconswitch = thinkup_var ( 'thinkup_header_rssiconswitch' );
$thinkup_header_rsscustomicon = thinkup_var ( 'thinkup_header_rsscustomicon', 'url' );

	$output = NULL;

	if ( $thinkup_header_rssiconswitch == '1' and ! empty( $thinkup_header_rsscustomicon ) ) {
		$output .= '#pre-header-social li.rss a,';
		$output .= '#pre-header-social li.rss a:hover {';
		$output .= 'background: url("' . esc_url( $thinkup_header_rsscustomicon ) . '") no-repeat center;';
		$output .= 'background-size: 25px;';
		$output .= '-webkit-border-radius: 0;';
		$output .= '-moz-border-radius: 0;';
		$output .= '-o-border-radius: 0;';
		$output .= 'border-radius: 0;';
		$output .= '}' . "\n";
		$output .= '#pre-header-social li.rss i {';
		$output .= 'display: none;';
		$output .= '}' . "\n";
	}
	return $output;
}

/* Input Custom Social Media Icons */
function thinkup_input_socialicon(){

	$output = NULL;

	$output .= thinkup_input_facebookicon();
	$output .= thinkup_input_twittericon();
	$output .= thinkup_input_googleicon();
	$output .= thinkup_input_linkedinicon();
	$output .= thinkup_input_flickricon();
	$output .= thinkup_input_youtubeicon();
	$output .= thinkup_input_rssicon();

	if ( ! empty( $output ) ) {
		echo    '<style type="text/css">' . "\n" . $output . '</style>';
	}
}
add_action( 'wp_head', 'thinkup_input_socialicon', 13 );


/* ----------------------------------------------------------------------------------
	SOCIAL MEDIA - OUTPUT SOCIAL MEDIA SELECTIONS
---------------------------------------------------------------------------------- */

function thinkup_input_socialmedia() {

// Get theme options values.
$thinkup_header_socialswitch   = thinkup_var ( 'thinkup_header_socialswitch' );
$thinkup_header_socialmessage  = thinkup_var ( 'thinkup_header_socialmessage' );
$thinkup_header_facebookswitch = thinkup_var ( 'thinkup_header_facebookswitch' );
$thinkup_header_facebooklink   = thinkup_var ( 'thinkup_header_facebooklink' );
$thinkup_header_twitterswitch  = thinkup_var ( 'thinkup_header_twitterswitch' );
$thinkup_header_twitterlink    = thinkup_var ( 'thinkup_header_twitterlink' );
$thinkup_header_googleswitch   = thinkup_var ( 'thinkup_header_googleswitch' );
$thinkup_header_googlelink     = thinkup_var ( 'thinkup_header_googlelink' );
$thinkup_header_linkedinswitch = thinkup_var ( 'thinkup_header_linkedinswitch' );
$thinkup_header_linkedinlink   = thinkup_var ( 'thinkup_header_linkedinlink' );
$thinkup_header_flickrswitch   = thinkup_var ( 'thinkup_header_flickrswitch' );
$thinkup_header_flickrlink     = thinkup_var ( 'thinkup_header_flickrlink' );
$thinkup_header_youtubeswitch  = thinkup_var ( 'thinkup_header_youtubeswitch' );
$thinkup_header_youtubelink    = thinkup_var ( 'thinkup_header_youtubelink' );
$thinkup_header_rssswitch      = thinkup_var ( 'thinkup_header_rssswitch' );
$thinkup_header_rsslink        = thinkup_var ( 'thinkup_header_rsslink' );

	if ( $thinkup_header_socialswitch == '1' ) {

		echo '<div id="pre-header-social"><ul>';

			if ( ! empty ( $thinkup_header_socialmessage ) ) {
				echo '<li class="social message">' . thinkup_input_socialmessage() . '</li>';
			}

			/* Facebook settings */
			if ( $thinkup_header_facebookswitch == '1' ) {
				echo '<li class="social facebook"><a href="' . esc_url( $thinkup_header_facebooklink ) . '" data-tip="bottom" data-original-title="' . __( 'Facebook', 'minamaze' ) . '" target="_blank">',
					 '<i class="fa fa-facebook"></i>',
					 '</a></li>';
			}

			/* Twitter settings */
			if ( $thinkup_header_twitterswitch == '1' ) {
				echo '<li class="social twitter"><a href="' . esc_url( $thinkup_header_twitterlink ) . '" data-tip="bottom" data-original-title="' . __( 'Twitter', 'minamaze' ) . '" target="_blank">',
					 '<i class="fa fa-twitter"></i>',
					 '</a></li>';
			}

			/* Google+ settings */
			if ( $thinkup_header_googleswitch == '1' ) {
				echo '<li class="social google"><a href="' . esc_url( $thinkup_header_googlelink ) . '" data-tip="bottom" data-original-title="' . __( 'Google+', 'minamaze' ) . '" target="_blank">',
					 '<i class="fa fa-google-plus"></i>',
					 '</a></li>';
			}

			/* LinkedIn settings */
			if ( $thinkup_header_linkedinswitch == '1' ) {
				echo '<li class="social linkedin"><a href="' . esc_url( $thinkup_header_linkedinlink ) . '" data-tip="bottom" data-original-title="' . __( 'LinkedIn', 'minamaze' ) . '" target="_blank">',
					 '<i class="fa fa-linkedin"></i>',
					 '</a></li>';
			}

			/* Flickr settings */
			if ( $thinkup_header_flickrswitch == '1' ) {
				echo '<li class="social flickr"><a href="' . esc_url( $thinkup_header_flickrlink ) . '" data-tip="bottom" data-original-title="' . __( 'Flickr', 'minamaze' ) . '" target="_blank">',
					 '<i class="fa fa-flickr"></i>',
					 '</a></li>';
			}

			/* YouTube settings */
			if ( $thinkup_header_youtubeswitch == '1' ) {
				echo '<li class="social youtube"><a href="' . esc_url( $thinkup_header_youtubelink ) . '" data-tip="bottom" data-original-title="' . __( 'YouTube', 'minamaze' ) . '" target="_blank">',
					 '<i class="fa fa-youtube-play"></i>',
					 '</a></li>';
			}

			/* RSS settings */
			if ( $thinkup_header_rssswitch == '1' ) {
				echo '<li class="social rss"><a href="' . esc_url( $thinkup_header_rsslink ) . '" data-tip="bottom" data-original-title="' . __( 'RSS', 'minamaze' ) . '" target="_blank">',
					 '<i class="fa fa-rss"></i>',
					 '</a></li>';
			}

		echo	'</ul></div>';

	}
}


?>