<?php


/*************************
 * Actions sur le footer
 **************************/

add_action( 'tha_footer_top', 'kasutan_footer_icones',10 );
add_action( 'tha_footer_top', 'kasutan_footer_social',20 );
add_action( 'tha_footer_top', 'kasutan_footer_sitemap',30 );
add_action( 'tha_footer_top', 'kasutan_footer_confidentialite',40 );
add_action( 'tha_footer_top', 'kasutan_footer_paiement',50 );
add_action( 'tha_footer_top', 'kasutan_footer_liens',60 );


/*************************
 * Icones avantages
 **************************/
function kasutan_footer_icones() {
	if(!function_exists('have_rows') || !have_rows('lapeyre_footer_icones','options')) {
		return;
	}
	echo '<ul class="avantages">';
		while(have_rows('lapeyre_footer_icones','options')) : the_row();
			$image=esc_attr(get_sub_field('image'));
			$titre=wp_kses_post(get_sub_field('titre'));
			$sous_titre=wp_kses_post(get_sub_field('sous-titre'));

			printf('<li class="avantage">%s <strong>%s</strong> <span>%s</span></li>',wp_get_attachment_image($image),$titre,$sous_titre);

		endwhile;
	echo '</ul>';
}