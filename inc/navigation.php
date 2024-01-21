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
		printf('<button id="ouvrir-produits-desktop" class="anim-trait" aria-controls="menu-produits-desktop" aria-expanded="false"><span class="texte" data-text="Produits">Produits</span><span class="picto">%s</span></button>',kasutan_picto(array('icon'=>'chevron-bas')));
		kasutan_affiche_navigation1();
		echo '<div class="boutons">';
			kasutan_affiche_boutons_header(true);
		echo '</div>';

	echo '</nav>';

	kasutan_affiche_menu_produits('desktop');

	
}


function kasutan_mobile_nav() {
	?>
	<div id="overlay-mobile"></div>
	<button class="menu-toggle picto" id="volet-ouvrir" aria-controls="volet-navigation"  aria-label="Ouvrir le volet de navigation">
		<?php echo kasutan_picto(array('icon'=>'menu', 'class'=>'menu', 'size'=>'28'));?>
	</button>
	<div class="volet-navigation"  id="volet-navigation">
		<button class="menu-toggle picto fermer-menu" id="menu-close"  aria-label="Fermer le volet de navigation">
			<?php echo kasutan_picto(array('icon'=>'close', 'class' => 'fermer-menu','size'=>'28'));?>
		</button>
		<nav class="menu-mobile">
		<?php
		printf('<button id="ouvrir-produits-mobile">Produits<span class="picto">%s</span></button>',kasutan_picto(array('icon'=>'chevron-droite')));
		kasutan_affiche_navigation1();
		echo '</nav>';
		echo '<div class="boutons">';
			kasutan_affiche_boutons_header();
		echo '</div>';

		kasutan_affiche_menu_produits('mobile');

	echo '</div>'; //Fin volet navigation
}

function kasutan_affiche_navigation1() {
	//Elements obtenus par API
	$liens=get_option('lapeyre_headers_navigation1',false);
	if(!empty($liens)) {
		foreach($liens as $lien) {
			$attr='';

			/*
			Target non fourni pour ces objets
			if($lien->target != "none") {
				$attr='target="_blank" rel="noopener noreferrer"';
			}*/

			printf('<a href="%s" class="%s anim-trait" %s><span class="texte" data-text="%s"> %s</span></a>',$lien->href,strtolower($lien->class),$attr,$lien->text,$lien->text);
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

function kasutan_affiche_menu_produits($contexte) {
	//Elements obtenus par API
	$produits=get_option('lapeyre_headers_produits',false);
	//Element statique : champ ACF
	$affaires=get_option('options_lapeyre_header_affaires',false);

	if(!empty($produits)) {
		if($contexte==="desktop") echo '<div id="overlay-produits"></div>';

		printf('<div id="menu-produits-%s" class="menu-produits %s">',$contexte,$contexte);
			echo '<div class="niveau niveau-1">';
				if($contexte=="desktop") {
					echo '<p class="titre-menu">Produits</p>';
				} else {
					kasutan_affiche_top_menu_produit('Produits');
				}
				foreach($produits as $cat) {
					printf('<button aria-controls="panneau-%s-%s" class="produit niv1"><span class="texte">%s</span>%s</button>',$contexte,$cat->uniqueID,$cat->name,kasutan_picto(array('icon'=>'chevron-droite')));

					kasutan_affiche_panneau_menu($contexte,$cat->uniqueID,$cat->permalink,$cat->children,2,$cat->name);

				}

				if($affaires) {
					printf('<a href="%s" class="lien-extra rouge" target="%s" rel="noopener noreferrer"><span class="texte">%s</span>%s</a>',
						esc_url($affaires['url']),
						esc_attr($affaires['target']),
						wp_kses_post( $affaires['title'] ),
						kasutan_picto(array('icon'=>'chevron-droite'))
					);
				}
			echo '</div>';
			
			if($contexte==="desktop") printf('<button id="fermer-produits-desktop"  aria-label="Fermer le menu produits">%s</button>', kasutan_picto(array('icon'=>'close')));
		echo '</div>'; //.menu-produits
	}

}

function kasutan_affiche_panneau_menu($contexte,$uniqueID,$permalink,$children,$niveau,$name) {
	if($niveau===2) {
		$label="Découvrir l'univers";
	} else if($niveau===3) {
		$label="Voir tous les produits";
	}
	printf('<div class="niveau niveau-%s" id="panneau-%s-%s">',$niveau,$contexte,$uniqueID);
		if($contexte==='mobile') {
			kasutan_affiche_top_menu_produit($name);
		}
		if($niveau===2) {
			$label="Découvrir l'univers";

			foreach($children as $cat) {
				$uniqueID2=rand(0,10000000); //Regénérer un ID vraiment unique car la sous-catégorie peut être affichée dans plusieurs univers (ex portes de garage)

				printf('<button aria-controls="panneau-%s-%s" class="produit niv2"><span class="texte">%s</span>%s</button>',$contexte,$uniqueID2,$cat->name,kasutan_picto(array('icon'=>'chevron-droite')));

				kasutan_affiche_panneau_menu($contexte,$uniqueID2,$cat->permalink,$cat->children,3,$cat->name);
			}

		} else if($niveau===3) {
			$label="Voir tous les produits";
			echo '<div class="liste-produits">';
				foreach($children as $cat) {
					printf('<a href="%s" class="lien-produit"><span class="texte">%s</span></a>',$cat->permalink,$cat->name);
				}

			echo '</div>';
		}

		
		printf('<a href="%s" class="lien-extra"><span class="texte">%s</span>%s</a>',
				$permalink,
				$label,
				kasutan_picto(array('icon'=>'chevron-droite'))
			);
	echo '</div>';

}

function kasutan_affiche_top_menu_produit($label) {
	echo '<div class="top-menu">';
		printf('<button class="menu-toggle fermer-panneau" aria-label="Fermer ce panneau">%s</button>',kasutan_picto(array('icon'=>'fleche-gauche')));
		printf('<p class="titre-menu">%s</p>',$label);
		printf('<button class="menu-toggle avec-picto fermer-menu" aria-label="Fermer le volet de navigation">%s</button>',kasutan_picto(array('icon'=>'close')));
	echo '</div>';
}