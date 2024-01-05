<?php
add_action('tha_header_top','kasutan_header_bandeau',5);
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
				$classe="";
				$next=$position+1; //sera faux pour le dernier message, corrigé en JS
				if($position === 1) {
					$classe="active";
				}
				printf('<li class="%s" data-position="%s" data-next="%s" >%s</li>',
					$classe,
					$position,
					$next,
					$text
				);
				$position++;
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

add_action('kasutan_main_header','kasutan_navigation',5);
//cf navigation.php

add_action('kasutan_main_header','kasutan_header_logo',10);
function kasutan_header_logo() {
	//Url du site parent : option ACF BO 
	$url_home=get_option('options_lapeyre_cible_logo',false);
	if(!$url_home) {
		$url_home="https://www.lapeyre.fr";
	}
	//Url de l'image : appel API puis option WP
	$logo=get_option('lapeyre_headers_logo',false);
	if($logo && is_array($logo) && array_key_exists('url',$logo) && !empty($logo['url'])) {
		printf('<a class="site-title" href="%s"><img src="%s" alt="Logo Lapeyre" width="213" height="68" /></a>',$url_home,$logo['url']);
	}
}


add_action('kasutan_main_header','kasutan_header_recherche',20);
function kasutan_header_recherche() {

	$aria="Formulaire de recherche dans l\'en-tête";
	$label="Saisissez vos mots-clés";
	$submit="Déclencher la recherche";
	$placeholder=get_option('options_lapeyre_recherche_placeholder',false);
	if(!$placeholder) {
		$placeholder="Rechercher un produit, un service, un tutoriel...";
	}
	$action="/";

	

	printf('<form role="search" method="get" class="search-form search-topbar" action="%s" aria-label="%s">
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


add_action('kasutan_main_header','kasutan_header_pictos',30);
function kasutan_header_pictos() {
	echo 'pictos ici';
}