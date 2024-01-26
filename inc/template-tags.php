<?php
/**
 * Template Tags
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


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
 * Afficher les tags univers et type d'article
 * A partir des infos catégories
 */
function kasutan_affiche_post_tags($infos) {
	
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

	if($tag_univers || $tag_type) {
		echo '<div class="tags">';
			if($tag_univers) echo $tag_univers;
			if($tag_type) echo $tag_type;
		echo '</div>';
}

}


/**
 * Afficher un slider d'articles
 */
function kasutan_affiche_slider($posts,$tag_titre='h3') {
	if(empty($posts)) {
		return;
	}

	$index=0;
	$total=count($posts);
	global $post; 
	
	printf('<div class="slider-wrap" data-total="%s" data-active="0">',$total);
		echo '<div class="slider-drag"><ul class="slider">';
		foreach ($posts as $post_id) {
			$post = get_post($post_id); 
			setup_postdata($post);
			get_template_part( 'partials/archive', null,array('tag_titre'=>$tag_titre,'index'=>$index,'slider'=>true) );
			$index++;
		}
		wp_reset_postdata();
		echo '</ul></div>';
		kasutan_affiche_nav_slider($total);
	echo '</div>'; //.slider-wrap
}

/**
 * Afficher les boutons de navigation d'un slider (articles ou pages)
 */
function kasutan_affiche_nav_slider($total) {
	echo '<div class="nav-slider">';
		?>
		<button class="fleche-slider gauche" data-direction="-1" disabled>
			<span class="sr-text">Faire défiler vers la slide précédente</span>
			<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M0.75 6.00492C0.75 5.8769 0.769817 5.74888 0.809451 5.63071C0.849085 5.51253 0.918445 5.40421 1.01753 5.30573L5.60518 0.756043C5.78354 0.578782 6.01143 0.5 6.27896 0.5C6.54649 0.5 6.7843 0.598478 6.96265 0.785586C7.14101 0.972695 7.24009 1.18935 7.24009 1.47493C7.24009 1.76052 7.15091 1.98702 6.96265 2.16428L3.09832 6.00492L6.99238 9.86526C7.17073 10.0524 7.25 10.2789 7.25 10.5448C7.25 10.8107 7.15091 11.0372 6.96265 11.2243C6.77439 11.4114 6.54649 11.5 6.26905 11.5C5.99162 11.5 5.75381 11.4114 5.57546 11.2243L1.01753 6.69427C0.918445 6.59579 0.849085 6.48747 0.809451 6.36929C0.769817 6.25112 0.75 6.13295 0.75 5.99508L0.75 6.00492Z" fill="#757575"/>
			</svg>
		</button>
		<div class="dots">
			<?php 
			$class_dot="active";//uniquement pour le premier bouton
			for($i=0;$i<$total;$i++) {
				printf('<button class="dot %s" data-target="%s"><span class="sr-text">Faire défiler vers la slide %s</span></button>',$class_dot,$i,$i+1);
				$class_dot="";
			} ?>
		</div>
		<button class="fleche-slider droite" data-direction="+1">
			<span class="sr-text">Faire défiler vers la slide suivante</span>
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M15.25 11.9951C15.25 12.1231 15.2302 12.2511 15.1905 12.3693C15.1509 12.4875 15.0816 12.5958 14.9825 12.6943L10.3948 17.244C10.2165 17.4212 9.98857 17.5 9.72104 17.5C9.45351 17.5 9.2157 17.4015 9.03735 17.2144C8.85899 17.0273 8.75991 16.8107 8.75991 16.5251C8.75991 16.2395 8.84909 16.013 9.03735 15.8357L12.9017 11.9951L9.00762 8.13474C8.82927 7.94763 8.75 7.72113 8.75 7.45524C8.75 7.18935 8.84908 6.96285 9.03735 6.77574C9.22561 6.58863 9.45351 6.5 9.73094 6.5C10.0084 6.5 10.2462 6.58863 10.4245 6.77574L14.9825 11.3057C15.0816 11.4042 15.1509 11.5125 15.1905 11.6307C15.2302 11.7489 15.25 11.8671 15.25 12.0049L15.25 11.9951Z" fill="#757575"/>
			</svg>
		</button>
		<?php
		echo '</div>'; //.nav-slider
}

/**
* Affiche le fil d'ariane.
*/
function kasutan_fil_ariane() {

	$accueil_url=get_option('options_lapeyre_cible_home_ariane','false');
	if(empty($accueil_url)) {
		$accueil_url="/accueil-temporaire";
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
* Image banniere pour les pages ordinaires
*
*/
function kasutan_page_banniere() {
	//TODO simplifier (une seule image desktop et mobile si on la demande au bon format)

	$image_id=$image_mobile_id=$titre=$sous_titre="";
	if(function_exists('get_field')) {
		$image_id=esc_attr(get_field('lapeyre_banniere_image'));
		$image_mobile_id=esc_attr(get_field('lapeyre_banniere_image_mobile'));
		$titre=wp_kses_post(get_field('lapeyre_banniere_titre'));
		$sous_titre=wp_kses_post(get_field('lapeyre_banniere_sous_titre'));
	}

	if(empty($titre)) {
		$titre=get_the_title();
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
* Image banniere pour les pages avec le modèle Page d'un type d'article
*
*/
function kasutan_page_banniere_type() {

	$image_id=$titre=$sous_titre=$couleur="";
	if(function_exists('get_field')) {
		$image_id=esc_attr(get_field('lapeyre_banniere_image'));
		$couleur=esc_attr(get_field('lapeyre_banniere_couleur'));
		$titre=wp_kses_post(get_field('lapeyre_banniere_titre'));
		$sous_titre=wp_kses_post(get_field('lapeyre_banniere_sous_titre'));
	}

	if(empty($titre)) {
		$titre=get_the_title();
	}

	if(empty($couleur)) {
		$couleur="transparent";
	}

	printf('<div class="page-banniere-type" style="background-color:%s">',$couleur);
		echo '<div class="image">';
			echo wp_get_attachment_image( $image_id, 'full',false,array('decoding'=>'async','loading'=>'eager'));
		echo '</div>';
		printf('<h1 class="entry-title">%s</h1>',$titre);
		if($sous_titre) printf('<p class="sous-titre">%s</p>',$sous_titre);
	echo '</div>';
	
}

/**
* Image banniere pour les articles
*
*/
function kasutan_single_banniere() {

	
	$image_id=$date_modif="";
	if(function_exists('get_field')) {
		$image_id=esc_attr(get_field('lapeyre_banniere_image'));
		$date_modif=esc_attr(get_field('lapeyre_date_modif')); 


		if(!$image_id) {
			$image_id=esc_attr(get_field('lapeyre_banniere_defaut','option'));
		}
	}



	$infos=array();
	if(function_exists('kasutan_get_infos_cats')) {
		$infos=kasutan_get_infos_cats('',true);
	}

	

	printf('<div class="page-banniere-single" data-post-id="%s">',get_the_ID());
		if($image_id) {
			echo '<div class="image">';
				echo wp_get_attachment_image( $image_id, 'banniere',false,array('decoding'=>'async','loading'=>'eager'));
			echo '</div>';
		}
		echo '<div class="overlay"></div>';

		echo '<div class="encart">';

			//tags univers et type article
			if(function_exists('kasutan_affiche_post_tags')) {
				kasutan_affiche_post_tags($infos);
			}
			
			printf('<h1 class="single-title">%s</h1>',get_the_title());

			echo '<div class="dates">';
				printf('<p>écrit le : <strong>%s</strong></p>', get_the_date('d F Y'));
				if($date_modif)	printf('<p>mis à jour le : <strong>%s</strong></p>',$date_modif);

			echo '</div>';//.dates
		echo '</div>'; //.encart
	echo '</div>';
}

/**
* Boutons de partage pour un single
* simplesharingbuttons.com
*/
function kasutan_boutons_partage($avec_titre) {
	$permalink_brut=get_the_permalink();
	$lien=urlencode($permalink_brut);
	$titre=urlencode(get_the_title());

	$picto=kasutan_picto_simple('lien');
	if($avec_titre) {
		$picto=kasutan_picto_simple('lien-2');
	}
	$media=false;
	if(has_post_thumbnail()) {
		$media=get_the_post_thumbnail_url(null, 'large');
	}
	if(!empty($media)) $media=urlencode($media);

	$canaux=['facebook','pinterest','x']; //dans l'ordre où les liens seront affichés
	$urls=array();

	$urls['facebook']='https://www.facebook.com/sharer/sharer.php?u='.$lien.'&quote='.$titre;

	$urls['x']='https://twitter.com/intent/tweet?source='.$lien.'&text='.$titre;

	$urls['pinterest']="http://pinterest.com/pin/create/button/?url=".$lien;

	if($media) {
		$urls['pinterest'].='&media='.$media;
	}


	$labels=array();
	$labels['facebook']=__('Partager sur Facebook','lapeyremag');
	$labels['x']=__('Partager sur X','lapeyremag');
	$labels['pinterest']=__('&Eacute;pingler sur Pinterest','lapeyremag');

	$titre=false;
	if($avec_titre && function_exists('get_field')) {
		$titre=wp_kses_post(get_field('lapeyre_titre_partage','option'));
	}
	if($avec_titre && !$titre) {
		$titre=__('Ces conseils vous plaisent ? Parlez-en autour de vous.','lapeyremag');
	}
	echo '<div class="partage social">';
		if($titre) printf('<p class="titre-partage">%s</p>',$titre);
		echo '<nav class="reseaux">';
			printf('<button class="copier-url" title="Copier le lien" data-url="%s">%s</button>',$permalink_brut,$picto);
			foreach($canaux as $canal) {
				printf('<a href="%s" class="%s" title="%s" target="_blank" rel="noopener noreferrer">%s</a>',$urls[$canal],$canal,$labels[$canal],
				kasutan_picto(array('icon'=>$canal)));
			}
		echo '</nav>';
	echo '</div>';
}

/**
* Articles related pour un single
* 
*/
function kasutan_affiche_related($term_id) {
	if(!function_exists('kasutan_affiche_slider')) {
		return;
	}
	$exclude=array();
	$exclude[]=get_the_ID();
	$posts=get_posts(array(
		'numberposts'=>12,
		'category'=>$term_id,
		'fields'=>'ids',
		'post__not_in'=>$exclude
	));

	if(empty($posts)) {
		return;
	}

	$titre=false;
	if(function_exists('get_field')) {
		$titre=wp_kses_post(get_field('lapeyre_related_titre','option'));
	}

	echo '<section class="related">';
		if($titre) printf('<p class="h2 titre-section">%s</p>',$titre);
		kasutan_affiche_slider($posts,'h2');
	echo '</section>';
}


//Navigation par univers
//Avec une variante si !$key -> liens vers les sous-catégories de type $key mais avec avec image et nom de la catégorie parente
function kasutan_affiche_nav_univers($exclure=false,$key=false) {
	if(!function_exists('get_field')) {
		return;
	}

	$cats=get_categories();

	if(!empty($cats)) {
		$titre=wp_kses_post(get_field('lapeyre_nav_univers_titre','option'));
		//Préparer les vignettes univers et les compter
		$total=0;
		ob_start();
		foreach($cats as $cat) {
			$term_id=$cat->term_id;
			$nom=$cat->name;

			if($cat->parent) {
				//C'est une catégorie enfant
				continue;
			}
			if($exclure && $term_id===$exclure) {
				//C'est la catégorie qu'on voulait exclure
				continue;
			}
			if(strpos(strtolower($nom),'old')!==false) {
				//C'est une ancienne catégorie
				continue;
			}

			//On va afficher cette vignette
			$total++;
			
			$image_id=false;
			$image_id=esc_attr(get_field('lapeyre_vignette','category_'.$term_id));
			if(!$image_id) {
				$image_id=esc_attr(get_field('lapeyre_banniere_image','category_'.$term_id));
			}
			if(!$image_id) {
				$image_id=esc_attr(get_field('lapeyre_banniere_defaut','option'));
			}

			//Lien vers le parent ou vers une cat enfant ?
			$lien=get_term_link($cat); //pour la version vers cat parent et aussi en fallback

			if($key) {
				$enfants=get_categories(
					array( 'parent' => $term_id )
				);
				foreach($enfants as $enfant_id) {
					$enfant=get_term($enfant_id);
					$nom_enfant=$enfant->name;
					if(strpos(strtolower($nom_enfant),$key) !== false) {
						//On a trouvé la catégorie enfant de type $key
						$lien=get_term_link($enfant);
					}
				}
			}
			
			printf('<a href="%s" class="slide">%s <div class="nom">%s</div></a>',
				$lien,
				wp_get_attachment_image($image_id, "small"),
				$nom
			);
		}
		$vignettes=ob_get_clean();
		echo '<div class="nav-univers">';
			if($titre) printf('<p class="h2 titre-section">%s</p>',$titre);
			printf('<div class="slider-wrap" data-total="%s" data-active="0">',$total);
				echo '<div class="slider-drag"><nav class="slider slider-univers">';
					echo $vignettes;
				echo '</nav></div>';
				kasutan_affiche_nav_slider($total);
			echo '</div>'; //.slider-wrap
		echo '</div>';
	}
}

//Section projet pour un univers
function kasutan_affiche_projet($term_id,$nom) {
	if(!function_exists('get_field')) {
		return;
	}
	$projet=get_field('lapeyre_projet','category_'.$term_id); //champ ACF de type groupe

	if(empty($projet)) {
		return;
	}
	$image=esc_attr($projet['image']);
	$titre=wp_kses_post($projet['titre']);
	$desc=wp_kses_post($projet['descriptif']);
	$lien=$projet['bouton'];

	//On a besoin au moins du lien et du titre pour afficher la section
	if(empty($titre) || empty($lien)) {
		return;
	}

	echo '<div class="projet">';
		if($image) {
			echo wp_get_attachment_image($image, 'large');
		}
		echo '<div class="texte">';
			printf('<p class="nom">%s</p>',$nom);
			printf('<p class="titre">%s</p>',$titre);
			if($desc) printf('<div class="desc">%s</div>',$desc);
			$attr='';
			if($lien['target'] === "_blank") {
				$attr='target="_blank" rel="noopener noreferrer"';
			}
			printf('<a href="%s" class="bouton bleu" %s>%s</a>',
				esc_url($lien['url']),
				$attr,
				wp_kses_post( $lien['title'] )
			);
		echo '</div>';
	echo '</div>';

}