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



		
		

	}); //fin document ready
})( jQuery );

