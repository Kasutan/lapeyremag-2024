<?php


/*************************
 * Actions sur le footer
 **************************/

add_action( 'tha_footer_top', 'kasutan_footer_icones',10 );
add_action( 'tha_footer_top', 'kasutan_footer_social',20 );
add_action( 'kasutan_main_footer', 'kasutan_footer_sitemap',10 );
add_action( 'kasutan_main_footer', 'kasutan_footer_confidentialite',20 );
add_action( 'tha_footer_bottom', 'kasutan_footer_paiement',10 );
add_action( 'tha_footer_bottom', 'kasutan_footer_liens',20 );


/*************************
 * Icones avantages
 **************************/
function kasutan_footer_icones() {
	if(!function_exists('have_rows') || !have_rows('lapeyre_footer_icones','options')) {
		return;
	}
	echo '<ul class="avantages">';
		while(have_rows('lapeyre_footer_icones','options')) : the_row();
			$image=esc_attr(get_sub_field('image'));
			$titre=wp_kses_post(get_sub_field('titre'));
			$sous_titre=wp_kses_post(get_sub_field('sous-titre'));

			printf('<li class="avantage">%s <strong>%s</strong> <span>%s</span></li>',wp_get_attachment_image($image),$titre,$sous_titre);

		endwhile;
	echo '</ul>';
}

/*************************
 * Liens vers les réseaux sociaux
 **************************/
function kasutan_footer_social() {
	if(!function_exists('get_field') || !function_exists('kasutan_picto')) {
		return;
	}
	$social=get_field('lapeyre_footer_social','option');
	if(empty($social)) {
		return;
	}

	echo '<div class="social">';
		if($social['titre']) printf('<p class="titre">%s</p>',wp_kses_post($social['titre']));
		if($social['sous-titre']) printf('<p class="sous-titre">%s</p>',wp_kses_post($social['sous-titre']));

		echo '<nav class="reseaux">';
		$reseaux=['facebook','pinterest','instagram','youtube'];
		foreach($reseaux as $reseau) {
			if($social[$reseau]) printf('<a href="%s" class="%s" title="Suivez-nous sur %s" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_url($social[$reseau]),
				$reseau,
				ucfirst($reseau),
				kasutan_picto(array('icon'=>$reseau))
			);
		}
		echo '</nav>';

	echo '</div>';
}

/*************************
 * Sitemap
 **************************/
function kasutan_footer_sitemap() {
	if(!function_exists('get_field')) {
		return;
	}

	//Elements obtenus par API
	$sitemap=get_option('lapeyre_headers_sitemap',false);
	$produits=get_option('lapeyre_headers_produits',false);
	$nav_header=get_option('lapeyre_headers_navigation1',false); //Pour le lien promotion

	//Titres statiques saisis dans page option en BO
	$titres=get_field('lapeyre_footer_cols','options');

	//Pour afficher le sitemap on a besoin des titres et d'au moins une colonne
	if((empty($sitemap) && empty($produits)) || empty($titres)) {
		return;
	}
	
	echo '<div class="sitemap">';

	
	for($i=1;$i<=4;$i++) {
		$index_titre='titre_'.$i;
		$index_col=$i-2;
		if(empty($titres[$index_titre])) {
			continue;
		}
		$titre=wp_kses_post($titres[$index_titre]);

		printf('<div class="col-wrap">');
			//Titre simple en desktop
			printf('<p class="titre-col">%s</p>',$titre);

			//Bouton pour volet escamotable en mobile
			printf('<button class="toggle-col" id="sitemap-toggle-%s" aria-expanded="true" aria-controls="sitemap-%s"><span>%s</span>',$i,$i,$titre);
				if(function_exists('kasutan_picto')) {
					echo kasutan_picto(array('icon'=>'chevron-bas'));
				}
			echo '</button>';

			

			printf('<nav class="col col-%s" id="sitemap-%s">',$i,$i,$i);

			if($i===1) {

				//Parmi la navigation du header, on affiche ici ceux dont la classe est Promo
				foreach($nav_header as $lien) {
					if($lien->class==="Promo") {
						printf('<a href="%s" class="Promo">%s</a>',$lien->href,$lien->text);
					}
				}

				if(!empty($produits)) {
					foreach($produits as $produit) {
						printf('<a href="%s">%s</a>',$produit->permalink,$produit->name);
					}
				}

			} else if(!empty($sitemap[$index_col])){
				$liens=$sitemap[$index_col];

				foreach($liens as $lien) {
					$attr='';

					if($lien->target !== "None") {
						$attr='target="_blank" rel="noopener noreferrer"';
					}

					printf('<a href="%s" %s>%s</a>',$lien->href,$attr,$lien->text);
				}
			}


			


			echo '</nav>';


		echo '</div>';

		$col1=false;
	}


	echo '</div>';
}

/*************************
 * Message sur la confidentialité et lien espace pro
 **************************/
function kasutan_footer_confidentialite() {
	if(!function_exists('get_field')) {
		return;
	}

	$confidentialite=wp_kses_post(get_field('lapeyre_footer_confidentialite','option'));
	$espace_pro=get_field('lapeyre_footer_pro','option'); 

	echo '<div class="main-2">';
	if($confidentialite) {
		echo '<div class="politique" id="politique">';
		echo $confidentialite;

		//TODO : vérifier que la classe suffit pour déclencher l'ouverture du volet one trust
		?>
		<a href="#politique" class="ot-sdk-show-settings cookies">
			<span>Paramétrer les cookies</span>
			<svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M5.70832 4.99588C5.70832 5.10256 5.69181 5.20925 5.65878 5.30772C5.62575 5.4062 5.56795 5.49647 5.48538 5.57854L1.66234 9.36994C1.51371 9.51766 1.3238 9.58331 1.10085 9.58331C0.877912 9.58331 0.679741 9.50125 0.531113 9.34532C0.382485 9.1894 0.299914 9.00886 0.299914 8.77087C0.299914 8.53288 0.374228 8.34413 0.531113 8.19641L3.75139 4.99588L0.506341 1.77893C0.357713 1.623 0.291656 1.43425 0.291656 1.21268C0.291656 0.991102 0.374227 0.802352 0.531112 0.646428C0.687998 0.490505 0.877911 0.416647 1.10911 0.416647C1.34031 0.416647 1.53848 0.490505 1.68711 0.646428L5.48538 4.42142C5.56795 4.50349 5.62575 4.59376 5.65878 4.69224C5.69181 4.79071 5.70832 4.88919 5.70832 5.00408L5.70832 4.99588Z" fill="#212121"/>
			</svg>
		</a>
		<?php
		echo '</div>';
	}

	if($espace_pro) {
		printf('<a href="%s" class="bouton pro">%s</a>',
			esc_url($espace_pro['url']),
			wp_kses_post($espace_pro['title'])
		);
	}
	
	echo '</div>';
}

/*************************
 * Logos paiement
 **************************/
function kasutan_footer_paiement() {
	if(!function_exists('get_field')) {
		return;
	}
	$logos=get_field('lapeyre_footer_paiement','options');
	if(empty($logos)) {
		return;
	}

	echo '<div class="paiement"><div class="logos-wrap">';
		foreach($logos as $logo) {
			echo wp_get_attachment_image($logo, 'small');
		}
		echo '<div class="spacer"></div>';
	echo '</div></div>';
}


/*************************
 * Copyright et Liens
 **************************/
function kasutan_footer_liens() {
	//Elements obtenus par API
	$liens=get_option('lapeyre_headers_footer_links',false);

	echo '<div class="bottom-footer"><div class="liens-wrap">';
		printf('<span><strong>&copy;%s Lapeyre</strong></span>',date('Y'));
		if(!empty($liens)) {
			foreach($liens as $lien) {
				$attr='';

				if($lien->target != "none") {
					$attr='target="_blanc" rel="noopener noreferrer"';
				}

				printf('<a href="%s" %s>%s</a>',$lien->href,$attr,$lien->text);
			}
		}

	echo '</div></div>';
}