<?php 
/**
* Template pour le bloc nav-page-type
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
$exclure=get_field('exclure');

if(have_rows('lapeyre_types_articles','option')) :
	printf('<section class="acf nav-page-type %s">', $className);

		printf('<h2 class="titre-section">%s</h2>',$titre);
		if($sous_titre) printf('<p class="sous-titre">%s</p>',$sous_titre);

		if(empty($exclure)) {
			$total=3;
		} else {
			$total=2;
		}
		$index=0;
		printf('<div class="slider-wrap" data-total="%s" data-active="0">',$total);
		echo '<div class="slider-drag"><div class="slider">';
			while(have_rows('lapeyre_types_articles','option')) : the_row();

				$key=esc_attr(get_sub_field('key'));
				if(empty($exclure) || $exclure!==$key) {
					$page=get_sub_field('page');
					$picto=esc_attr(get_sub_field('picto'));
					printf('<a href="%s" class="type slide">%s %s</a>',
						get_the_permalink($page),
						wp_get_attachment_image($picto),
						get_the_title($page)
					);
					
				}

			endwhile;
			echo '</div></div>';
			if(function_exists('kasutan_affiche_nav_slider')) {
				kasutan_affiche_nav_slider($total);
			}
		echo '</div>';//.slider-wrap
	echo '</section>';
endif;
	