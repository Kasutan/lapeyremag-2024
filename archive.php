<?php
/**
 * Archive
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


/**
 * Body Class
 *
 */
function ea_archive_body_class( $classes ) {
	$classes[] = 'archive';
	return $classes;
}
add_filter( 'body_class', 'ea_archive_body_class' );

/**
 * Archive Header
 *
 */
add_action( 'tha_content_while_before', 'ea_archive_header' );
function ea_archive_header() {

	$title = false;

	if( is_home() ) {
		$title = get_the_title( get_option( 'page_for_posts' ) );

	} elseif( is_search() ) {
		$title = 'Votre recherche&nbsp;: '.get_search_query();

	} elseif( is_archive() ) {
		$title = get_the_archive_title();
	}

	if( empty( $title ) )
		return;


	echo '<header class="entry-header">';
	do_action ('ea_archive_header_before' );
		echo '<h1 class="entry-title">' . $title . '</h1>';
	do_action ('ea_archive_header_after' );
	echo '</header>';

		echo '<ul class="loop">';
		

}

// Breadcrumbs
add_action( 'ea_archive_header_before', 'kasutan_fil_ariane', 10 );

// Fermer balise loop
add_action( 'tha_content_while_after', 'ea_archive_while_after',10 );
function ea_archive_while_after() {
	echo '</ul> <!--end .loop-->';
}
// Build the page
require get_template_directory() . '/index.php';
