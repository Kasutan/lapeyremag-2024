<?php
/**
 * Register Custom color palette for Gutenberg editor
 *
 * Should be the colors from css/colors.css.
 *
 * @package kasutan
 */

$colors_sass=array('blanc'=>'#fff', 
'beige-clair'=>'#F9F4EE',
'beige'=>'#F4EADE',
'sauge'=>'#AAC5B5',
'rose'=>'#E6658B',
'orange'=>'#E6A74A',
'ocre'=>'#CC7364',
'jaune'=>'#D4CE5D',
'violet'=>'#8779B7',
'bleu'=>'#6EBACB',
'bleu-fonce'=>'#5D89A2',
'bleu-tres-fonce'=>'#3D6881',
'rouge'=>'#EB002D',
'gris-800'=>'#424242',
'texte'=>'#212121',
'noir-titre'=>'#1F1416',
'noir' =>'#000000');

$colors_editor=array();

foreach($colors_sass as $nom=>$couleur) {
	$colors_editor[]=array('name'=>$nom,'slug'=>$nom,'color'=>$couleur);
}

add_theme_support( 'editor-color-palette',$colors_editor);