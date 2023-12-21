<?php
/**
 * Helper Functions
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Duplicate 'the_content' filters
global $wp_embed;
add_filter( 'ea_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'ea_the_content', array( $wp_embed, 'autoembed'     ), 8 );
add_filter( 'ea_the_content', 'wptexturize'        );
add_filter( 'ea_the_content', 'convert_chars'      );
add_filter( 'ea_the_content', 'wpautop'            );
add_filter( 'ea_the_content', 'shortcode_unautop'  );
add_filter( 'ea_the_content', 'do_shortcode'       );


/**
 * Conditional CSS Classes
 *
 * @param string $base_classes, classes always applied
 * @param string $optional_class, additional class applied if $conditional is true
 * @param bool $conditional, whether to add $optional_class or not
 * @return string $classes
 */
function ea_class( $base_classes, $optional_class, $conditional ) {
	return $conditional ? $base_classes . ' ' . $optional_class : $base_classes;
}

/**
 * Récupérer des infos sur les catégories d'un article
 */
function kasutan_get_infos_cats($post_id='',$avec_couleur=false) {
	if(empty($post_id)) {
		$post_id=get_the_ID();
	}
	if(get_post_type($post_id)!=='post') {
		return false;
	}

	$categories = get_the_category();
	if(empty($categories)) {
		return false;
	}

	$reponse=array();

	foreach($categories as $term) {
		$name=$term->name;
		$term_id=$term->term_id;
		if(strpos($name,'OLD') > 0) {
			continue;
		}
		
		//S'il y a plusieurs catégories parentes associées à cet article, on écrasera les infos avec la dernière stockée.

		$parent_id=$term->parent;

		if($parent_id) {
			$reponse['parent_id']=$parent_id;
			$reponse['parent_name']=get_cat_name($parent_id);
			$reponse['parent_link']=get_category_link($parent_id);
			$reponse['child_name']=$name;
			$reponse['child_link']=get_category_link($term_id);
		} else if(!isset($reponse['child_name'])) {
			//Si on n'a pas déjà trouvé un parent et un enfant, on stocke au moins le parent
			//Si l'article est uniquement dans une catégorie parente, on pourra quand même afficher quelque chose
			//Si on trouve une catégorie de niveau 2 dans la suite du foreach, ces infos seront écrasées
			$reponse['parent_id']=$term_id;
			$reponse['parent_name']=$name;
			$reponse['parent_link']=get_category_link($term_id);
			$reponse['child_name']=false;
			$reponse['child_link']=false;
		}

	}

	$reponse['couleur']=false;

	//TODO créer champ ACF et tester
	if($avec_couleur && $reponse['parent_id'] && function_exists('get_field')) {
		//on a demandé aussi la couleur associée à la catégorie parente
		$couleur=get_field('couleur','category_'.$reponse['parent_id']);
		if($couleur) {
			$reponse['couleur']=false;
		}
	}

	return $reponse;

}


/**
 *  Background Image Style
 *
 * @param int $image_id
 * @return string $output
 */
function ea_bg_image_style( $image_id = false, $image_size = 'full' ) {
	if( !empty( $image_id ) )
		return ' style="background-image: url(' . wp_get_attachment_image_url( $image_id, $image_size ) . ');"';
}

/* Decor svg*/

function kasutan_affiche_decor_svg($nom) {
	$icon_path = get_theme_file_path( '/icons/' . $nom . '.svg' );
	if( file_exists( $icon_path ) ) {
		printf('<div class="decor">%s</div>',file_get_contents( $icon_path ));
	}
}

/**
 * Get Icon
 * This function is in charge of displaying SVG icons across the site.
 *
 * Place each <svg> source in the /assets/icons/ directory, without adding
 * both `width` and `height` attributes, since these are added dynamically,
 * before rendering the SVG code.
 *
 * All icons are assumed to have equal width and height, hence the option
 * to only specify a `$size` parameter in the svg methods.
 *
 */
function kasutan_picto( $atts = array() ) {

	$atts = shortcode_atts( array(
		'icon'	=> false,
		'size'	=> 16,
		'class'	=> false,
		'label'	=> false,
	), $atts );

	if( empty( $atts['icon'] ) )
		return;

	$icon_path = get_theme_file_path( '/icons/' . $atts['icon'] . '.svg' );
	if( ! file_exists( $icon_path ) )
		return;

		$icon = file_get_contents( $icon_path );

		$class = 'svg-icon';
		if( !empty( $atts['class'] ) )
			$class .= ' ' . esc_attr( $atts['class'] );

		if( false !== $atts['size'] ) {
			$repl = sprintf( '<svg class="' . $class . '" width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $atts['size'], $atts['size'] );
			$svg  = preg_replace( '/^<svg /', $repl, trim( $icon ) ); // Add extra attributes to SVG code.
		} else {
			$svg = preg_replace( '/^<svg /', '<svg class="' . $class . '"', trim( $icon ) );
		}
		$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
		$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

		if( !empty( $atts['label'] ) ) {
			$svg = str_replace( '<svg class', '<svg aria-label="' . esc_attr( $atts['label'] ) . '" class', $svg );
			$svg = str_replace( 'aria-hidden="true"', '', $svg );
		}

		return $svg;
}

/**
 * Has Action
 *
 */
function ea_has_action( $hook ) {
	ob_start();
	do_action( $hook );
	$output = ob_get_clean();
	return !empty( $output );
}




/**
* Récupérer l'ID d'une page - stockée dans une option ACF.
*/

function kasutan_get_page_ID($nom) {
	if (!function_exists('get_field')) {
		return;
	}

	$page=get_field($nom,'option');

	return $page;
}

/**
* Convertir une zone de texte en liste à puces.
*/
function kasutan_make_list($text) {
	$array=explode('<br />',$text);
	ob_start();
		echo '<ul>';
		foreach($array as $item) {
			printf('<li>%s</li>',$item);
		}
		echo '</ul>';
	return ob_get_clean();
}
