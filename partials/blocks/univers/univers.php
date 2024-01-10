<?php 
/**
* Template pour le bloc univers
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';


$titre=wp_kses_post( get_field('titre') );
$sous_titre=wp_kses_post( get_field('sous_titre') );
$univers=get_field('univers');
$nb=esc_attr(get_field('nombre'));
$label_types=wp_kses_post( get_field('label_types') );
$label_bouton=wp_kses_post( get_field('label_bouton') );

if(!empty($univers) && function_exists('kasutan_affiche_slider')) :
	printf('<section class="acf-univers %s">', $className);

		printf('<h2 class="titre-section">%s</h2>',$titre);
		if($sous_titre) printf('<p class="sous-titre">%s</p>',$sous_titre);
		
		foreach($univers as $term) {
			$term_id=$term->term_id;

			//Vérifier qu'on a bien un univers = une catégorie parente
			if($term->parent) {
				continue;
			}
			
			$posts=get_posts(array(
				'numberposts'=>$nb,
				'category'=>$term_id,
				'fields'=>'ids'
			));

			if($posts) {
				$lien=get_term_link($term);
				$bouton=sprintf('<a href="%s" class="bouton">%s</a>',$lien,$label_bouton);
				$types=get_term_children($term_id,'category');

				echo '<div class="univers">';

					echo '<div class="nav-univers">';

						printf('<h3 class="titre-univers">%s</h3>',$term->name);
					
						echo '<div class="nav-types">';
							printf('<p class="label-types">%s</p>',$label_types);
							foreach($types as $type_id) {
								$type=get_term($type_id);
								printf('<a href="%s" class="type">%s</a>',get_term_link($type),$type->name);
							}
						echo '</div>'; //.nav-types
					
						echo $bouton; //desktop uniquement
					
					echo '</div>';//.nav-univers
					
					kasutan_affiche_slider($posts);

					printf('<div class="bouton-wrap">%s</div>',$bouton); //mobile uniquement

				echo '</div>';//.univers
			}
		}

	echo '</section>';
endif;
	