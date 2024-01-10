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
		var headerHeight=$(header).outerHeight();

		let root = document.documentElement;
		root.style.setProperty('--hauteur-header',headerHeight+'px');

		$(window).scroll(function(){
			var nowScrollTop = $(this).scrollTop();
			if(nowScrollTop < headerHeight ) {
				$('body').removeClass('js-sticky-header');
			} else if(Math.abs(lastScrollTop - nowScrollTop) >= delta){
				if (nowScrollTop > lastScrollTop){
					//Scrolling down
					//Si on avait déjà collé le header, on le décolle
					$('body').removeClass('js-sticky-header');
				} else {
					//Scrolling up
					$('body').addClass('js-sticky-header');
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


		/********* Défilement des sliders articles **********/
		//Vérifier si on a besoin de la navigation
		var sliders=$('.slider-wrap');
		maybeHideSliderNav();
		function maybeHideSliderNav() {
			if(sliders.length <= 0) {
				return;
			}
			$(sliders).each(function(){
				var vignettes=$(this).find('.vignette');
				var nb=vignettes.length;
				var vWidth=$(vignettes).outerWidth();
				var sliderWidth=vWidth*nb+20*(nb-1);
				if(sliderWidth <= $(this).outerWidth()) {
					$(this).find('.nav-slider').hide();
				} else {
					$(this).find('.nav-slider').show();
				}
			});
		}

		//Navigation par les flèches
		var flecheSlider=$('.fleche-slider');
		if(flecheSlider.length>0) {
			flecheSlider.click(function (e) { 
				var block=$(this).parents('.slider-wrap');
				var slider=$(block).find('.slider');
				var flecheGauche=$(block).find('.gauche');
				var flecheDroite=$(block).find('.droite');
				var dots=$(block).find('.dot');

				var active=parseInt($(block).attr('data-active'));
				var direction=parseInt($(this).attr("data-direction"));
				var newSlide=active+direction;
				var slideWidth=$(slider).find('.vignette').outerWidth() + 20;

				var total = parseInt(block.attr('data-total'));
				var derniere=total - 1;

				if(newSlide >= 0 && newSlide <= derniere) {
					var newLeft=-1 * newSlide * slideWidth;
					slider.css('left',newLeft);
					block.attr('data-active',newSlide);
					//actualiser les dots
					$(dots).removeClass('active');
					$(block).find('.dot[data-target="'+newSlide+'"]').addClass('active');
				}
				if(newSlide == 0) {
					$(flecheGauche).attr('disabled',true);
					$(flecheDroite).attr('disabled',false);
				} else if(newSlide == derniere) {
					$(flecheGauche).attr('disabled',false);
					$(flecheDroite).attr('disabled',true);
				} else {
					$(flecheGauche).attr('disabled',false);
					$(flecheDroite).attr('disabled',false);
				}
			});
		}

		//Navigation par les dots
		var dotSlider=$('.slider-wrap .dot');
		if(dotSlider.length>0) {
			dotSlider.click(function (e) { 
				var block=$(this).parents('.slider-wrap');
				var slider=$(block).find('.slider');
				var flecheGauche=$(block).find('.gauche');
				var flecheDroite=$(block).find('.droite');
				var dots=$(block).find('.dot');

				var newSlide=$(this).attr("data-target");
				var slideWidth=$(slider).find('.vignette').outerWidth() + 20;

				var total = parseInt(block.attr('data-total'));
				var derniere=total - 1;

				if(newSlide >= 0 && newSlide <= derniere) {
					var newLeft=-1 * newSlide * slideWidth;
					slider.css('left',newLeft);
					block.attr('data-active',newSlide);
					//actualiser les dots
					$(dots).removeClass('active');
					$(block).find('.dot[data-target="slide-'+newSlide+'"]').addClass('active');
				}
				if(newSlide == 0) {
					$(flecheGauche).attr('disabled',true);
					$(flecheDroite).attr('disabled',false);
				} else if(newSlide == derniere) {
					$(flecheGauche).attr('disabled',false);
					$(flecheDroite).attr('disabled',true);
				} else {
					$(flecheGauche).attr('disabled',false);
					$(flecheDroite).attr('disabled',false);
				}
			});
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

			//Ajuster aussi hauteur header
			headerHeight=$(header).outerHeight();
			root.style.setProperty('--hauteur-header',headerHeight+'px');

			//Vérifier si on a besoin de la navigation des sliders
			maybeHideSliderNav();

		}
		
		window.onresize = ajusteOnResize;
		

	}); //fin document ready
})( jQuery );

