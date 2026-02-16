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
	$pictos=array(
		array(
			'classe' => 'magasin',
			'cible' => 'https://magasins.lapeyre.fr/',
			'label' => 'Mon magasin',
			'icone' => '<svg class="sc-cyRTDc gYwCsC MuiSvgIcon-root MuiSvgIcon-fontSizeMedium sc-ihhmKJ eWrbWV" focusable="false" aria-hidden="true" viewBox="0 0 20 20" width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.73664 2.5C3.73664 1.94772 4.18435 1.5 4.73664 1.5H15.265C15.8173 1.5 16.265 1.94772 16.265 2.5C16.265 3.05228 15.8173 3.5 15.265 3.5H4.73664C4.18435 3.5 3.73664 3.05228 3.73664 2.5ZM14.2647 11.3231H12.7556V15.7345L12.7556 15.783C12.7556 16.1821 12.7557 16.5606 12.7221 16.8645C12.688 17.1728 12.6015 17.6254 12.2453 17.9837C11.888 18.343 11.435 18.4309 11.1263 18.4654C10.8232 18.4992 10.4459 18.4992 10.0496 18.4991L10.0008 18.4991H6.49137L6.44265 18.4991C6.04627 18.4992 5.66901 18.4992 5.36587 18.4654C5.05719 18.4309 4.60425 18.343 4.24689 17.9837C3.89065 17.6254 3.80417 17.1728 3.7701 16.8645C3.73653 16.5606 3.73658 16.1821 3.73663 15.783L3.73664 15.7345V11.323H3.73755C2.45587 11.3119 1.51334 10.1126 1.80936 8.86233L2.53348 5.80394C2.74707 4.90182 3.55261 4.26473 4.47967 4.26473H15.5211C16.4482 4.26473 17.2537 4.90182 17.4673 5.80394L18.1914 8.86233C18.4873 10.1121 17.5457 11.311 16.2647 11.323V17.4991C16.2647 18.0513 15.817 18.4991 15.2647 18.4991C14.7124 18.4991 14.2647 18.0513 14.2647 17.4991V11.3231ZM5.73664 11.3231V15.7345C5.73664 16.0821 5.73746 16.3178 5.74616 16.4898C5.91609 16.4983 6.14873 16.4991 6.49137 16.4991H10.0008C10.3435 16.4991 10.5761 16.4983 10.746 16.4898C10.7547 16.3178 10.7556 16.0821 10.7556 15.7345V11.3231H5.73664ZM10.7184 16.7412L10.7196 16.7368C10.7189 16.7399 10.7184 16.7413 10.7184 16.7412ZM10.993 16.4636C10.9959 16.4627 10.9975 16.4623 10.9976 16.4623C10.9976 16.4624 10.9962 16.4628 10.993 16.4636ZM5.49463 16.4623C5.4947 16.4623 5.49627 16.4627 5.49915 16.4636C5.49601 16.4628 5.49457 16.4624 5.49463 16.4623ZM5.77257 16.7368L5.77382 16.7412C5.77379 16.7413 5.77334 16.7399 5.77257 16.7368ZM4.47967 6.26473H15.5211L16.2452 9.32312L3.75556 9.32312L4.47967 6.26473Z"></path></svg>'
		),
		array(
			'classe' => 'rdv',
			'cible' => 'https://www.lapeyre.fr/',
			'label' => 'Prendre RDV',
			'icone' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.6667 0C12.1269 0 12.5 0.3731 12.5 0.833333V1.66667H13.3333C14.2538 1.66667 15 2.41286 15 3.33333V15C15 15.9205 14.2538 16.6667 13.3333 16.6667H1.66667C0.746192 16.6667 0 15.9205 0 15V3.33333C0 2.41286 0.746192 1.66667 1.66667 1.66667H2.5V0.833333C2.5 0.3731 2.8731 0 3.33333 0C3.79357 0 4.16667 0.3731 4.16667 0.833333V1.66667H10.8333V0.833333C10.8333 0.3731 11.2064 0 11.6667 0ZM1.66667 15H13.3333V6.66667H1.66667V15ZM9.8275 8.16083C10.1529 7.83533 10.6804 7.83533 11.0058 8.16083C11.3313 8.48625 11.3313 9.01375 11.0058 9.33917L6.83917 13.5058C6.51375 13.8313 5.98625 13.8313 5.66081 13.5058L3.99414 11.8392C3.6687 11.5138 3.6687 10.9863 3.99414 10.6608C4.31957 10.3353 4.84709 10.3353 5.17252 10.6608L6.25 11.7382L9.8275 8.16083ZM1.66667 5H13.3333V3.33333H1.66667V5Z" fill="black"></path></svg>'
		),
		array(
			'classe' => 'compte',
			'cible' => 'https://www.lapeyre.fr/LogonForm',
			'label' => 'Mon compte',
			'icone' => '<svg class="sc-cyRTDc gYwCsC MuiSvgIcon-root MuiSvgIcon-fontSizeMedium sc-imTPag hFhybq" focusable="false" aria-hidden="true" viewBox="0 0 20 20" width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path xmlns="http://www.w3.org/2000/svg" d="M10.5 11.9375C13.2889 11.9375 15.5776 13.0033 16.7384 13.6678C17.5841 14.1521 18 15.0467 18 15.9219V17.5625C18 18.0803 17.5803 18.5 17.0625 18.5H3.9375C3.41974 18.5 3 18.0803 3 17.5625V15.9219C3 15.0467 3.4159 14.1521 4.26159 13.6678C5.42244 13.0033 7.71112 11.9375 10.5 11.9375ZM10.5 13.8125C8.16944 13.8125 6.21229 14.7124 5.1936 15.2956C5.01497 15.3979 4.875 15.6167 4.875 15.9219V16.625H16.125V15.9219C16.125 15.6167 15.985 15.3979 15.8064 15.2956C14.7878 14.7124 12.8305 13.8125 10.5 13.8125ZM10.5 3.5C12.571 3.5 14.25 5.17893 14.25 7.25C14.25 9.32103 12.571 11 10.5 11C8.42893 11 6.75 9.32103 6.75 7.25C6.75 5.17893 8.42893 3.5 10.5 3.5ZM10.5 5.375C9.46444 5.375 8.625 6.21447 8.625 7.25C8.625 8.28553 9.46444 9.125 10.5 9.125C11.5356 9.125 12.375 8.28553 12.375 7.25C12.375 6.21447 11.5356 5.375 10.5 5.375Z" fill="black"></path></svg>'
		),
		array(
			'classe' => 'panier',
			'cible' => 'https://www.lapeyre.fr/cart',
			'label' => 'Panier',
			'icone' => '<svg class="sc-cyRTDc gYwCsC MuiSvgIcon-root MuiSvgIcon-fontSizeMedium sc-hKFoFe cbgjmj" focusable="false" aria-hidden="true" viewBox="0 0 20 20" width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.66156 18.3333C6.20369 18.3333 5.81242 18.1666 5.48776 17.8416C5.16309 17.5166 4.99659 17.125 4.99659 16.6666C4.99659 16.2083 5.16309 15.8166 5.48776 15.4916C5.81242 15.1666 6.20369 15 6.66156 15C7.11942 15 7.51069 15.1666 7.83536 15.4916C8.16003 15.8166 8.32653 16.2083 8.32653 16.6666C8.32653 17.125 8.16003 17.5166 7.83536 17.8416C7.51069 18.1666 7.11942 18.3333 6.66156 18.3333ZM14.9864 18.3333C14.5285 18.3333 14.1373 18.1666 13.8126 17.8416C13.4879 17.5166 13.3214 17.125 13.3214 16.6666C13.3214 16.2083 13.4879 15.8166 13.8126 15.4916C14.1373 15.1666 14.5285 15 14.9864 15C15.4443 15 15.8355 15.1666 16.1602 15.4916C16.4849 15.8166 16.6514 16.2083 16.6514 16.6666C16.6514 17.125 16.4849 17.5166 16.1602 17.8416C15.8355 18.1666 15.4443 18.3333 14.9864 18.3333ZM5.95395 4.99996L7.95191 9.16663H13.7793L16.0686 4.99996H5.95395ZM6.66156 14.1666C6.0372 14.1666 5.56268 13.8916 5.24634 13.3416C4.92999 12.7916 4.91334 12.25 5.20471 11.7083L6.32856 9.66663L3.33162 3.33329H2.48249C2.2494 3.33329 2.0496 3.24996 1.89975 3.09163C1.7499 2.93329 1.66666 2.73329 1.66666 2.49996C1.66666 2.26663 1.7499 2.06663 1.90808 1.90829C2.06625 1.74996 2.26604 1.66663 2.49914 1.66663H3.85609C4.00594 1.66663 4.15578 1.70829 4.2973 1.79163C4.43883 1.87496 4.53872 1.99163 4.61365 2.14996L5.17974 3.34163H17.4589C17.8335 3.34163 18.0916 3.48329 18.2331 3.75829C18.3746 4.03329 18.3663 4.32496 18.2081 4.63329L15.2528 9.96663C15.1029 10.2416 14.8948 10.4583 14.6451 10.6166C14.3953 10.775 14.1123 10.85 13.7876 10.85H7.58562L6.66988 12.5166H15.8522C16.0853 12.5166 16.2851 12.6 16.4349 12.7583C16.5848 12.9166 16.668 13.1166 16.668 13.35C16.668 13.5833 16.5848 13.7833 16.4266 13.9416C16.2684 14.1 16.0686 14.1833 15.8355 14.1833H6.66156V14.1666Z"></path></svg>'
		),
	);
	echo '<div class="pictos-header">';
			foreach($pictos as $picto) {
				printf('<a href="%s" class="%s">%s<span class="label">%s</span></a>',
					$picto['cible'],
					$picto['classe'],
					$picto['icone'],
					$picto['label']
				);
			}
	echo '</div>';
}

