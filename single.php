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


//Boutons de partage, temps de lecture, résumé et  sommaire, bloc "à savoir"
add_action('tha_entry_content_before', 'kasutan_single_entry_content_before');
function kasutan_single_entry_content_before() {
	if(get_post_type() !== 'post') {
		return;
	}


	echo '<div class="single-metas">';

		if(function_exists('kasutan_boutons_partage')) {
			kasutan_boutons_partage($avec_titre=false); 
		}

		if(function_exists('kasutan_affiche_temps')) {
			kasutan_affiche_temps();
		}
	echo '</div>';

	$intro=$savoir=$titre_savoir="";
	if(function_exists('get_field')) {
		$intro=wp_kses_post(get_field('lapeyre_intro'));
		$savoir=get_field('lapeyre_savoir');//champ de type groupe
		$titre_savoir=wp_kses_post(get_field('lapeyre_savoir_titre','option'));
	}

	if($intro) printf('<div class="intro">%s</div>',$intro);

	if(function_exists('kasutan_picto')) {
		printf('<div class="sommaire" id="sommaire-single"><button id="toggle-sommaire" class="toggle" aria-expanded="true" aria-controls="liens-sommaire"><span class="menu">%s</span> <span>Sommaire</span> <span class="chevron">%s</span></button><nav><ol id="liens-sommaire" class="liens"></ol></nav></div>',
			kasutan_picto(array('icon'=>'menu')),
			kasutan_picto(array('icon'=>'chevron-bas'))
		);
	}

	//Bloc "à savoir avant de se lancer"
	if(!empty($savoir) && $savoir['afficher']) {
		
		echo '<div class="savoir">';
			if($titre_savoir) printf('<p class="titre">%s</p>',$titre_savoir);
			echo '<ul class="infos">';

				if($savoir['niveau'] && function_exists('kasutan_picto')) {
					printf('<li class="niveau-%s">Difficulté&nbsp;: %s %s %s <strong>%s</strong>',
						$savoir['niveau']['value'],
						kasutan_picto(array('icon'=>'star-wired')),
						kasutan_picto(array('icon'=>'star-wired')),
						kasutan_picto(array('icon'=>'star-wired')),
						$savoir['niveau']['label']
					);
				}

				if($savoir['heures']) {
					$label="heure";
					if($savoir['heures']>1) {
						$label="heures";
					}
					printf('<li>Temps estimé&nbsp;: <strong>%s %s</strong>',esc_attr($savoir['heures']),$label);
				} 

				if($savoir['etapes']) printf('<li>Étapes&nbsp;: <strong>%s</strong>',esc_attr($savoir['etapes']));

			echo "</ul>";
		echo '</div>';
	}
	
	
}

add_action('tha_entry_content_after','kasutan_single_entry_content_after');
function kasutan_single_entry_content_after(){
	if(function_exists('kasutan_boutons_partage')) {
		kasutan_boutons_partage($avec_titre=true); 
	}
}


add_action('tha_entry_bottom','kasutan_single_entry_bottom');
function kasutan_single_entry_bottom(){
	//Récupérer term_id de l'univers de cet article
	$infos=array();
	$parent_id=false;
	$parent_name=false;
	$child_name=false;
	if(function_exists('kasutan_get_infos_cats')) {
		$infos=kasutan_get_infos_cats();
	}
	if(!empty($infos)) {
		if (isset($infos['parent_id'])) $parent_id=$infos['parent_id'];
		if(isset($infos['parent_name'])) $parent_name=$infos['parent_name'];
		if(isset($infos['child_name'])) $child_name=$infos['child_name'];
	}


	if(function_exists('kasutan_affiche_related')) {
		kasutan_affiche_related($parent_id);
		//Si besoin des articles de même univers ET de même type : récupérer child_id et le passer en paramètre à la place de parent_id
	}
	
	//Section projet affichée uniquement pour les articles de type guide pratique
	if($child_name && strpos(strtolower($child_name),'guide')!==false && $parent_id && function_exists('kasutan_affiche_projet')) {
		kasutan_affiche_projet($parent_id,$parent_name);
	}

	if(function_exists('kasutan_affiche_nav_univers')) {
		kasutan_affiche_nav_univers($parent_id);
	}

}


// Build the page
require get_template_directory() . '/index.php';
