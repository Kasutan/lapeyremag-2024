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
			if($sous_titre) $sous_titre=wp_kses_post(get_sub_field('sous-titre'));

			printf('<li class="avantage">%s <strong>%s</strong> <span>%s</span></li>',wp_get_attachment_image($image),$titre,$sous_titre);

		endwhile;
	echo '</ul>';
}

/*************************
 * Liens vers les réseaux sociaux
 **************************/
function kasutan_footer_social() {
	if(!function_exists('get_field') || !function_exists('kasutan_picto')) {
		return;
	}
	$social=get_field('lapeyre_footer_social','option');
	if(empty($social)) {
		return;
	}

	echo '<div class="social">';
		if($social['titre']) printf('<p class="titre">%s</p>',wp_kses_post($social['titre']));
		if($social['sous-titre']) printf('<p class="sous-titre">%s</p>',wp_kses_post($social['sous-titre']));

		echo '<nav class="reseaux">';
		$reseaux=['facebook','pinterest','instagram','youtube'];
		foreach($reseaux as $reseau) {
			if($social[$reseau]) printf('<a href="%s" class="%s" title="Suivez-nous sur %s" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_url($social[$reseau]),
				$reseau,
				ucfirst($reseau),
				kasutan_picto(array('icon'=>$reseau))
			);
		}
		echo '</nav>';

	echo '</div>';
}