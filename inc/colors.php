<?php
/**
 * Register Custom color palette for Gutenberg editor
 *
 * Should be the colors from css/colors.css.
 *
 * @package kasutan
 */

$colors_sass=array('blanc'=>'#fff', 
'rouge' =>'#C3472F',
'rouge-texte' =>'#BB2833',
'rouge-fond' =>'rgba(195,71,47,0.05)',
'orange' =>'#EB910A', 
'orange-imp' =>'#F7B12D', 
'orange-cadre' =>'rgba(246,176,45,0.3)', 
'orange-fond' =>'rgba(247,177,45,0.1)', 
'orange-input' =>'#FFF6EB', 
'bleu'=>"#38A4E0",
'cyan'=>"#7cc9c1",
'vert' =>'#5D8E5A',
'marron'=>"#614D40",
'gris-1' =>'rgba(0,0,0,0.9)',
'gris-2' =>'#1E1E1E',
'gris-fond' =>'#F3F3F3',
'gris-input' =>'#D9D9D9',
'noir' =>'#000000');

$colors_editor=array();

foreach($colors_sass as $nom=>$couleur) {
	$colors_editor[]=array('name'=>$nom,'slug'=>$nom,'color'=>$couleur);
}

add_theme_support( 'editor-color-palette',$colors_editor);