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
			var bouton=$('#toggle-sommaire');
			$(titres).each(function(index,elem) {
				$(elem).attr('id','ancre-'+index);
				$(elem).addClass('avec-num');
				var nom=$(elem).text();	//Pour enlever les balises internes au H2
				var num=parseInt(index+1);
				var newNom='<span class="num num1">'+num+'</span><span class="num num2">'+num+'</span><span class="nom">'+nom+'</span>';
				$(elem).html(newNom);
				$(liens).append('<li><a href="#ancre-'+index+'">'+newNom+'</a></li>');
			})

			//Refermer le sommaire en mobile uniquement
			if(width<1014) {
				fermerSommaire(liens,bouton);
				//Au clic sur un lien interne en mobile, refermer le sommaire
				$(liens).find('a').click(function() {
					fermerSommaire(liens,bouton);
				})
			}

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

		function fermerSommaire(liens,bouton) {
			$(liens).slideUp();
			$(bouton).attr('aria-expanded','false');
			
		}

		/****************** compteur de visites en AJAX *************************/	
		var post=$('.page-banniere-single').attr('data-post-id');
		$.ajax({
			type: "POST",
			url: lapeyremagVars.ajax_url,
			data: {
				nonce: lapeyremagVars.nonce,
				action: 'kasutan_incremente_vues',
				data: {
					post: post,
				},
			},
			success: function(response){
				//Success
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				//Error
				console.log('erreur ajax',errorThrown);
				//$(errorMessage).show();				
			},
			timeout: 60000
		});

	
	}); //fin document ready
})( jQuery );

