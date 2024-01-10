<?php
/*Template Name: Page d'un type d'article*/

// Fil d'ariane au-dessus de la bannière
add_action( 'tha_entry_top', 'kasutan_fil_ariane', 5 );


// Bannière auto contenant le titre de la page (2 versions, page simple et page spéciale type d'article)
add_action( 'tha_entry_top', 'kasutan_page_banniere', 10 );



// Page title
//add_action( 'tha_entry_top', 'kasutan_page_titre', 10 );

// Build the page
require get_template_directory() . '/index.php';




