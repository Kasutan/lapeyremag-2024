<?php
/**
 * Archive partial - search results
 *
**/

//simplification de partials/archive.php

$post_type=get_post_type();

if($post_type==="post") {
	$is_post=true;
	$infos_cat=array('nom'=>'Autres','couleur'=>'rouge');

	if(function_exists('kasutan_get_cat_et_couleur')) {
		$infos_cat=kasutan_get_cat_et_couleur();
		$couleur=$infos_cat['couleur'];
	}
} else {
	$is_post=false;

	//couleur aléatoire
	$couleurs=['rouge','bleu','vert','orange'];
	$index=rand(0,3);
	$couleur=$couleurs[$index];

}

printf('<li class="vignette %s">',$post_type);
$url=esc_url( get_permalink( ) );

	printf('<a href="%s" class="lien">',$url);

		if(function_exists('kasutan_vignette_image')) {
			kasutan_vignette_image();
		}
		

		printf('<div class="texte has-%s-background-color">',$couleur);

		
			echo '<div class="titre-wrap">';
				printf('<h2 class="titre">%s</h2>',get_the_title());
				if($is_post) {
					printf('<p class="date">%s</p>', get_the_date("d.m.Y"));
				}
			echo '</div>';

			$extrait=wpautop(get_the_excerpt());
			printf('<div class="extrait">%s</div>',$extrait);
		
		echo '</div>';

	echo '</a>';

	if($is_post) {
		printf('<a href="%s" class="cat"><div class="fond has-%s-background-color"></div><span>%s</span></a>',$infos_cat['url'],$infos_cat['couleur'],$infos_cat['nom']);
	}

echo '</li>';
