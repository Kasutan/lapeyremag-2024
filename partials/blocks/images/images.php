<?php 
/**
* Template pour le bloc images sur-mesure
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';

$images=get_field('images');
$className.=' '.esc_attr(get_field('largeur'));

if(!empty($images)) :

	$count=count($images);
	printf('<div class="acf images nb-%s %s">', $count, $className);
		foreach($images as $image) :
			echo wp_get_attachment_image(esc_attr($image), 'large');
		endforeach;

	echo '</div>';

endif;