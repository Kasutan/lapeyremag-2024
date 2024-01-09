<?php
/**
 * Archive partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

//On récupère d'éventuelles infos passées en $args de get_template_part
$balise_vignette='li';
$balise_titre='h2';
$featured=false;
$index=1;
if(!empty($args)) {
	if(array_key_exists('tag',$args)) {
		$balise_vignette=$args['tag'];
	}
	if(array_key_exists('tag_titre',$args)) {
		$balise_titre=$args['tag_titre'];
	}
	if(array_key_exists('featured',$args)) {
		$featured=$args['featured'];
	}
	if(array_key_exists('index',$args)) {
		$index=$args['index'];
	}
}
if($balise_vignette==='div') {
	//on est dans le carousel
	$balise_titre='h3';
}

//Préparer les infos sur le post
$infos=array('parent_name'=>false,'child_name'=>false,'couleur'=>false);
$url=esc_url( get_permalink( ) );
$post_id=get_the_ID();
if(function_exists('kasutan_get_infos_cats')) {
	$infos=kasutan_get_infos_cats($post_id,true);
}


$class="vignette";
$taille="medium_large";
if($featured) {
	$class.=" featured";
	$taille='large';
}

//Préparer les tags univers (catgéorie parente) et type d'article (catégorie enfant)
$tag_univers=$tag_type=false;

if($infos['parent_name']) {
	if($infos['couleur']) {
		$couleur=$infos['couleur'];
	} else {
		$couleur='rose';
	}

	$tag_univers=sprintf('<span class="tag univers has-%s-background-color">%s</span>',
		$couleur,
		$infos['parent_name']
	);
}

if($infos['child_name']) {
	$tag_type=sprintf('<span class="tag type">%s</span>',$infos['child_name']);
}


printf('<%s class="%s" data-index="%s">',$balise_vignette,$class,$index);

	
	printf('<a href="%s" class="lien">',$url);

		if(function_exists('kasutan_vignette_image')) {
			kasutan_vignette_image($taille);
		}
		echo '<div class="has-color-bleu has-bleu-color">';
		echo '</div>';
		printf('<div class="texte">');

			if($tag_univers || $tag_type) {
				echo '<div class="tags">';
					if($tag_univers) echo $tag_univers;
					if($tag_type) echo $tag_type;
				echo '</div>';
			}
		
			printf('<%s class="titre">%s</%s>',$balise_titre,get_the_title(),$balise_titre);

			if(function_exists('kasutan_affiche_temps')) {
				kasutan_affiche_temps();
			}
		echo '</div>';
	
	echo '</a>';

printf('</%s>',$balise_vignette);

