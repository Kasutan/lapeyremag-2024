<?php


/**
 * Footer above
 *
 */

//add_action('tha_footer_before','kasutan_footer_before');
function kasutan_footer_before() {
	if(!function_exists('get_field')) {
		return;
	}
}

/**
 * Footer top
 *
 */

add_action( 'tha_footer_top', 'kasutan_main_footer' );
function kasutan_main_footer() {


	echo '<div class="main-footer">';

		echo '<code>Footer à intégrer</code>';

	echo '</div>';
}


/**
* Footer bottom
*
*/
//add_action( 'tha_footer_bottom', 'kasutan_copyright' );
function kasutan_copyright() {

	
}