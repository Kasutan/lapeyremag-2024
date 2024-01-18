<?php 


/**************************************************************************
* 						Ajax incrémente la meta d'un article
***************************************************************************/

add_action( 'wp_ajax_kasutan_incremente_vues', 'kasutan_incremente_vues' );
function kasutan_incremente_vues() {
	if ( !wp_verify_nonce($_POST['nonce'], 'lapeyremag_nonce') ){ 
		die('Permission Denied.'); 
	}
	$post_id = sanitize_text_field($_POST['data']['post']);
	

	try {

		$vues=get_post_meta($post_id, 'lapeyre_vues', true);
		if(empty($vues)) {
			$vues=0;
		}
		$vues++;
		$response = update_post_meta($post_id,'lapeyre_vues',$vues);
		
		if($response) {
			echo true; 
			die();
		} else {
			error_log('AJAX COMPTEUR VUES la méta n\a pas pu être incrémentée');
			echo false;
			die();
		}
	} catch(Exception $e){
		error_log('AJAX COMPTEUR VUE try/catch raised an exception');
		error_log($e->getMessage());
		echo false;
		die();
	}
}
