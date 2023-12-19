<?php 
/**
* Template pour le bloc Carrousel de livres
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/

if(function_exists('get_field')) : 

	if(array_key_exists('className',$block)) {
		$className=esc_attr($block["className"]);
	} else $className='';


	if(have_rows('livres')) : 
		$titre=wp_kses_post( get_field('titre') );

		printf('<section class="carrousel-livres %s">',$className);
			
			if($titre) printf('<h2>%s</h2>',$titre);

			echo '<div class="owl-carousel">';
			while(have_rows('livres')) : the_row();
				$texte=wp_kses_post(get_sub_field('texte'));
				$image_id=esc_attr(get_sub_field('image'));
				$cible=esc_url(get_sub_field('cible'));


				
				echo '<div class="slide">';
					if($cible) {
						printf('<a href="%s">',$cible);
					} 
					echo wp_get_attachment_image( $image_id, 'medium');
					if($cible) {
						printf('</a>');
					} 
					if($texte) {
						if($cible) {
							printf('<a href="%s" class="texte">%s</a>',$cible,$texte);
						} else {
							printf('<p class="texte">%s</p>',$texte);
						}
					}
					
				
				echo '</div>';
				
			endwhile;
			echo '</div>';
	
		echo '</section>';
	endif;

endif;