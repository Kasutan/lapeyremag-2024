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

// Image bannière 
add_action( 'tha_entry_top', 'kasutan_actus_banniere', 5 );


// Breadcrumbs 
add_action( 'tha_entry_top', 'kasutan_fil_ariane', 8 );



//Titre déplacé dans le contenu
remove_action( 'tha_entry_top', 'ea_entry_title' );


//Image et titre insérés avant le contenu pour mise en page grille
add_action('tha_entry_content_before', 'kasutan_single_entry_content_before');
function kasutan_single_entry_content_before() {
	if(get_post_type() !== 'post') {
		return;
	}

	$infos_cat=array('nom'=>'Autres','couleur'=>'rouge');

	if(function_exists('kasutan_get_cat_et_couleur')) {
		$infos_cat=kasutan_get_cat_et_couleur();
	}

	//Wrapper pour le bandeau
	echo '<div class="bandeau">';

	//Ajouter l'image et le nom de la catégorie
	if(has_post_thumbnail()) {
		echo '<div class="thumbnail">';
			printf('<a href="%s" class="cat has-%s-background-color">%s</a>',$infos_cat['url'],$infos_cat['couleur'],$infos_cat['nom']);
			the_post_thumbnail( 'large');
		echo '</div>';
	}
	
	printf('<div class="col has-%s-background-color">',$infos_cat['couleur']); //wrapper pour la 1ère colonne

	//titre 
	printf('<h1 class="single-title">%s</h1>',get_the_title());

	//date 
	//printf('<p class="single-date">%s</p>', get_the_date('d F Y'));

}

add_action('tha_entry_content_after','kasutan_single_entry_content_after');
function kasutan_single_entry_content_after(){
	//fermer wrapper de la la 1ère colonne
	echo '</div> <!--end .col-->';

	//fermer le wrapper du bandeau
	echo '</div>';

	//Et glisser un lien vers toutes les actualités
	$actus=get_option( 'page_for_posts' ) ;
	$label="Retour aux actualités";
	
	printf('<div class="retour"><a href="%s">%s</a></div>',get_the_permalink($actus),$label);
}


// Build the page
require get_template_directory() . '/index.php';
