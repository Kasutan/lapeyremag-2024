<?php 
/**
* Template pour le bloc populaires
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
$nb=esc_attr(get_field('nombre'));

$query=new WP_Query(array(
	'posts_per_page'=>$nb,
	'orderby'=>'meta_value_num',
	'meta_type'=>'numeric',
	'meta_key'=>'lapeyre_vues',
	'fields'=>'ids'
));

$posts=$query->posts;

if(function_exists('kasutan_affiche_slider') && !empty($posts)) :
	printf('<section class="acf-populaires %s">', $className);

		printf('<h2 class="titre-section">%s</h2>',$titre);
		kasutan_affiche_slider($posts,'h3');

	echo '</section>';
endif;
	