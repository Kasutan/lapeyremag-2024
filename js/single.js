(function($) {

	$( document ).ready(function() {

		var width=$(window).width();

		/****************** Copier le lien single au clic sur un bouton *************************/	
		//https://developer.mozilla.org/en-US/docs/Web/API/Clipboard/writeText
		//Attention fonctionnalité désactivée pour localhost sur Chrome et probablement sur Firefox et/ou uniquement autorisée en httpS

		var boutonCopier=$('#copier-url');
		if(boutonCopier.length > 0) {
			boutonCopier.click(function(){
				let text=$(this).attr('data-url');
				if(navigator && navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(text).then( () => {
						/* success */

						$(this).find('.avant').hide();
						$(this).find('.apres').show();
						},
						() => {
						/* failure */
						alert('Le presse-papier n\'est pas accessible sur ce navigateur. Vous pouvez copier le lien directement ici : '+ text);

						}
					);
				} else {
					alert('Le presse-papier n\'est pas accessible sur ce navigateur. Vous pouvez copier le lien directement ici : '+ text);
				}

				
				
			})
		}

		
	
	}); //fin document ready
})( jQuery );

