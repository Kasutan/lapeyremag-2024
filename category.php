<?php
/**
 * Archive des catégories produits
 *
 * @package      lapeyremag-2024
 * @author       Magalie Castaing
 * @since        1.0.0
 * @license      GPL-2.0+
**/

$cat=get_queried_object();
$term_id=$cat->term_id;
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

$image=$sous_titre=false;
$titre=$name;
if($has_parent) $titre_loop=$name.' '.$parent_name;
$titres_sections=array();
$key_pour_cette_sous_cat=false;

if(function_exists('get_field')) {
	//Bannière parent ou enfant : dans les champs ACF de la catégorie
	$image=esc_attr(get_field('lapeyre_banniere_image','category_'.$term_id));

	//Sous-titre pour la catégorie parent : dans les champs ACF de la catégorie
	if(!$has_parent) {
		$sous_titre=wp_kses_post(get_field('lapeyre_banniere_sous_titre','category_'.$term_id));
	}

	//Titres des sous-catégories pour la catégorie parent : dans les réglages du site
	if(have_rows('lapeyre_types_articles','option')) {
		while(have_rows('lapeyre_types_articles','option')) : the_row();
			$key=esc_attr(get_sub_field('key'));
			$page=get_sub_field('page');
			if($key && $page) {
				$titres_sections[$key]=get_the_title($page);
			}
		endwhile;
	}

	//Titre header et titre de la boucle pour la catégorie enfant : dans les réglages du site
	if($has_parent && have_rows('lapeyre_types_articles','option')) {
		while(have_rows('lapeyre_types_articles','option')) : the_row();

		$key=esc_attr(get_sub_field('key'));
		if(strpos(strtolower($name),$key) !== false) {
			//On a trouvé le type qui correspond à la catégorie actuelle
			//On l'enregistre pour construire plus tard la nav par univers
			$key_pour_cette_sous_cat=$key;

			// Pour avoir le titre avec article
			$page=get_sub_field('page');
			if($page) $titre=get_the_title($page); 

			//Phrase composée d'un chaine liée au type d'article et du nom de la catégorie parente
			$intro=wp_kses_post(get_sub_field('intro'));
			if($intro) $titre_loop=$intro.' '.$parent_name;
		}

		endwhile;
	}


}

get_header();


echo '<div class="ea_content_area_wrap">';
echo '<main class="site-main">';

	echo '<header class="entry-header">';
		if(function_exists('kasutan_fil_ariane')) {
			kasutan_fil_ariane();
		}
		echo '<div class="page-banniere">';
			if($image) echo wp_get_attachment_image($image,'banniere');
			
			echo '<div class="overlay"></div>';
		
			if($has_parent) printf('<p class="sur-titre parent">%s</p>',$parent_name);
		
			echo '<h1 class="entry-title">' . $titre . '</h1>';
		
			if(!$has_parent && $sous_titre) printf('<p class="sous-titre">%s</p>',$sous_titre);

		echo '</div>'; //fermer banniere

		
	echo '</header>';


	if($has_parent) {
		

		if ( have_posts() ) :

			echo '<div id="archive-filtrable">';
				if($titre_loop) printf('<h2 class="titre-loop">%s</h2>',$titre_loop);

				echo '<ul class="loop list">';

				/* Start the Loop */
				$count=0;
				while ( have_posts() ) : the_post();
					$count++;
					get_template_part( 'partials/archive',null,array('tag_titre'=>'h3'));
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

		if($parent_id && function_exists('kasutan_affiche_projet')) {
			kasutan_affiche_projet($parent_id,$parent_name);
		}
		if(function_exists('kasutan_affiche_nav_univers')) {
			kasutan_affiche_nav_univers($parent_id,$key_pour_cette_sous_cat);
		}

	} else {


		$enfants=get_term_children($term_id, 'category');

		if($enfants) {
			foreach($enfants as $cat_id) {
				$cat=get_term($cat_id);
				$nom_enfant=$cat->name;

				$posts=new WP_Query(array(
					'cat'=>$cat_id,
					'posts_per_page'=>3
				));
				if($posts->have_posts()) {

					//Préparer les éléments dynamiques de la section
					$bouton=sprintf('<a href="%s" class="bouton">%s</a>',get_term_link($cat),'Tout découvrir');

					//Titre de la section par défaut = nom de la catégorie
					$titre_section=$nom_enfant; 

					//Titre de la section si on le trouve = nom de la page de ce type d'articles
					if($titres_sections) {
						foreach($titres_sections as $key=>$titre_page) {
							if(strpos(strtolower($nom_enfant),$key) !== false) {
								$titre_section=$titre_page;
							}
						}
					}

					echo '<section class="section-cat">';
						echo '<div class="nav-cat">';
							printf('<h2 class="titre-cat">%s</h2>',$titre_section);
							echo $bouton; //desktop uniquement

						echo '</div>'; //.nav-cat

						echo '<ul class="loop archive-cat">';
							while($posts->have_posts()) {
								$posts->the_post();
								get_template_part( 'partials/archive', null,array('tag_titre'=>'h3'));
							}
							wp_reset_postdata();

						echo '</ul>';

						printf('<div class="bouton-wrap">%s</div>',$bouton); //mobile uniquement

					echo '</section>';
				}
			}
		}

		if(function_exists('kasutan_affiche_nav_univers')) {
			kasutan_affiche_nav_univers($term_id);
		}
	}


echo '</main>';

echo '</div>';


get_footer();