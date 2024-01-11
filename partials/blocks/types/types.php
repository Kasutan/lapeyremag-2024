<?php 
/**
* Template pour le bloc types
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';



$cats=get_field('cats');
$nb=esc_attr(get_field('nombre'));
$label_bouton=wp_kses_post( get_field('label_bouton') );

if(!empty($cats) && function_exists('kasutan_affiche_slider')) :
	printf('<section class="acf-types %s">', $className);

		
		foreach($cats as $term) {
			$term_id=$term->term_id;

			$parent=$term->parent;
			//Vérifier qu'on a bien une sous-catégorie
			if(!$parent) {
				continue;
			}

			$parent_term=get_term($parent);
			$titre_cat=$parent_term->name;
			
			$posts=get_posts(array(
				'numberposts'=>$nb,
				'category'=>$term_id,
				'fields'=>'ids'
			));

			if($posts) {
				$lien=get_term_link($term);
				$bouton=sprintf('<a href="%s" class="bouton">%s</a>',$lien,$label_bouton);
				$types=get_term_children($term_id,'category');

				echo '<div class="section-cat">';

					echo '<div class="nav-cat">';

						printf('<h2 class="titre-cat">%s</h2>',$titre_cat);
					
						echo $bouton; //desktop uniquement
					
					echo '</div>';//.nav-cat
					
					kasutan_affiche_slider($posts,'h3');

					printf('<div class="bouton-wrap">%s</div>',$bouton); //mobile uniquement

				echo '</div>';//.univers
			}
		}

	echo '</section>';
endif;
	