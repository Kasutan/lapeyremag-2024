<?php
/**
 * Site Footer
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

echo '</div>'; // .site-inner
tha_footer_before();
echo '<footer class="site-footer">';
tha_footer_top();
	echo '<div class="main-footer">';
		do_action('kasutan_main_footer');
	echo '</div>';
tha_footer_bottom();
echo '</footer>';
tha_footer_after();

echo '</div>';
tha_body_bottom();
wp_footer();

echo '</body></html>';
