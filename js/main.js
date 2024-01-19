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

		/********* Navigation dans menu produit **********/
		//Au chargement de la page, activer les premiers panneaux de niveau 2 et 3
		var premierPanneau=$('.niveau-2:first-of-type');
		$(premierPanneau).addClass('actif');
		$(premierPanneau).find('.niveau-3:first-of-type').addClass('actif');

		var boutonsProduits=$('button.produit');
		$(boutonsProduits).click(function(){
			var panneau=$('#'+$(this).attr('aria-controls'));

			
			if($(this).hasClass('niv1')) {
				//Désactiver les boutons de niveau 1 et de niveau 2
				$('.niv1, .niv2').removeClass('actif');
				//Désactiver les panneaux de niveau 2 et de niveau 3
				$('.niveau-2, .niveau-3').removeClass('actif');

				//Activer aussi le premier bouton de niveau 2 et le panneau de niveau 3 que ce bouton contrôle
				var bouton2=$(panneau).find('button:first-of-type');
				$(bouton2).addClass('actif');
				var panneau3=$('#'+$(bouton2).attr('aria-controls'));
				$(panneau3).addClass('actif');
			} else {
				//On désactive les boutons de niveau 2
				$('.niv2').removeClass('actif');
				//Désactiver les panneaux de niveau 3
				$('.niveau-3').removeClass('actif');
			}
			//Dans les 2 cas on active le bouton cliqué et le panneau qu'il contrôle
			$(this).addClass('actif');
			$(panneau).addClass('actif');
		});

		/********* Défilement des sliders articles ou autres objets **********/
		//Vérifier si on a besoin de la navigation
		var sliders=$('.slider-wrap');
		maybeHideSliderNav();
		function maybeHideSliderNav() {
			if(sliders.length <= 0) {
				return;
			}
			$(sliders).each(function(){
				var slides=$(this).find('.slide');
				var nb=slides.length;
				var vWidth=$(slides).outerWidth();
				var sliderWidth=vWidth*nb+20*(nb-1);
				if(sliderWidth <= $(this).outerWidth()) {
					$(this).find('.nav-slider').hide();
					//reset la position du slider
					$(this).find('.slider').css('left',0);
					$(this).find('.slider-drag').css('left',0);
					//Cas particulier du slider par univers
					$(this).find('.slider-univers').addClass('js-center');
					
				} else {
					$(this).find('.nav-slider').show();
					//Cas particulier du slider par univers
					$(this).find('.slider-univers').removeClass('js-center');
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
				var slideWidth=$(slider).find('.slide').outerWidth() + 20;

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
				var slideWidth=$(slider).find('.slide').outerWidth() + 20;

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

		/********* Cas particulier du slider de navigation par type de page**********/
		var sliderNavPage=$('.nav-page-type .slider-wrap');
		if(sliderNavPage.length > 0) {
			$(sliderNavPage).each(function(){
				if($(this).attr('data-total')==3) {
					$(this).find('.dot:nth-child(2)').trigger("click");
					$(this).find('.dot:nth-child(2)').addClass("active");

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

