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
$main=esc_attr(get_field('article_principal'));
$posts=get_field('posts');
if(!empty($main)) :
	printf('<section class="acf acf-featured %s">', $className);

		printf('<h2 class="titre-section">%s</h2>',$titre);
		
		//On affiche d'abord l'article principal
		global $post;
		$post=$main;
		get_template_part( 'partials/archive', null,array('balise_title'=>'h3','index'=>$count,'featured'=>true) );
		wp_reset_postdata();

		//Puis les suivants sous forme de slider
		if(function_exists('kasutan_affiche_slider')) {
			kasutan_affiche_slider($posts);
		}

	echo '</section>';
endif;
	