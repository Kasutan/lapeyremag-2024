<?php
/**
 * Page
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Fil d'ariane au-dessus de la bannière
add_action( 'tha_entry_top', 'kasutan_fil_ariane', 5 );


// Bannière auto contenant le titre de la page
add_action( 'tha_entry_top', 'kasutan_page_banniere', 10 );


// Build the page
require get_template_directory() . '/index.php';




