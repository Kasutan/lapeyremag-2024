<?php
/**
 * Navigation
**/

function kasutan_desktop_nav() {
	echo '<nav id="site-navigation" class="nav-main" aria-label="menu principal">';
		printf('<button id="ouvrir-produits-desktop" class="anim-trait" aria-controls="menu-produits-desktop" aria-expanded="false"><span class="texte" data-text="Produits">Produits</span><span class="picto">%s</span></button>',kasutan_picto_simple('chevron-bas'));
		kasutan_affiche_navigation1();
		echo '<div class="boutons">';
			kasutan_affiche_boutons_header(true);
		echo '</div>';

	echo '</nav>';

	kasutan_affiche_menu_produits('desktop');

	
}


function kasutan_volet_mobile_nav() {
	?>
	<div id="overlay-mobile"></div>
	<div class="volet-navigation"  id="volet-navigation">
		<button class="menu-toggle picto fermer-menu" id="menu-close"  aria-label="Fermer le volet de navigation">
			<?php echo kasutan_picto(array('icon'=>'close', 'class' => 'fermer-menu','size'=>'28'));?>
		</button>
		<nav class="menu-mobile">
		<?php
		printf('<button id="ouvrir-produits-mobile">Produits<span class="picto">%s</span></button>',kasutan_picto_simple('chevron-droite'));
		kasutan_affiche_navigation1();
		echo '</nav>';
		echo '<div class="boutons">';
			kasutan_affiche_boutons_header();
		echo '</div>';

		kasutan_affiche_menu_produits('mobile');

	echo '</div>'; //Fin volet navigation
}



function kasutan_bouton_mobile_nav() {
	?>
	<button class="menu-toggle picto" id="volet-ouvrir" aria-controls="volet-navigation"  aria-label="Ouvrir le volet de navigation">
		<?php echo kasutan_picto(array('icon'=>'menu', 'class'=>'menu', 'size'=>'28'));?>
	</button>
	<?php
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
		$attr='';
		if($bouton1['target'] === "_blank") {
			$attr='target="_blank" rel="noopener noreferrer"';
		}
		printf('<a href="%s" class="bouton ecoute" %s>%s<span>%s</span></a>',
			esc_url($bouton1['url']),
			$attr,
			kasutan_picto_simple('ecoute'),
			wp_kses_post( $bouton1['title'] )
		);
	}

	if($bouton2) {
		$attr='';
		if($bouton2['target'] === "_blank") {
			$attr='target="_blank" rel="noopener noreferrer"';
		}
		printf('<a href="%s" class="bouton bleu" %s>%s<span>%s</span></a>',
			esc_url($bouton2['url']),
			$attr,
			kasutan_picto_simple('projet'),
			wp_kses_post( $bouton2['title'] )
		);
	}
}

function kasutan_affiche_menu_produits($contexte) {
	require get_template_directory().'/partials/menu_produits_'.$contexte.'.html';
}


//Cette fonction est appelée dans les 2 contextes desktop et mobile par la fonction Produits:Update() du plugin Lapeyre Headers & Footers, qui est elle-même déclenchée par un événement cron lapeyre_headers_update_cache
function kasutan_prepare_html_menu_produits($contexte) {
	ob_start();
	//Elements obtenus par API
	$produits=get_option('lapeyre_headers_produits',false);
	//Element statique : champ ACF
	$affaires=get_option('options_lapeyre_header_affaires',false);

	if(!empty($produits)) {
		if($contexte==="desktop") echo '<div id="overlay-produits"></div>';

		printf('<div id="menu-produits-%s" class="menu-produits %s">',$contexte,$contexte);
		
			if($contexte==="desktop") printf('<button id="fermer-produits-desktop"  aria-label="Fermer le menu produits">%s</button>', kasutan_picto_simple('close'));

			$lien_affaires='';
			if($affaires) {
				$attr='';
				if($affaires['target'] === "_blank") {
					$attr='target="_blank" rel="noopener noreferrer"';
				}
				$lien_affaires=sprintf('<a href="%s" class="lien-extra rouge" %s><span class="texte">%s</span>%s</a>',
					esc_url($affaires['url']),
					$attr,
					wp_kses_post( $affaires['title'] ),
					kasutan_picto_simple('chevron-droite')
				);
			}

			echo '<div class="niveau niveau-1">';
				if($contexte=="desktop") {
					echo '<p class="titre-menu">Produits</p>';
				} else {
					kasutan_affiche_top_menu_produit('Produits');
					echo $lien_affaires; //lien bonnes affaires affiché en haut du panneau en mobile
				}
				foreach($produits as $cat) {
					printf('<button aria-controls="panneau-%s-%s" class="produit niv1"><span class="texte">%s</span>%s</button>',$contexte,$cat->uniqueID,$cat->name,kasutan_picto_simple('chevron-droite'));

					kasutan_affiche_panneau_menu($contexte,$cat->uniqueID,$cat->permalink,$cat->children,2,$cat->name);

				}

				if($contexte=="desktop") {
					echo $lien_affaires; //lien bonnes affaires affiché en bas du panneau en desktop
				}
				
			echo '</div>';
			
			
		echo '</div>'; //.menu-produits
	}

	$menu_produits=ob_get_clean();
	file_put_contents(get_template_directory().'/partials/menu_produits_'.$contexte.'.html',$menu_produits);

}

function kasutan_affiche_panneau_menu($contexte,$uniqueID,$permalink,$children,$niveau,$name) {
	$url_produits=esc_url(get_option('options_lapeyre_cible_produits',false)); // option ACF
	if(empty($url_produits)) {
		$url_produits="https://www.lapeyre.fr/produits";
	}

	if($niveau===2) {
		$label="Découvrir l'univers";
	} else if($niveau===3) {
		$label="Voir tous les produits";
	}
	$lien_extra='';
	$lien_extra=sprintf('<a href="%s" class="lien-extra"><span class="texte">%s</span>%s</a>',
		$permalink,
		$label,
		kasutan_picto_simple('chevron-droite')
	);

	printf('<div class="niveau niveau-%s" id="panneau-%s-%s">',$niveau,$contexte,$uniqueID);
		if($contexte==='mobile') {
			kasutan_affiche_top_menu_produit($name);
			echo $lien_extra; //Lien extra en haut du panneau en mobile
		}
		if($niveau===2) {
			foreach($children as $cat) {
				$uniqueID2=rand(0,10000000); //Regénérer un ID vraiment unique car la sous-catégorie peut être affichée dans plusieurs univers (ex portes de garage)

				printf('<button aria-controls="panneau-%s-%s" class="produit niv2"><span class="texte">%s</span>%s</button>',$contexte,$uniqueID2,$cat->name,kasutan_picto_simple('chevron-droite'));

				kasutan_affiche_panneau_menu($contexte,$uniqueID2,$cat->permalink,$cat->children,3,$cat->name);
			}

		} else if($niveau===3) {
			echo '<div class="liste-produits">';
				foreach($children as $cat) {
					printf('<a href="%s%s" class="lien-produit"><span class="texte">%s</span></a>',$url_produits,$cat->permalink,$cat->name);
				}

			echo '</div>';
		}

		if($contexte==='desktop') {
			echo $lien_extra; //Lien extra en bas du panneau en mobile
		}
		
	echo '</div>';

}

function kasutan_affiche_top_menu_produit($label) {
	echo '<div class="top-menu">';
		printf('<button class="menu-toggle fermer-panneau" aria-label="Fermer ce panneau">%s</button>',kasutan_picto_simple('fleche-gauche'));
		printf('<p class="titre-menu">%s</p>',$label);
		printf('<button class="menu-toggle avec-picto fermer-menu" aria-label="Fermer le volet de navigation">%s</button>',kasutan_picto_simple('close'));
	echo '</div>';
}