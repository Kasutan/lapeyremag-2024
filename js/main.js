(function($) {

	$( document ).ready(function() {
		var width=$(window).width();


		/****Défilement du bandeau supérieur */
		var messages=$('.bandeau-header li');
		if(messages.length>1) {
			//il y a plusieurs messages (s'il n'y en a qu'un, pas de défilement)

			//Trouver le message le plus haut
			var max=0;
			$(messages).each(function(index, elem) {
				if($(elem).outerHeight() > max) {
					max=$(elem).outerHeight();
				}
			});
			//imposer la hauteur max au bandeau et à chaque message
			$('.bandeau-header, .bandeau-header li').css('height',max+'px');


			//corriger data-next pour le dernier message
			$('.bandeau-header li:last-of-type').attr('data-next',"0");

			
	
	
			setInterval(function(){ 
				$('.bandeau-header li.last-active').removeClass();
				var active = $('.bandeau-header li.active');
				var next=$(active).attr('data-next');
				$(active).addClass('last-active');
				$('.bandeau-header li[data-position='+next+']').addClass('active');
			}, 7000);
				
		}
		
		/********* Ouvrir-fermer les sous-menus mobile **********/
		var ouvrirSousMenu=$('.volet-navigation .ouvrir-sous-menu');
		if(ouvrirSousMenu.length>0) {
			ouvrirSousMenu.click(function(e) {
				var sousMenu=$(this).siblings('.sub-menu');

				if($(this).hasClass('js-ouvert')) {
					//le sous-menu était ouvert, on le referme
					$(this).removeClass('js-ouvert');
					$(sousMenu).slideUp();
				} else {
					//on referme tous les sous-menus
					ouvrirSousMenu.removeClass('js-ouvert');
					$('.volet-navigation .sub-menu').slideUp();

					//on ouvre celui demandé
					$(this).addClass('js-ouvert');
					$(sousMenu).slideDown();
				}
			});
		}
		
		/********* Header sticky on scroll up **********/

		var lastScrollTop = 0, delta = 5;
		var header=$('.site-header');
		var inner=$('.site-inner');
		var headerHeight=$(header).outerHeight();
		$(window).scroll(function(){
			var nowScrollTop = $(this).scrollTop();
			if(Math.abs(lastScrollTop - nowScrollTop) >= delta){
				if (nowScrollTop > lastScrollTop){
					//Scrolling down
					$('body').removeClass('js-sticky-header');
					$(inner).css('margin-top',0);
				} else {
					//Scrolling up
					$('body').addClass('js-sticky-header');
					$(inner).css('margin-top',headerHeight+'px');
				}
			lastScrollTop = nowScrollTop;
			}
		});


		/********* Ouvrir-fermer les colonnes du sitemap **********/
		//En mobile uniquement 
		if(width < 768) {
			var toggleCol=$('.sitemap .toggle-col');
			var cols=$('.sitemap .col');

			//Au chargement de la page, fermer toutes les colonnes en JS
			$(toggleCol).attr('aria-expanded','false');
			$(cols).slideUp();


			if(toggleCol.length>0) {
				toggleCol.click(function(e) {
					var col=$('#'+$(this).attr('aria-controls'));

					if($(this).attr('aria-expanded')==='true') {
						//le sous-menu était ouvert, on le referme
						$(this).attr('aria-expanded','false');
						$(col).slideUp();
					} else {
						//on referme toutes les colonnes
						$(toggleCol).attr('aria-expanded','false');
						$(cols).slideUp();

						//on ouvre la colonne demandée
						$(this).attr('aria-expanded','true');
						$(col).slideDown();
					}
				});
			}
		}

		/********* Ajustements quand on redimensionne la fenêtre **********/
		
		
		function ajusteOnResize() {
			var newWidth=window.innerWidth;
			if(newWidth > 768) {
				//Montrer toutes les colonnes du sitemap
				$('.sitemap .col').show();
			} else {
				//Refermer toutes les colonnes du sitemap qui devraient être fermées
				$('.sitemap .col').each(function() {
					var control=$(this).siblings('.toggle-col');
					if($(control).attr('aria-expanded')==="false") {
						$(this).slideUp();
					}
				})

			}
		}
		
		window.onresize = ajusteOnResize;
		

	}); //fin document ready
})( jQuery );

