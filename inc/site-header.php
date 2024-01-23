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
		echo '<ul class="bandeau-header">';
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

		//Cas particulier où il n'y a que deux messages.
		if($total===2) {
			//Pour que le défilement fonctionne bien, on duplique les 2 messages
			printf('<li data-position="2" data-next="3" >%s</li>',$messages[0]);
			printf('<li data-position="3" data-next="0" >%s</li>',$messages[1]);
			
		}

	
		echo '</ul>';
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
				<svg viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
					<path d="M21.2893 2H2.71068C1.213 2 0 3.19731 0 4.67563V21.0837C0 21.5846 0.420836 22 0.928314 22C1.43579 22 1.85663 21.5846 1.85663 21.0837V4.67563C1.85663 4.21136 2.24033 3.83262 2.71068 3.83262H21.2893C21.7597 3.83262 22.1434 4.21136 22.1434 4.67563V21.0837C22.1434 21.5846 22.5642 22 23.0717 22C23.5792 22 24 21.5846 24 21.0837V4.67563C24 3.19731 22.787 2 21.2893 2Z"></path>
					<path d="M6.4982 7.16799H17.5018C18.0093 7.16799 18.4301 6.7526 18.4301 6.25168C18.4301 5.75076 18.0093 5.33537 17.5018 5.33537H6.4982C5.99072 5.33537 5.56988 5.75076 5.56988 6.25168C5.56988 6.7526 5.99072 7.16799 6.4982 7.16799Z"></path>
					<path d="M17.1057 8.80513H6.89428C5.91645 8.80513 5.11191 9.58705 5.11191 10.5644V21.0715C5.11191 21.5724 5.53275 21.9878 6.04023 21.9878C6.54771 21.9878 6.96854 21.5724 6.96854 21.0715L6.89428 10.6378L10.0753 10.5767C10.1743 10.6133 10.2733 10.6378 10.3724 10.6378H11.0655V21.0715C11.0655 21.5724 11.4863 21.9878 11.9938 21.9878C12.5013 21.9878 12.9221 21.5724 12.9221 21.0715V10.6011L17.0315 10.5522V21.0715C17.0315 21.5724 17.4523 21.9878 17.9598 21.9878C18.4673 21.9878 18.8881 21.5724 18.8881 21.0715V10.5522C18.8881 9.58705 18.0959 8.79291 17.1057 8.79291V8.80513Z"></path>
				</svg>
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

