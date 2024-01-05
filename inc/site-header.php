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
	//option acf bo
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
	if(!function_exists('get_field')) {
		return;
	}
	$pictos=get_field('lapeyre_topbar_pictos','options');
	if(empty($pictos) || !is_array($pictos)) {
		return;
	}
	echo '<div class="pictos-header">';
		if($pictos['label_magasin'] && $pictos['cible_magasin']) {
			printf('<a href="%s" class="magasin">',$pictos['cible_magasin']);
			?>
				<svg viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
					<path d="M21.2893 2H2.71068C1.213 2 0 3.19731 0 4.67563V21.0837C0 21.5846 0.420836 22 0.928314 22C1.43579 22 1.85663 21.5846 1.85663 21.0837V4.67563C1.85663 4.21136 2.24033 3.83262 2.71068 3.83262H21.2893C21.7597 3.83262 22.1434 4.21136 22.1434 4.67563V21.0837C22.1434 21.5846 22.5642 22 23.0717 22C23.5792 22 24 21.5846 24 21.0837V4.67563C24 3.19731 22.787 2 21.2893 2Z"></path>
					<path d="M6.4982 7.16799H17.5018C18.0093 7.16799 18.4301 6.7526 18.4301 6.25168C18.4301 5.75076 18.0093 5.33537 17.5018 5.33537H6.4982C5.99072 5.33537 5.56988 5.75076 5.56988 6.25168C5.56988 6.7526 5.99072 7.16799 6.4982 7.16799Z"></path>
					<path d="M17.1057 8.80513H6.89428C5.91645 8.80513 5.11191 9.58705 5.11191 10.5644V21.0715C5.11191 21.5724 5.53275 21.9878 6.04023 21.9878C6.54771 21.9878 6.96854 21.5724 6.96854 21.0715L6.89428 10.6378L10.0753 10.5767C10.1743 10.6133 10.2733 10.6378 10.3724 10.6378H11.0655V21.0715C11.0655 21.5724 11.4863 21.9878 11.9938 21.9878C12.5013 21.9878 12.9221 21.5724 12.9221 21.0715V10.6011L17.0315 10.5522V21.0715C17.0315 21.5724 17.4523 21.9878 17.9598 21.9878C18.4673 21.9878 18.8881 21.5724 18.8881 21.0715V10.5522C18.8881 9.58705 18.0959 8.79291 17.1057 8.79291V8.80513Z"></path>
				</svg>
			<?php 
			echo $pictos['label_magasin'];
			echo '</a>';
		}

		if($pictos['label_compte'] && $pictos['cible_compte']) {
			printf('<a href="%s">',$pictos['cible_compte']);
			?>
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8 8.00999C6.9 8.00999 5.96 7.62047 5.17 6.83146C4.39 6.05243 3.99 5.10362 3.99 4.00499C3.99 2.90637 4.38 1.96754 5.17 1.17853C5.96 0.389513 6.9 0 8 0C9.1 0 10.04 0.389513 10.83 1.17853C11.62 1.96754 12.01 2.90637 12.01 4.00499C12.01 5.10362 11.62 6.04245 10.83 6.83146C10.05 7.61049 9.1 8.00999 8 8.00999ZM14 16H2C1.45 16 0.98 15.8002 0.59 15.4107C0.2 15.0212 0 14.5518 0 14.0025V13.2035C0 12.6342 0.15 12.1149 0.44 11.6454C0.73 11.176 1.12 10.8065 1.6 10.5568C2.63 10.0375 3.68 9.65793 4.75 9.39825C5.82 9.13858 6.9 9.00874 8 9.00874C9.1 9.00874 10.18 9.13858 11.25 9.39825C12.32 9.65793 13.37 10.0474 14.4 10.5568C14.88 10.8065 15.27 11.166 15.56 11.6454C15.85 12.1248 16 12.6442 16 13.2035V14.0025C16 14.5518 15.8 15.0212 15.41 15.4107C15.02 15.8002 14.55 16 14 16ZM2 14.0025H14V13.2035C14 13.0237 13.95 12.8539 13.86 12.7041C13.77 12.5543 13.65 12.4345 13.5 12.3546C12.6 11.9051 11.69 11.5655 10.77 11.3458C9.85 11.1261 8.93 11.0062 7.99 11.0062C7.05 11.0062 6.13 11.1161 5.21 11.3458C4.29 11.5755 3.38 11.9051 2.48 12.3546C2.33 12.4345 2.21 12.5543 2.12 12.7041C2.03 12.8539 1.98 13.0237 1.98 13.2035V14.0025H2ZM8 6.01248C8.55 6.01248 9.02 5.81273 9.41 5.42322C9.8 5.03371 10 4.56429 10 4.01498C10 3.46567 9.8 2.99625 9.41 2.60674C9.02 2.21723 8.55 2.01748 8 2.01748C7.45 2.01748 6.98 2.21723 6.59 2.60674C6.2 2.99625 6 3.46567 6 4.01498C6 4.56429 6.2 5.03371 6.59 5.42322C6.98 5.81273 7.45 6.01248 8 6.01248Z" fill="#424242"/>
				</svg>
			<?php 
			echo $pictos['label_compte'];
			echo '</a>';
		}

		if($pictos['cible_panier']) {
			printf('<a href="%s">',$pictos['cible_panier']);
			?>
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5.66153 17.3332C5.20366 17.3332 4.81239 17.1665 4.48773 16.8415C4.16306 16.5165 3.99656 16.1248 3.99656 15.6665C3.99656 15.2082 4.16306 14.8165 4.48773 14.4915C4.81239 14.1665 5.20366 13.9998 5.66153 13.9998C6.11939 13.9998 6.51066 14.1665 6.83533 14.4915C7.16 14.8165 7.32649 15.2082 7.32649 15.6665C7.32649 16.1248 7.16 16.5165 6.83533 16.8415C6.51066 17.1665 6.11939 17.3332 5.66153 17.3332ZM13.9864 17.3332C13.5285 17.3332 13.1372 17.1665 12.8126 16.8415C12.4879 16.5165 12.3214 16.1248 12.3214 15.6665C12.3214 15.2082 12.4879 14.8165 12.8126 14.4915C13.1372 14.1665 13.5285 13.9998 13.9864 13.9998C14.4442 13.9998 14.8355 14.1665 15.1602 14.4915C15.4848 14.8165 15.6513 15.2082 15.6513 15.6665C15.6513 16.1248 15.4848 16.5165 15.1602 16.8415C14.8355 17.1665 14.4442 17.3332 13.9864 17.3332ZM4.95392 3.99984L6.95188 8.1665H12.7793L15.0686 3.99984H4.95392ZM5.66153 13.1665C5.03716 13.1665 4.56265 12.8915 4.24631 12.3415C3.92996 11.7915 3.91331 11.2498 4.20468 10.7082L5.32853 8.6665L2.33159 2.33317H1.48246C1.24936 2.33317 1.04957 2.24984 0.899721 2.0915C0.749874 1.93317 0.666626 1.73317 0.666626 1.49984C0.666626 1.2665 0.749874 1.0665 0.908046 0.908171C1.06622 0.749837 1.26601 0.666504 1.49911 0.666504H2.85606C3.0059 0.666504 3.15575 0.708171 3.29727 0.791504C3.4388 0.874837 3.53869 0.991504 3.61362 1.14984L4.17971 2.3415H16.4588C16.8335 2.3415 17.0915 2.48317 17.233 2.75817C17.3746 3.03317 17.3662 3.32484 17.2081 3.63317L14.2528 8.9665C14.1029 9.2415 13.8948 9.45817 13.645 9.6165C13.3953 9.77484 13.1123 9.84984 12.7876 9.84984H6.58558L5.66985 11.5165H14.8521C15.0852 11.5165 15.285 11.5998 15.4349 11.7582C15.5847 11.9165 15.668 12.1165 15.668 12.3498C15.668 12.5832 15.5847 12.7832 15.4266 12.9415C15.2684 13.0998 15.0686 13.1832 14.8355 13.1832H5.66153V13.1665Z" fill="#212121"/>
				</svg>
				<span class="screen-reader-text">Mon panier</span>
			<?php 
			echo '</a>';
		}
	echo '</div>';
}