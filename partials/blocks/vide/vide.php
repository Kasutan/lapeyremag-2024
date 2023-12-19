<?php 
/**
* Template pour le bloc vide
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

printf('<section class="acf vide %s">', $className);

	printf('<h2 class="titre-section">%s</h2>',$titre);


echo '</section>';
	