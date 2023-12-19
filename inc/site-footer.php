<?php


/**
 * Formulaire newsletter
 *
 */

add_action('tha_footer_before','kasutan_footer_before');
function kasutan_footer_before() {
	if(!function_exists('get_field')) {
		return;
	}
	$newsletter=get_field('lapeyremag_newsletter','option');
	$image_id=wp_kses_post( $newsletter['image'] );
	$image_id_mobile=wp_kses_post( $newsletter['image_mobile'] );

	
		$titre=wp_kses_post( $newsletter['titre'] );
		$shortcode=wp_kses_post($newsletter['texte']) ;

	

	printf('<section class="newsletter">');
		echo '<div class="image desktop">';
			echo wp_get_attachment_image( $image_id, 'banniere');
		echo '</div>';
		echo '<div class="image mobile">';
			echo wp_get_attachment_image( $image_id_mobile, 'large');
		echo '</div>';

		echo '<div class="overlay"></div>';
		
		printf('<span class="titre-section">%s</span>',$titre);
		
		echo '<div class="form-wrap">';
			echo do_shortcode($shortcode);
		echo '</div>';

	echo '</section>';
}

/**
 * Menus + logos Footer + sélecteur de langues
 *
 */

add_action( 'tha_footer_top', 'kasutan_main_footer' );
function kasutan_main_footer() {


	echo '<div class="main-footer">';

		if(function_exists('kasutan_affiche_logos')) {
			kasutan_affiche_logos("logos-footer");
		}


	echo '<div class="colonnes-footer">';
	
	for($i=1;$i<=3;$i++) {
		if( has_nav_menu( 'footer-'.$i ) ) {
			printf('<div class="col-%s col">',$i);
			wp_nav_menu( array( 'theme_location' => 'footer-'.$i, 'menu_id' => 'footer-'.$i, 'container_class' => 'nav-footer' ) );

			if($i===3 &&  has_nav_menu( 'footer-social')) {
				wp_nav_menu( array( 'theme_location' => 'footer-social', 'menu_id' => 'footer-social', 'container_class' => 'nav-social' ) );
			}
			echo '</div>';
		}
	}

	echo '</div>';
	
	echo '</div>';
}


/**
* Copyright et liens légaux
*
*/
add_action( 'tha_footer_bottom', 'kasutan_copyright' );
function kasutan_copyright() {

	$by = 'Site internet réalisé par 40 degrés sur la banquise';

	
	echo '<div class="copyright">';
		printf('<span class="titre">%s %s</span>',get_option('blogname'), date('Y'));
		echo '<span class="sep">-</span>';
		if(has_nav_menu('footer-legal')) {
			wp_nav_menu( array( 'theme_location' => 'footer-legal', 'menu_id' => 'footer-legal', 'container_class' => 'nav-footer' ) );
		}
		printf('<a class="agence" href="https://banquise.com/" rel="noopener noreferrer" target="_blank">%s</a>',$by);
	echo '</div>';
}