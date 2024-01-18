<?php
/**
 * Navigation
**/


/* walker for primary menu sub nav */
class etcode_sublevel_walker extends Walker_Nav_Menu
{
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .=sprintf('<button class="ouvrir-sous-menu picto"><span class="screen-reader-text">Montrer ou masquer le sous-menu</span><span class="picto-angle">%s</span></button><ul class="sub-menu">',kasutan_picto(array('icon'=>'angle')) );
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "</ul>";
	}
}


/**
* Navigation
*
*/

function kasutan_desktop_nav() {
	echo '<nav id="site-navigation" class="nav-main" aria-label="menu principal">';
		printf('<a href="#" id="ouvrir-menu-desktop"><span>Produits</span>%s</a>',kasutan_picto(array('icon'=>'chevron-bas')));
		kasutan_affiche_navigation1();
		echo '<div class="boutons">';
			kasutan_affiche_boutons_header(true);
		echo '</div>';
	echo '</nav>';
	
}


function kasutan_mobile_nav() {
	?>
	<button class="menu-toggle picto" id="menu-toggle" aria-controls="volet-navigation"  aria-label="Ouvrir volet de navigation">
		<?php echo kasutan_picto(array('icon'=>'menu', 'class'=>'menu', 'size'=>'28'));?>
	</button>
	<div class="volet-navigation"  id="volet-navigation">
		<button class="menu-toggle picto" id="menu-close"  aria-label="Fermer volet de navigation">
			<?php echo kasutan_picto(array('icon'=>'close', 'class' => 'fermer-menu','size'=>'28'));?>
		</button>
		<nav class="menu-mobile">
		<?php
		printf('<a href="#" id="ouvrir-menu-mobile"><span>Produits</span>%s</a>',kasutan_picto(array('icon'=>'chevron-droite')));
		kasutan_affiche_navigation1();
		echo '</nav>';
		echo '<div class="boutons">';
			kasutan_affiche_boutons_header();
		echo '</div>';
	echo '</div>'; //Fin volet navigation
}

function kasutan_affiche_navigation1() {
	//Elements obtenus par API
	$liens=get_option('lapeyre_headers_navigation1',false);
	if(!empty($liens)) {
		foreach($liens as $lien) {
			$attr='';

			if($lien->target != "none") {
				$attr='target="_blank" rel="noopener noreferrer"';
			}

			printf('<a href="%s" class="%s" %s>%s</a>',$lien->href,strtolower($lien->class),$attr,$lien->text);
		}
	}
}


function kasutan_affiche_boutons_header() {
	if(!function_exists('get_field')) {
		return;
	}
	$bouton1=get_field('lapeyre_header_bouton_1','option');
	$bouton2=get_field('lapeyre_header_bouton_2','option');

	if($bouton1) {
		printf('<a href="%s" class="bouton ecoute" target="%s" rel="noopener noreferrer">%s<span>%s</span></a>',
			esc_url($bouton1['url']),
			esc_attr($bouton1['target']),
			kasutan_picto(array('icon'=>'ecoute')),
			wp_kses_post( $bouton1['title'] )
		);
	}

	if($bouton2) {
		printf('<a href="%s" class="bouton bleu" target="%s" rel="noopener noreferrer">%s<span>%s</span></a>',
			esc_url($bouton2['url']),
			esc_attr($bouton2['target']),
			kasutan_picto(array('icon'=>'projet')),
			wp_kses_post( $bouton2['title'] )
		);
	}

	
}