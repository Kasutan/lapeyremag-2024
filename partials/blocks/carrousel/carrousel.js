//https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html

(function($) {

	$( document ).ready(function() {
		var owl = $(".carrousel-livres .owl-carousel"); 
		if(owl.length > 0) {
			var items= {
				xsm : 1,
				sm : 1,
				md : 2,
				lg : 3,
			}
			owl.owlCarousel({
				loop:true,
				nav : true,
				navText:['<span class="screen-reader-text"> Livre précédent - previous book</span>','<span class="screen-reader-text">Livre suivant - next book </span>'],
				dots : false,
				autoplay:true,
				autoplayTimeout:5000,
				autoplaySpeed:2000,
				autoplayHoverPause:true,
				margin:0,
				//slideBy:'page',
				//checkVisible: false,
				onInitialized: accessibleNav,
				responsive : {
					0 : {
						items:items.xsm,
					},
					500 : {
						items:items.sm,
						margin:10,
					},
					768 : {
						items:items.md,
						margin:20,
					},
					1024 : {
						items:items.lg,
						margin:54,
					},
				}
			});
		}

		function accessibleNav(e) {
			//$('.owl-dot span').addClass('screen-reader-text');
			//$('.owl-dot span').html('Afficher le groupe de logos suivant');
			//Role incorrect d'après Axe
			$('.owl-nav button').removeAttr('role');

		}
	}); //fin document ready
})( jQuery );