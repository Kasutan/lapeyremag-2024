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

		/****************** Sommaire auto *************************/	
		var titres=$('.entry-content h2');
		if(titres.length > 0) {
			//Ajouter des ancres et des numéros aux titres et remplir la liste de liens
			var liens=$('#liens-sommaire');
			$(titres).each(function(index,elem) {
				$(elem).attr('id','ancre-'+index);
				var nom=$(elem).html();
				var num=parseInt(index+1);
				var newNom='<span class="num">'+num+'</span><span class="nom">'+nom+'</span>';
				$(elem).html(newNom);
				$(liens).append('<li><a href="#ancre-'+index+'">'+newNom+'</a></li>');
			})

			//Refermer le sommaire
			$(liens).slideUp();

			//Au clic sur le bouton, toggle
			$('#toggle-sommaire').click(function(){
				if($(this).attr('aria-expanded')==='true') {
					//le sommaire était ouvert, on le referme
					$(this).attr('aria-expanded','false');
					$(liens).slideUp();
				} else {
					//on ouvre le sommaire
					$(this).attr('aria-expanded','true');
					$(liens).slideDown();
				}
			})
		}

		
	
	}); //fin document ready
})( jQuery );

