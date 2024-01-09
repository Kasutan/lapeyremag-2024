<?php 
/**
* Template pour le bloc featured
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
$posts=get_field('posts');
if(!empty($posts) && function_exists('kasutan_affiche_slider')) :
	printf('<section class="acf acf-featured %s">', $className);

		printf('<h2 class="titre-section">%s</h2>',$titre);
		kasutan_affiche_slider($posts,true);//Paramètre featured=true

	echo '</section>';
endif;
	