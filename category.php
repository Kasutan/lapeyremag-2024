<?php
/**
 * Archive des catégories produits
 *
 * @package      lapeyremag-2024
 * @author       Magalie Castaing
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**TODO fonctions helpers pour les catégories
 * 
 * shortcode helper pour parcourir toutes les sous-catégories et leur associer un custom field avec l'une des trois options $types=['guide','tendance','tutoriel'] d'après leur nom. Puis laisser ce champ accessible en BO pour permettre de renommer les sous-cats tout en gardant la logique. 
 * 
 * ajouter pour chaque option un champ ACF pour stocker le titre avec article "nos guides" "les tendances" "nos tutoriels" et un picto (pour le bloc de navigation par types d'articles). 
 * 
 * fonction pour identifier de quel type est une sous-catégorie 
 * 
 * mettre les sous-catégories dans le bon ordre (avec plugin, fait en local, à refaire sur le serveur)
 * 
 * obtenir toutes les catégories qui ont en meta une option pour le type d'article (utile pour construire les pages d'un type d'article) -> on pourra avoir un bloc ACF unique avec juste en option un choix parmi les mots-clés $types=['guide','tendance','tutoriel']
*/

$cat=get_queried_object();
$name=$cat->name;

$parent_id=$cat->parent;
if($parent_id) {
	$has_parent=true;
	$parent_name=get_cat_name($parent_id);

	wp_enqueue_script( 'lapeyremag-listjs', get_template_directory_uri() . '/lib/list/list.min.js', array(), '1.0', true );
	wp_enqueue_script( 'lapeyremag-pagination', get_template_directory_uri() . '/js/min/pagination.js', array('jquery','lapeyremag-listjs'), filemtime( get_template_directory() . '/js/min/pagination.js'), true );
} else {
	$has_parent=false;
	$enfants=get_categories(
		array( 'parent' => $cat->cat_ID )
	);
}

get_header();


echo '<div class="ea_content_area_wrap">';
echo '<main class="site-main">';

	if(function_exists('kasutan_fil_ariane')) {
		kasutan_fil_ariane();
	}

	echo '<header class="entry-header">';
		if($has_parent) printf('<p class="sur-titre parent">%s</p>',$parent_name);
		
		echo '<h1 class="entry-title">' . $name . '</h1>';
		
		if(!$has_parent) echo '<p class="sous-titre">Phrase intro cat parent ici (get_field)</p>';

		echo '<p>image banniere ici (get_field)</p>';
	echo '</header>';


	if($has_parent) {
		echo '<p>Phrase intro cat enfant ici (get_field)</p>';

		if ( have_posts() ) :

			echo '<div id="archive-filtrable">';
			echo '<ul class="loop list">';

			/* Start the Loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'partials/archive');
			endwhile;
	
			echo '</ul>';
			echo '<div class="pagination-wrap"><ul class="pagination"></ul><p>xx sur xxx</p></div>';
			echo '</div>';
	
		else :

			get_template_part( 'partials/archive', 'none' );
	
		endif;

		printf('<section class="has-beige-clair-background-color"> Prête à lancer votre projet ? Infos dans les champs personnalisés de la la catégorie parente : %s</section>',$parent_name);

		printf('<section> Voulez-vous découvrir autres articles ? Navigation vers archive univers x même type d article que la page actuelle. Exclure la page actuelle de la navigation</section>');

	} else {

		echo '<div class="container has-beige-clair-background-color">';

		$types=['guide','tendance','tutoriel'];
		foreach($types as $type) {
			printf('<section>Les 3 derniers articles de la sous-catégorie avec mot-clé %s dans cet univers %s + lien vers page archive univers x type d\'article</section>',$type,$name);
		}
		//TODO : après avoir mis les sous-catégories dans l'ordre, appeler tout simplement les catégories enfants de la catégorie actuelle. Pour chaque sous-catégorie, on retr
		echo '</div>';

		printf('<section> Voulez-vous découvrir autres articles ? Navigation vers archive univers de niveau 1. Exclure la page univers actuelle de la navigation</section>');
	}


echo '</main>';

echo '</div>';


get_footer();