<?php
/**
 * Single Post
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

//Body class
function kasutan_single_body_class( $classes ) {
	if(!has_post_thumbnail()) {
		$classes[] = 'no-thumbnail';
	}
	return $classes;
}
add_filter( 'body_class', 'kasutan_single_body_class' );


// Breadcrumbs 
add_action( 'tha_entry_top', 'kasutan_fil_ariane', 5 );



// Image bannière 
add_action( 'tha_entry_top', 'kasutan_single_banniere', 10 );


//Boutons de partage, temps de lecture, résumé et sommaire
add_action('tha_entry_content_before', 'kasutan_single_entry_content_before');
function kasutan_single_entry_content_before() {
	if(get_post_type() !== 'post') {
		return;
	}

	//TODO parser l'article pour trouver la première image et tous les titres - ou JS ?

	echo '<div class="single-metas">';

		if(function_exists('kasutan_boutons_partage')) {
			kasutan_boutons_partage($avec_titre=false,$media=""); 
		}

		if(function_exists('kasutan_affiche_temps')) {
			kasutan_affiche_temps();
		}
	echo '</div>';

	$intro="";
	if(function_exists('get_field')) {
		$intro=wp_kses_post(get_field('lapeyre_intro'));
	}

	if($intro) printf('<div class="intro">%s</div>',$intro);

	printf('<div class="flex-center has-jaune-background-color sommaire" style="margin-bottom:4rem">TODO Sommaire (flottant en mobile) </div>');

}

add_action('tha_entry_content_after','kasutan_single_entry_content_after');
function kasutan_single_entry_content_after(){
	if(function_exists('kasutan_boutons_partage')) {
		kasutan_boutons_partage($avec_titre=true,$media=""); 
	}
}


add_action('tha_entry_bottom','kasutan_single_entry_bottom');
function kasutan_single_entry_bottom(){
	
	printf('<div class="flex-center has-bleu-background-color">TODO Ces articles pourraient aussi vous intéresser</div>');
	
	printf('<div class="flex-center has-beige-background-color">TODO Prêt à lancer votre projet ? (réutilisable)</div>');

	printf('<div class="flex-center has-jaune-background-color">TODO Voulez-vous découvrir d\'autres articles ? (réutilisable)</div>');

}


// Build the page
require get_template_directory() . '/index.php';
