<?php
/*************************
 * Actions sur le header
 **************************/

add_action('tha_header_before','kasutan_volet_mobile_nav',10);
//callback définie dans navigation.pho

add_action('tha_header_top','kasutan_header_bandeau',5);
//callback définie plus bas dans ce fichier

add_action('kasutan_main_header','kasutan_bouton_mobile_nav',5);
//callback définie dans navigation.php

add_action('kasutan_main_header','kasutan_header_logo',10);
//callback définie plus bas dans ce fichier

add_action('kasutan_main_header','kasutan_header_recherche',20);
//callback définie plus bas dans ce fichier

add_action('kasutan_main_header','kasutan_header_pictos',30);
//callback définie plus bas dans ce fichier

add_action('kasutan_main_header','kasutan_desktop_nav',40);
//callback définie dans navigation.php


/*************************
 * Définitions des callbacks
 **************************/
function kasutan_header_bandeau() {
	$bandeau=get_option('lapeyre_headers_bandeau',false);
	if($bandeau && is_array($bandeau)) :
		echo '<div class="bandeau-header"><div class="messages-wrap"><ul class="messages">';
		$messages=[];
		foreach($bandeau as $elem) :
			$show=true;
			$text=false;
			if($elem->text) {
				$text=wp_kses_post($elem->text);
			}

			if($elem->display_from) {
				$date_from=$elem->display_from;
				$time_from=$date_from->getTimestamp();
				$tz=$date_from->getTimezone(); // time zone de display_from

				$date_now=date_create_immutable("now", $tz); // maintenant dans la même time zone
				$time_now=$date_now->getTimestamp();

				//On compare les timestamps
				if ($time_now < $time_from) {
					//le moment de début d'affichage est dans le futur
					$show=false;
				}
			}
			
			if($elem->display_to) {
				$date_to=$elem->display_to;
				$time_to=$date_to->getTimestamp();
				$tz=$date_to->getTimezone(); // time zone de display_to

				$date_now=date_create_immutable("now", $tz); // maintenant dans la même time zone
				$time_now=$date_now->getTimestamp();

				if ($time_now > $time_to) {
					//le moment de fin d'affichage est dans le passé
					$show=false;
				}
			}

			if($show && !empty($text)) {
				$messages[]=$text;
			}

		endforeach;

		$total=count($messages);

		foreach($messages as $indice=>$text) {
			$classe="";
			$next=$indice+1;
			if($indice===0) {
				$classe="active";
			}
			printf('<li class="%s" data-position="%s" data-next="%s" >%s</li>',
				$classe,
				$indice,
				$next,
				$text
			);
		}

	
		echo '</ul></div>';
		if(function_exists('kasutan_picto')) {
			printf('<button id="fermer-bandeau" class="avec-picto fermer-bandeau"><span class="sr-text">Fermer le bandeau défilant</span>%s</button>',kasutan_picto(array('icon'=>'close')));
		}
		echo '</div>';
	endif;
}

function kasutan_header_logo() {
	//Url du site parent : option ACF BO 
	$url_home=get_option('options_lapeyre_cible_logo',false);
	if(!$url_home) {
		$url_home="https://www.lapeyre.fr";
	}
	//Url de l'image : appel API puis option WP
	$logo=get_option('lapeyre_headers_logo',false);
	if($logo && is_array($logo) && array_key_exists('url',$logo) && !empty($logo['url'])) {
		//Récupérer ratio image API
		$height=71; // fallback
		if($logo['height'] && $logo['width']) {
			$height=round($logo['height'] * 213 / $logo['width']);
		}
		printf('<a class="logo" href="%s"><img src="%s" alt="Logo Lapeyre" width="213" height="%s" /></a>',$url_home,$logo['url'],$height);
	}
}

function kasutan_header_recherche() {

	$aria="Formulaire de recherche dans l\'en-tête";
	$label="Saisissez vos mots-clés";
	$submit="Déclencher la recherche";
	//option acf bo
	$placeholder=get_option('options_lapeyre_recherche_placeholder',false);
	if(!$placeholder) {
		$placeholder="Rechercher un produit, un service, un tutoriel...";
	}
	$action="/";

	

	printf('<form role="search" method="get" class="search-form search-header" action="%s" aria-label="%s">
			<input class="search-submit" value="%s" type="submit">
			<label>
				<span class="screen-reader-text">%s</span>
				<input class="search-field" 
				placeholder="%s" value="" name="s" type="search">
			</label>
		</form>',
		$action,
		$aria,
		$submit,
		$label,
		$placeholder
	);
}

function kasutan_header_pictos() {
	if(!function_exists('get_field')) {
		return;
	}
	$pictos=get_field('lapeyre_topbar_pictos','options');
	if(empty($pictos) || !is_array($pictos)) {
		return;
	}
	echo '<div class="pictos-header">';
		if($pictos['label_magasin'] && $pictos['cible_magasin']) {
			printf('<a href="%s" class="magasin" title="%s">',$pictos['cible_magasin'],$pictos['label_magasin']);
			?>
				<svg focusable="false" aria-hidden="true" viewBox="0 0 20 20" width="20" height="20" fill="#000" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.73664 2.5C3.73664 1.94772 4.18435 1.5 4.73664 1.5H15.265C15.8173 1.5 16.265 1.94772 16.265 2.5C16.265 3.05228 15.8173 3.5 15.265 3.5H4.73664C4.18435 3.5 3.73664 3.05228 3.73664 2.5ZM14.2647 11.3231H12.7556V15.7345L12.7556 15.783C12.7556 16.1821 12.7557 16.5606 12.7221 16.8645C12.688 17.1728 12.6015 17.6254 12.2453 17.9837C11.888 18.343 11.435 18.4309 11.1263 18.4654C10.8232 18.4992 10.4459 18.4992 10.0496 18.4991L10.0008 18.4991H6.49137L6.44265 18.4991C6.04627 18.4992 5.66901 18.4992 5.36587 18.4654C5.05719 18.4309 4.60425 18.343 4.24689 17.9837C3.89065 17.6254 3.80417 17.1728 3.7701 16.8645C3.73653 16.5606 3.73658 16.1821 3.73663 15.783L3.73664 15.7345V11.323H3.73755C2.45587 11.3119 1.51334 10.1126 1.80936 8.86233L2.53348 5.80394C2.74707 4.90182 3.55261 4.26473 4.47967 4.26473H15.5211C16.4482 4.26473 17.2537 4.90182 17.4673 5.80394L18.1914 8.86233C18.4873 10.1121 17.5457 11.311 16.2647 11.323V17.4991C16.2647 18.0513 15.817 18.4991 15.2647 18.4991C14.7124 18.4991 14.2647 18.0513 14.2647 17.4991V11.3231ZM5.73664 11.3231V15.7345C5.73664 16.0821 5.73746 16.3178 5.74616 16.4898C5.91609 16.4983 6.14873 16.4991 6.49137 16.4991H10.0008C10.3435 16.4991 10.5761 16.4983 10.746 16.4898C10.7547 16.3178 10.7556 16.0821 10.7556 15.7345V11.3231H5.73664ZM10.7184 16.7412L10.7196 16.7368C10.7189 16.7399 10.7184 16.7413 10.7184 16.7412ZM10.993 16.4636C10.9959 16.4627 10.9975 16.4623 10.9976 16.4623C10.9976 16.4624 10.9962 16.4628 10.993 16.4636ZM5.49463 16.4623C5.4947 16.4623 5.49627 16.4627 5.49915 16.4636C5.49601 16.4628 5.49457 16.4624 5.49463 16.4623ZM5.77257 16.7368L5.77382 16.7412C5.77379 16.7413 5.77334 16.7399 5.77257 16.7368ZM4.47967 6.26473H15.5211L16.2452 9.32312L3.75556 9.32312L4.47967 6.26473Z"></path></svg>
			<?php 
			printf('<span>%s</span>',$pictos['label_magasin']);
			echo '</a>';
		}

		if($pictos['label_compte'] && $pictos['cible_compte']) {
			printf('<a href="%s" title="%s">',$pictos['cible_compte'],$pictos['label_compte']);
			?>
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12 12.01C10.9 12.01 9.96 11.6205 9.17 10.8315C8.39 10.0524 7.99 9.10362 7.99 8.00499C7.99 6.90637 8.38 5.96754 9.17 5.17853C9.96 4.38951 10.9 4 12 4C13.1 4 14.04 4.38951 14.83 5.17853C15.62 5.96754 16.01 6.90637 16.01 8.00499C16.01 9.10362 15.62 10.0424 14.83 10.8315C14.05 11.6105 13.1 12.01 12 12.01ZM18 20H6C5.45 20 4.98 19.8002 4.59 19.4107C4.2 19.0212 4 18.5518 4 18.0025V17.2035C4 16.6342 4.15 16.1149 4.44 15.6454C4.73 15.176 5.12 14.8065 5.6 14.5568C6.63 14.0375 7.68 13.6579 8.75 13.3983C9.82 13.1386 10.9 13.0087 12 13.0087C13.1 13.0087 14.18 13.1386 15.25 13.3983C16.32 13.6579 17.37 14.0474 18.4 14.5568C18.88 14.8065 19.27 15.166 19.56 15.6454C19.85 16.1248 20 16.6442 20 17.2035V18.0025C20 18.5518 19.8 19.0212 19.41 19.4107C19.02 19.8002 18.55 20 18 20ZM6 18.0025H18V17.2035C18 17.0237 17.95 16.8539 17.86 16.7041C17.77 16.5543 17.65 16.4345 17.5 16.3546C16.6 15.9051 15.69 15.5655 14.77 15.3458C13.85 15.1261 12.93 15.0062 11.99 15.0062C11.05 15.0062 10.13 15.1161 9.21 15.3458C8.29 15.5755 7.38 15.9051 6.48 16.3546C6.33 16.4345 6.21 16.5543 6.12 16.7041C6.03 16.8539 5.98 17.0237 5.98 17.2035V18.0025H6ZM12 10.0125C12.55 10.0125 13.02 9.81273 13.41 9.42322C13.8 9.03371 14 8.56429 14 8.01498C14 7.46567 13.8 6.99625 13.41 6.60674C13.02 6.21723 12.55 6.01748 12 6.01748C11.45 6.01748 10.98 6.21723 10.59 6.60674C10.2 6.99625 10 7.46567 10 8.01498C10 8.56429 10.2 9.03371 10.59 9.42322C10.98 9.81273 11.45 10.0125 12 10.0125Z" fill="#424242"/>
				</svg>
			<?php 
			printf('<span class="label-compte">%s</span>',$pictos['label_compte']);
			echo '</a>';
		}

		if($pictos['cible_panier']) {
			printf('<a href="%s" title="Mon panier">',$pictos['cible_panier']);
			?>
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M7.99388 22C7.44444 22 6.97492 21.8 6.58532 21.41C6.19572 21.02 5.99592 20.55 5.99592 20C5.99592 19.45 6.19572 18.98 6.58532 18.59C6.97492 18.2 7.44444 18 7.99388 18C8.54332 18 9.01284 18.2 9.40244 18.59C9.79205 18.98 9.99184 19.45 9.99184 20C9.99184 20.55 9.79205 21.02 9.40244 21.41C9.01284 21.8 8.54332 22 7.99388 22ZM17.9837 22C17.4342 22 16.9647 21.8 16.5751 21.41C16.1855 21.02 15.9857 20.55 15.9857 20C15.9857 19.45 16.1855 18.98 16.5751 18.59C16.9647 18.2 17.4342 18 17.9837 18C18.5331 18 19.0026 18.2 19.3922 18.59C19.7818 18.98 19.9816 19.45 19.9816 20C19.9816 20.55 19.7818 21.02 19.3922 21.41C19.0026 21.8 18.5331 22 17.9837 22ZM7.14475 6L9.5423 11H16.5352L19.2824 6H7.14475ZM7.99388 17C7.24465 17 6.67523 16.67 6.29562 16.01C5.916 15.35 5.89602 14.7 6.24567 14.05L7.59429 11.6L3.99796 4H2.979C2.69929 4 2.45953 3.9 2.27971 3.71C2.0999 3.52 2 3.28 2 3C2 2.72 2.0999 2.48 2.2897 2.29C2.47951 2.1 2.71927 2 2.99898 2H4.62732C4.80713 2 4.98695 2.05 5.15678 2.15C5.3266 2.25 5.44648 2.39 5.53639 2.58L6.2157 4.01H20.9507C21.4002 4.01 21.7099 4.18 21.8797 4.51C22.0495 4.84 22.0395 5.19 21.8497 5.56L18.3034 11.96C18.1235 12.29 17.8738 12.55 17.5741 12.74C17.2744 12.93 16.9348 13.02 16.5452 13.02H9.10275L8.00387 15.02H19.0226C19.3023 15.02 19.5421 15.12 19.7219 15.31C19.9017 15.5 20.0016 15.74 20.0016 16.02C20.0016 16.3 19.9017 16.54 19.7119 16.73C19.5221 16.92 19.2824 17.02 19.0026 17.02H7.99388V17Z" fill="#424242"/>
				</svg>
				<span class="screen-reader-text">Mon panier</span>
			<?php 
			echo '</a>';
		}
	echo '</div>';
}

