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
				$count=0;
				while ( have_posts() ) : the_post();
					$count++;
					get_template_part( 'partials/archive');
				endwhile;
		
				echo '</ul>';

				echo '<div class="pagination-wrap">';
					printf('<div class="count"><strong id="nb-display">6</strong> sur %s</div>',$count);
					?><div class="pagination-list">
						<a class="pagination-prev disabled nav" href="#"  title="Page précédente">
						<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M0.75 6.00492C0.75 5.8769 0.769817 5.74888 0.809451 5.63071C0.849085 5.51253 0.918445 5.40421 1.01753 5.30573L5.60518 0.756043C5.78354 0.578782 6.01143 0.5 6.27896 0.5C6.54649 0.5 6.7843 0.598478 6.96265 0.785586C7.14101 0.972695 7.24009 1.18935 7.24009 1.47493C7.24009 1.76052 7.15091 1.98702 6.96265 2.16428L3.09832 6.00492L6.99238 9.86526C7.17073 10.0524 7.25 10.2789 7.25 10.5448C7.25 10.8107 7.15091 11.0372 6.96265 11.2243C6.77439 11.4114 6.54649 11.5 6.26905 11.5C5.99162 11.5 5.75381 11.4114 5.57546 11.2243L1.01753 6.69427C0.918445 6.59579 0.849085 6.48747 0.809451 6.36929C0.769817 6.25112 0.75 6.13295 0.75 5.99508L0.75 6.00492Z" fill="#757575"/>
						</svg>
						</a>
						<ul class="pagination"></ul>
						<a class="pagination-next nav" href="#" title="Page suivante">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M15.25 11.9951C15.25 12.1231 15.2302 12.2511 15.1905 12.3693C15.1509 12.4875 15.0816 12.5958 14.9825 12.6943L10.3948 17.244C10.2165 17.4212 9.98857 17.5 9.72104 17.5C9.45351 17.5 9.2157 17.4015 9.03735 17.2144C8.85899 17.0273 8.75991 16.8107 8.75991 16.5251C8.75991 16.2395 8.84909 16.013 9.03735 15.8357L12.9017 11.9951L9.00762 8.13474C8.82927 7.94763 8.75 7.72113 8.75 7.45524C8.75 7.18935 8.84908 6.96285 9.03735 6.77574C9.22561 6.58863 9.45351 6.5 9.73094 6.5C10.0084 6.5 10.2462 6.58863 10.4245 6.77574L14.9825 11.3057C15.0816 11.4042 15.1509 11.5125 15.1905 11.6307C15.2302 11.7489 15.25 11.8671 15.25 12.0049L15.25 11.9951Z" fill="#757575"/>
							</svg>
						</a>
					</div>
				</div>

			</div>
		<?php
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