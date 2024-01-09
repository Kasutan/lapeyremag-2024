(function ($) {

	$(document).ready(function () {
		/*--------------------------------------------------------------
		# Filtrer archives par catégorie
		https://listjs.com/docs/
		Si besoin de next et prev : https://codepen.io/gregh/pen/dJvNqb
		--------------------------------------------------------------*/
		var width = $(window).width();
		var nbPosts = 6;
		if (width >= 1024) {
			nbPosts = 12;
		}
		var optionsListe = {
			valueNames: ['categorie'],
			page: nbPosts,
			pagination: {
				outerWindow: 1,
				innerWindow: 1,
			},

		};

		var listePosts = new List('archive-filtrable', optionsListe);

		//Mettre à jour le compte
		updateCount();

		//Au clic sur un élément de pagination, smooth scroll en haut de la liste
		bindScroll(); // lier les écouteurs au premier affichage

		//lier les écouteurs à chaque fois que la liste est mise à jour + attendre un peu pour que les liens de navigation soient reconstruits
		listePosts.on('updated', function (list) {
			

			var isFirst = list.i == 1;
			var isLast = list.i > list.matchingItems.length - list.page;

			// make the Prev and Nex buttons disabled on first and last pages accordingly
			$(".pagination-prev.disabled, .pagination-next.disabled").removeClass(
				"disabled"
			);
			if (isFirst) {
				$(".pagination-prev").addClass("disabled");
			}
			if (isLast) {
				$(".pagination-next").addClass("disabled");
			}

			// hide pagination if there one or less pages to show
			if (list.matchingItems.length <= nbPosts) {
				$(".pagination-list").hide();
			} else {
				$(".pagination-list").show();
			}

			updateCount();
			setTimeout(bindScroll, 1000);
		})

		//Actions sur les chevrons de navigation
		$(".pagination-next").click(function () {
			$(".pagination .active")
				.next()
				.trigger("click");
		});
		$(".pagination-prev").click(function () {
			$(".pagination .active")
				.prev()
				.trigger("click");
		});


		function bindScroll() {
			$('.pagination li').click(function (e) {
				$("html, body").animate({
					scrollTop: $('#archive-filtrable').offset().top - 150
				}, 500);
				//Déplacer focus
				$('#archive-filtrable .vignette:first-child a').focus();
			});
		}

		function updateCount() {
			$('#nb-display').html(listePosts.visibleItems.length);
		}

	}); //fin document ready
})(jQuery);