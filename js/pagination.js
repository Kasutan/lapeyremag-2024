(function($) {

	$( document ).ready(function() {
		/*--------------------------------------------------------------
		# Filtrer archives par catégorie
		https://listjs.com/docs/
		Si besoin de next et prev : https://codepen.io/gregh/pen/dJvNqb
		--------------------------------------------------------------*/
		var optionsListe = {
			valueNames: ['categorie'],
			page: 12, //TODO 6 en mobile
			pagination: {
				outerWindow:1,
				innerWindow:2,
			},
		
		};
	
		var listePosts = new List('archive-filtrable', optionsListe);

		//Au clic sur un élément de pagination, smooth scroll en haut de la liste
		bindScroll(); // lier les écouteurs au premier affichage

		//lier les écouteurs à chaque fois que la liste est mise à jour + attendre un peu pour que les liens de navigation soient reconstruits
		listePosts.on('updated',function(list) {
			
			setTimeout(bindScroll,1000);
		})

		function bindScroll() {
			$('.pagination li').click(function(e) {
				$("html, body").animate({
					scrollTop: $('#archive-filtrable').offset().top - 150
					}, 500);
			});
		}

	}); //fin document ready
})( jQuery );

