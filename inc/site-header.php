<?php
add_action('tha_header_top','kasutan_header_top');
function kasutan_header_top() {
	echo '<div class="topbar">';

	$aria="Formulaire de recherche dans l\'en-tête";
	$label="Saisissez vos mots-clés";
	$submit="Déclencher la recherche";
	$placeholder="Rechercher";
	$action="/";

	

	printf('<form role="search" method="get" class="search-form search-topbar" action="%s" aria-label="%s">
			<label>
				<span class="screen-reader-text">%s</span>
				<input class="search-field" 
				placeholder="%s" value="" name="s" type="search"></label>
			<input class="search-submit" value="%s" type="submit">
		</form>',
		$action,
		$aria,
		$label,
		$placeholder,
		$submit
	);
	
	
	echo '</div>';
}