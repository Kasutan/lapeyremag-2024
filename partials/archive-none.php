<?php
/**
 * 404 / No Results partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

$titre="Contenu introuvable";
$texte_s="Désolé, aucun résultat n'a été trouvé. Voulez-vous essayer avec des mots-clés différents&nbsp;?";
$texte_404="Ce contenu n'existe pas. Voulez-vous essayer une recherche&nbsp;?";



echo '<section class="no-results not-found">';

	echo '<h1 class="entry-title">' . $titre . '</h1>';
	echo '<div class="entry-content">';

	if ( is_search() ) {

		echo '<p>' . $texte_s . '</p>';
		get_search_form();

	} else {

		echo '<p>' . $texte_404 . '</p>';
		get_search_form();
	}

	echo '</div>';
echo '</section>';
