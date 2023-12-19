<?php
/**
 * Archive partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

//On récupère une éventuelle info sur le tag html passée en $args de get_template_part
$tag='li';
$tag_titre='h2';
if(!empty($args) && array_key_exists('tag',$args)) {
	$tag=$args['tag'];
}

if($tag==='div') {
	//on est dans le carousel
	$tag_titre='h3';
}

printf('<%s class="vignette">',$tag);

	$url=esc_url( get_permalink( ) );

	printf('<a href="%s" class="lien">',$url);

		if(function_exists('kasutan_vignette_image')) {
			kasutan_vignette_image();
		}

	

	
		if(function_exists('kasutan_cat_pour_filtre')) {
			kasutan_cat_pour_filtre();
		}

		//TODO améliorer fallbacks ?
		$infos_cat=array('nom'=>'Autres','couleur'=>'rouge');

		if(function_exists('kasutan_get_cat_et_couleur')) {
			$infos_cat=kasutan_get_cat_et_couleur();
		}

		

		printf('<div class="texte has-%s-background-color">',$infos_cat['couleur']);
		
			echo '<div class="titre-wrap">';
				printf('<%s class="titre">%s</%s>',$tag_titre,get_the_title(),$tag_titre);

				printf('<p class="date">%s</p>', get_the_date("d.m.Y"));
			echo '</div>';

			$extrait=wpautop(get_the_excerpt());
			printf('<div class="extrait">%s</div>',$extrait);
		
		echo '</div>';
	
	echo '</a>';


	printf('<a href="%s" class="cat"><div class="fond has-%s-background-color"></div><span>%s</span></a>',$infos_cat['url'],$infos_cat['couleur'],$infos_cat['nom']);

printf('</%s>',$tag);

