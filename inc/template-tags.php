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
 * Entry Category
 *
 */
function kasutan_get_cat_et_couleur($contexte='archive') {
	$post_type=get_post_type();
	$term=false;
	$reponse=array('nom'=>false,'couleur'=>false,'url'=>false);

	if($post_type==='post') {
		$term = ea_first_term();
	}
	if( !empty( $term ) && ! is_wp_error( $term ) ) {

		$reponse['nom']=$term->name;
		
		if(function_exists('get_field')) $reponse['couleur']=esc_attr(get_field('couleur',$term));

		//Construction de l'url : on renvoie vers la page des actus en activant le filtre
		$actus=get_option( 'page_for_posts' ) ;
		$reponse['url']=get_the_permalink($actus).'?filtre_cat='.$term->slug;
	}
	
	return $reponse;
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
 * Post Summary Image
 *
 */
function kasutan_vignette_image( $size = 'medium' ) {
	/*On cherche une image à afficher*/ 
	$image_id=get_theme_mod( 'custom_logo' ); //défaut : le logo du site
	$classe='contain';
	if(has_post_thumbnail()) {
		$image_id=get_post_thumbnail_id();
		$classe='';
	} else if(function_exists('get_field')) {

		$banniere=get_field('lapeyremag_banniere_image');
		if($banniere) {
			$image_id=$banniere;
			$classe='';
		} else {
			$logo=get_field('lapeyremag_logo_1','option');
			if($logo) {
				$image_id=$logo;
				$classe='contain';
			}
		}
	}

	printf('<div class="vignette-image %s">%s</div>',
		$classe,
		wp_get_attachment_image( $image_id, $size )
	);
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

	//On n'affiche pas le fil d'ariane sur la page d'accueil
	if(is_front_page()) {
		return;
	}

	$post_type=get_post_type();

	echo '<p class="fil-ariane">';

	
	//Pour tous les contenus : afficher en premier le lien vers l'accueil du site
	$accueil=get_option('page_on_front'); //Traduit par PLL ✔️
	printf('<a href="%s">%s</a><span class="sep">></span>',
		get_the_permalink( $accueil),
		strip_tags(get_the_title($accueil))
	);
	


	//Afficher la page des actualités pour les articles (single ou archive de catégorie ou archive des articles ou archive de tag)
	if ( (is_single() && 'post' === $post_type) || is_category() || is_tag() ) :
		//l'ID de la page est stockée dans les options du site
		$actus=get_option('page_for_posts'); //Traduit par PLL ✔️
		if($actus) :
			printf('<a href="%s">%s</a><span class="sep">></span>',
				get_the_permalink( $actus),
				strip_tags(get_the_title($actus))
			);
		endif;
		
		//Ajouter la catégorie d'article pour les posts single
		/*
		if(is_single()) {
			$term=ea_first_term();
			if(!empty($term)) {
				printf('<a href="%s?filtre_cat=%s">%s</a><span class="sep">></span>',
				get_the_permalink( $actus),
					$term->slug,
					$term->name
				);
			}
		}*/
	endif;


	//Afficher le titre de la page courante
	if(is_page()) : 
		//Afficher le titre de la page parente s'il y en a une, non cliquable
		$current=get_post(get_the_ID());
		$parent=$current->post_parent; 
		if($parent) :
			printf('<a href="%s" class="parent">%s</a><span class="sep">></span><span class="current">%s</span>',
				get_the_permalink($parent),
				strip_tags(get_the_title($parent)),
				strip_tags(get_the_title())
			);
		else :
			printf('<span class="current">%s</span>',
				strip_tags(get_the_title())
			);
		endif;
	elseif(is_single()):
		//Couper le titre après x carcatères
		$limite=90;
		$titre=strip_tags(get_the_title());
		if(strlen($titre) > $limite) {
			$espace=strpos($titre,' ',$limite);
			$titre_coupe=substr($titre,0,$espace);
			$titre_coupe.='&mldr;'; //on ajoute le caractère pour l'ellipse
		} else {
			$titre_coupe=$titre;
		}
		printf('<span class="current">%s</span>',
			$titre_coupe
		);
	elseif (is_category()) :  //archives catégories d'articles
		echo '<span class="current">'.strip_tags(single_cat_title( '', false )).'</span>';
	elseif (is_tag()) :  //archives tags d'articles
		echo '<span class="current">'.strip_tags(single_tag_title( '', false )).'</span>';
	elseif (is_home()) :
		$actus=get_option('page_for_posts'); //Traduit par PLL ✔️
		echo '<span class="current">'.strip_tags(get_the_title($actus)).'</span>';
	elseif (is_search()) :
		echo '<span class="current">Recherche : '.get_search_query().'</span>';
	elseif (is_404()) :
		echo '<span class="current">Page introuvable</span>';

	endif;

	//Fermer la balise du fil d'ariane
	echo '</p>';

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
	if(is_front_page(  )) {
		return;
	}

	if(!function_exists('get_field')) {
		return;
	}
	$image_id="";
	if(!$use_defaut) {
		if(!$page_id) {
			$page_id=get_the_ID();
		}
		$image_id=esc_attr(get_field('lapeyremag_banniere_image',$page_id));
	}
	
	if(!$image_id || $use_defaut) {
		$image_id=esc_attr(get_field('lapeyremag_bg_image','option'));//image par défaut
	}

	if(!empty($image_id)) {
		printf('<div class="page-banniere">');
			echo wp_get_attachment_image( $image_id, 'full',false,array('decoding'=>'async','loading'=>'eager'));
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