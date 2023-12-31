<?php
/**
 * Template Tags
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


//TODO supprimer code inutile

/**
 * Logos pour l'en-tête et le pied de page
 *
 */

function kasutan_affiche_logos($classe='') {
	$url="/";
	
	$logo_1=$logo_2=false;
	if(function_exists('get_field')) {
		$logo_1=esc_attr(get_field('lapeyremag_logo_1','option'));
		$logo_2=esc_attr(get_field('lapeyremag_logo_2','option'));
	}
	if(!$logo_1 && !$logo_2) {
		$logo_1=get_theme_mod( 'custom_logo' );
	}
	if($logo_1) {
		$tag_2='';
		if($logo_2) {
			$tag_2=wp_get_attachment_image($logo_2,'medium');
		}
		printf('<a class="%s" href="%s">%s%s</a>',
			$classe,$url,wp_get_attachment_image($logo_1,'medium'),$tag_2
		);
	} else {
		printf('<a class="%s" href="%s">%s</a>',
		$classe,$url,get_bloginfo('name')
	);
	}

}


/**
 * Liste des catégories séparées par des espaces pour le filtre
 *
 */

function kasutan_cat_pour_filtre() {
	$terms = get_the_terms( get_the_ID(), 'category');
	if( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}

	$slugs=array();
	foreach($terms as $term) {
		$slugs[]=$term->slug;
	}

	printf('<span class="categorie screen-reader-text">%s</span>',implode(' ',$slugs));
}


/**
 * Post Summary Title
 *
 */
function ea_post_summary_title() {
	global $wp_query;
	$tag = ( is_singular() || -1 === $wp_query->current_post ) ? 'h3' : 'h2';
	echo '<' . $tag . ' class="post-summary__title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></' . $tag . '>';
}

/**
 * Affiche l'image vignette d'un article
 *
 */
function kasutan_vignette_image( $size = 'medium_large') {
	/*On cherche une image à afficher*/ 
	$image_id=get_theme_mod( 'custom_logo' ); //défaut : le logo du site
	$classe='contain';
	if(has_post_thumbnail()) {
		$image_id=get_post_thumbnail_id();
		$classe='';
	} else if(function_exists('get_field')) {

		$banniere=get_field('lapeyre_banniere_image');
		if($banniere) {
			$image_id=$banniere;
			$classe='';
		} else {
			if($logo) {
				$image_id=$logo;
				$classe='logo';
			}
		}
	}


	printf('<div class="vignette-image %s">%s</div>',
		$classe,
		wp_get_attachment_image( $image_id, $size )
	);
}

/**
 * Afficher le temps de lecture d'un article
 */
function kasutan_affiche_temps() {
	$picto='';
	if(function_exists('kasutan_picto')) {
		$picto=sprintf('<span class="picto">%s</span>',kasutan_picto(array('icon'=>'horloge')));
	}
	$content=get_the_content();
	$mots=str_word_count($content);
	$temps=$mots / 200; // 200 mots par minute
	$temps=round($temps);
	printf('<p class="temps">%s <strong>%s min </strong>de temps de lecture</p>',$picto,$temps);

}


/**
 * Afficher un slider d'articles
 */
function kasutan_affiche_slider($posts) {
	if(empty($posts)) {
		return;
	}

	$index=0;
	$total=count($posts);
	global $post; 
	
	printf('<div class="slider-wrap" data-total="%s">',$total);
		echo '<ul class="slider">';
		foreach ($posts as $post_id) {
			$index++;
			$post = get_post($post_id); 
			setup_postdata($post);
			get_template_part( 'partials/archive', null,array('balise_title'=>'h3','index'=>$index) );
		}
		wp_reset_postdata();
		echo '</ul>';
		echo '<div class="nav-slider">';
		?>
		<button class="pagination-prev disabled nav" data-direction="-1">
			<span class="sr-text">Faire défiler vers la slide précédente</span>
			<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M0.75 6.00492C0.75 5.8769 0.769817 5.74888 0.809451 5.63071C0.849085 5.51253 0.918445 5.40421 1.01753 5.30573L5.60518 0.756043C5.78354 0.578782 6.01143 0.5 6.27896 0.5C6.54649 0.5 6.7843 0.598478 6.96265 0.785586C7.14101 0.972695 7.24009 1.18935 7.24009 1.47493C7.24009 1.76052 7.15091 1.98702 6.96265 2.16428L3.09832 6.00492L6.99238 9.86526C7.17073 10.0524 7.25 10.2789 7.25 10.5448C7.25 10.8107 7.15091 11.0372 6.96265 11.2243C6.77439 11.4114 6.54649 11.5 6.26905 11.5C5.99162 11.5 5.75381 11.4114 5.57546 11.2243L1.01753 6.69427C0.918445 6.59579 0.849085 6.48747 0.809451 6.36929C0.769817 6.25112 0.75 6.13295 0.75 5.99508L0.75 6.00492Z" fill="#757575"/>
			</svg>
		</button>
		<ul class="dots">
			<?php for($i=1;$i<=$index;$i++) {
				printf('<button class="dot" data-target="slide-%s"><span class="sr-text">Faire défiler vers la slide %s</span></button>',$i,$i);
			} ?>
		</ul>
		<button class="pagination-next nav" data-direction="+1">
			<span class="sr-text">Faire défiler vers la slide suivante</span>
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M15.25 11.9951C15.25 12.1231 15.2302 12.2511 15.1905 12.3693C15.1509 12.4875 15.0816 12.5958 14.9825 12.6943L10.3948 17.244C10.2165 17.4212 9.98857 17.5 9.72104 17.5C9.45351 17.5 9.2157 17.4015 9.03735 17.2144C8.85899 17.0273 8.75991 16.8107 8.75991 16.5251C8.75991 16.2395 8.84909 16.013 9.03735 15.8357L12.9017 11.9951L9.00762 8.13474C8.82927 7.94763 8.75 7.72113 8.75 7.45524C8.75 7.18935 8.84908 6.96285 9.03735 6.77574C9.22561 6.58863 9.45351 6.5 9.73094 6.5C10.0084 6.5 10.2462 6.58863 10.4245 6.77574L14.9825 11.3057C15.0816 11.4042 15.1509 11.5125 15.1905 11.6307C15.2302 11.7489 15.25 11.8671 15.25 12.0049L15.25 11.9951Z" fill="#757575"/>
			</svg>
		</button>
		<?php
		echo '</div>'; //.nav-slider
	echo '</div>'; //.slider-wrap
}




/**
 * Entry Author
 *
 */
function ea_entry_author() {
	$id = get_the_author_meta( 'ID' );
	echo '<p class="entry-author"><a href="' . get_author_posts_url( $id ) . '" aria-hidden="true" tabindex="-1">' . get_avatar( $id, 40 ) . '</a><em>by</em> <a href="' . get_author_posts_url( $id ) . '">' . get_the_author() . '</a></p>';
}

/**
* Affiche le fil d'ariane.
*/
function kasutan_fil_ariane() {

	$accueil_url=get_option('lpm_accueil_url');
	if(empty($accueil_url)) {
		$accueil_url="https://www.lapeyre.fr/c/magazine";
	}

	//Une version desktop et une version mobile tronquée avec bouton retour
	//On construit le desktop et en même temps on stocke l'url du niveau précédent pour le bouton en mobile

	$prev_url=$accueil_url; //Valeur par défaut
	$prev_name="Accueil";


	echo '<p class="fil-ariane desktop">';
	
	//Pour toutes les pages et archives : afficher en premier le lien vers l'accueil du site e-commerce
	printf('<a href="%s">Accueil</a><span class="sep">|</span>',
		$accueil_url
	);

	if(is_page()) {
		//Pour les pages ordinaires, ajouter le titre de la page
		$title=strip_tags(get_the_title());
	} else if(is_category()) {
		//Pour les catégories d'article
		$cat=get_queried_object();
		$parent_id=$cat->parent;

		//Si la catégorie a un parent, on insère un lien vers son archive
		if($parent_id) {
			$parent_link=get_category_link($parent_id);
			$parent_name=get_cat_name($parent_id);
			printf('<a href="%s">%s</a><span class="sep">|</span>',
				$parent_link,
				$parent_name
			);
			$prev_url=$parent_link;
			$prev_name=$parent_name;
		}

		//Nom de la catégorie courante
		$title=strip_tags(single_cat_title( '', false ));

	} else if(is_single()) {
		//Liens vers les catégories
		if(function_exists('kasutan_get_infos_cats')) {
			$infos=kasutan_get_infos_cats();
		}

		if($infos['parent_name'] && $infos['parent_link']) {
			printf('<a href="%s">%s</a><span class="sep">|</span>',
				$infos['parent_link'],
				$infos['parent_name']
			);

			$prev_url=$infos['parent_link'];
			$prev_name=$infos['parent_name'];
			
		}
		
		if($infos['child_name'] && $infos['child_link']) {
			printf('<a href="%s">%s</a><span class="sep">|</span>',
				$infos['child_link'],
				$infos['child_name']
			);

			$prev_url=$infos['child_link'];
			$prev_name=$infos['child_name'];

		}

		//Nom du post
		$title=strip_tags(get_the_title());

	} elseif (is_tag()) {  //archives tags d'articles
		$title=strip_tags(single_tag_title( '', false ));
	} elseif (is_search()) {
		$title='Recherche : '.get_search_query();
	} elseif (is_404()) {
		$title="Page introuvable";

	}
	//Pour tous les contenus, on affiche le titre du contenu courant
		printf('<span class="current">%s</span>',$title);

	//Fermer la balise du fil d'ariane desktop
	echo '</p>';

	//Afficher aussi un fil d'ariane spécial pour mobile avec juste le titre coupé et un bouton back qui renvoie vers le niveau précédent

	echo '<p class="fil-ariane mobile">';

		printf('<a class="prev" href="%s"><span class="screen-reader-text">%s</span>',$prev_url,$prev_name);
			echo '<svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M9.26174 0.400169C9.47334 0.608722 9.58337 0.858987 9.58337 1.14262C9.58337 1.42625 9.47334 1.67651 9.26174 1.88507L3.05752 7.99984L9.28713 14.1229C9.4818 14.3148 9.58337 14.5651 9.58337 14.8571C9.58337 15.149 9.47334 15.391 9.26174 15.5995C9.05013 15.8081 8.79621 15.9165 8.50843 15.9165C8.22065 15.9165 7.96672 15.8081 7.75512 15.5995L0.645237 8.5671C0.560596 8.48368 0.501347 8.39192 0.46749 8.29181C0.433634 8.19171 0.416706 8.0916 0.416706 7.97481C0.416706 7.85802 0.433634 7.75792 0.46749 7.65781C0.501347 7.55771 0.560596 7.46594 0.645237 7.38252L7.78051 0.375144C7.97519 0.183275 8.22065 0.0831701 8.50843 0.08317C8.79621 0.08317 9.05013 0.191616 9.26174 0.400169Z" fill="#5D89A2"/>
			</svg>';
		echo '</a>';

		printf('<span class="current">&mldr;<span class="sep">|</span>%s</span>',$title);
	
	echo '</p>'; // fin du fil d'ariane mobile

}


/**
* Affiche le titre des pages ordinaires
*
*/
function kasutan_page_titre() {
	$masquer=false;
	$classe="entry-title";
	$titre=get_the_title();
	if(function_exists('get_field') && esc_attr(get_field('lapeyremag_masquer_titre'))==='oui') {
		$masquer=true;
	}
	if(is_front_page(  )) {
		$masquer=true;
	}
	if($masquer) {
		$classe.=" screen-reader-text";
	}
	printf('<h1 class="%s">%s</h1>',$classe,$titre);
}

/**
* Image banniere pour les pages ordinaires
*
*/
function kasutan_page_banniere($page_id=false,$use_defaut=false) {
	//TODO supprimer les arguments
	//TODO variante pour page spéciale type d'article (option dans le groupe de champs ou modèle de page ?)

	$image_id=$image_mobile_id=$titre=$sous_titre="";
	if(function_exists('get_field')) {
		$image_id=esc_attr(get_field('lapeyre_banniere_image'));
		$image_mobile_id=esc_attr(get_field('lapeyre_banniere_image_mobile'));
		$titre=wp_kses_post(get_field('lapeyre_banniere_titre'));
		$sous_titre=wp_kses_post(get_field('lapeyre_banniere_sous_titre'));
	}

	if(empty($titre)) {
		$titre=get_the_title();
		//TODO adapter pour recherche et 404
	}
	


	if(empty($image_id)) {
		printf('<h1 class="entry-title sans-banniere">%s</h1>',$titre);
	} else {
		if(empty($image_mobile_id)) {
			$image_mobile_id=$image_id;
		}
		printf('<div class="page-banniere">');
			echo wp_get_attachment_image( $image_mobile_id, 'medium_large',false,array('decoding'=>'async','loading'=>'eager','class'=>'mobile'));
			echo wp_get_attachment_image( $image_id, 'banniere',false,array('decoding'=>'async','loading'=>'eager','class'=>'desktop'));
			echo '<div class="overlay"></div>';
			printf('<h1 class="entry-title">%s</h1>',$titre);
			if($sous_titre) printf('<p class="sous-titre">%s</p>',$sous_titre);
		echo '</div>';
	}
}

/**
* Image banniere pour les actus + utilisée aussi pour la recherche
*
*/
function kasutan_actus_banniere() {
	if(!function_exists('get_field')) {
		return;
	}


	if(is_single()) {
		//On est sur un post single, on vérifie s'il a sa propre image bannière
		$image_id=esc_attr(get_field('lapeyremag_banniere_image'));
		if(!$image_id) {
			//si le post n'a pas d'image bannière on utilise l'image par défaut
			$image_id=esc_attr(get_field('lapeyremag_bg_image','option'));//image par défaut
		}

		kasutan_page_banniere(get_the_ID());
		return;
	} 

	if(is_search()) {
		
		kasutan_page_banniere(false,true);
		return;
	}

	//On est sur une page d'archive, on affiche la bannière de la page des actualités
	$actus=get_option('page_for_posts'); 

	kasutan_page_banniere($actus);
}


/**
* Filtre par catégories pour les archives de blog
*
*/
function kasutan_affiche_filtre_articles() {
	echo '<p class="screen-reader-text">Filtrer les actualités</p>';
	echo '<form class="filtre-archive" id="filtre-liste">';
		$terms=get_terms( array(
			'taxonomy' => 'category'
			) 
		);

		$label_all="Toutes";
		

		

		?>
		<input type="radio" id="toutes" name="filtre-categorie" value="toutes" checked>
		<?php
		printf('<label for="toutes" class="toutes">%s</label>',$label_all);
		foreach($terms as $term) : 
			


			$pluriel=false;
			if(function_exists('get_field')) {
				$pluriel=esc_attr(get_field('pluriel',$term));
			}
			if(!$pluriel) {
				$pluriel=$term->name;
			}


			

			printf('<input type="radio" id="%s" name="filtre-categorie" value="%s">',
				$term->slug,
				$term->slug
			);
			printf('<label for="%s">%s</label>',
				$term->slug,
				$pluriel
			);
		endforeach;
		?>
		
	</form>
<?php
}




/**
 * Change the excerpt more string
 */
function kasutan_excerpt_more( $more ) {
	return '&hellip; >>>';
}
add_filter( 'excerpt_more', 'kasutan_excerpt_more' );

function kasutan__excerpt_length( $length ) {

	return 22;
	
}
add_filter( 'excerpt_length', 'kasutan__excerpt_length', 999 );