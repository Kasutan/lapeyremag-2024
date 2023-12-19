<?php
/**
 * Navigation
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/
/**
* Generate a class attribute and an AMP class attribute binding.
*
* @param string $default Default class value.
* @param string $active  Value when the state enabled.
* @param string $state   State variable to toggle based on.
* @return string HTML attributes.
*/

/* walker for primary menu sub nav */
class etcode_sublevel_walker extends Walker_Nav_Menu
{
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .=sprintf('<button class="ouvrir-sous-menu picto"><span class="screen-reader-text">Montrer ou masquer le sous-menu</span><span class="picto-angle">%s</span></button><ul class="sub-menu">',kasutan_picto(array('icon'=>'angle')) );
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "</ul>";
	}
}


/**
* Navigation
*
*/

function kasutan_navigation() {
	echo '<div class="header-navigation">';


		//Navigation mobile
		kasutan_mobile_nav();

		//Navigation desktop
		echo '<nav id="site-navigation" class="nav-main" aria-label="menu principal">';
			if( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'container_class' => 'nav-primary' ) );
			}
			
		echo '</nav>';
	echo '</div>';
}


function kasutan_mobile_nav() {
	?>
	<button class="menu-toggle picto" id="menu-toggle" aria-controls="volet-navigation"  aria-label="Menu">
		<?php echo kasutan_picto(array('icon'=>'menu', 'class'=>'menu', 'size'=>'28'));?>
		<?php echo kasutan_picto(array('icon'=>'close', 'class' => 'fermer-menu','size'=>'28'));?>
	</button>
	<div class="volet-navigation"  id="volet-navigation">
		<?php
		if( class_exists('etcode_sublevel_walker') ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'menu-mobile',
				'walker' => new etcode_sublevel_walker,
				'menu_class'=>'menu-mobile',
			) );
		} else {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'menu-mobile',
				'menu_class'=>'menu-mobile',
			) );
		}
	echo '</div>'; //Fin volet navigation
}


/**
 * Archive Paginated Navigation
 *
 */
add_action( 'tha_content_while_after', 'kasutan_archive_paginated_navigation',20 );
function kasutan_archive_paginated_navigation() {
	if( ! is_singular() )
	the_posts_pagination();
}

