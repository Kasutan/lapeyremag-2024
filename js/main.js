(function ($) {

	$(document).ready(function () {
		var width = $(window).width();

		/****Masquer le bandeau supérieur */
		var bandeau = $('.bandeau-header');
		var masquer = localStorage.getItem('lapeyre_masquer_bandeau');
		if (masquer === "oui") {
			//Le visiteur a déjà interagi avec le bandeau et l'a masqué, on le cache dès le chargement de la page
			$(bandeau).hide();
		} else {
			//on surveille l'interaction avec la croix
			$('#fermer-bandeau').click(function () {
				//Cacher le bandeau
				$(bandeau).slideUp();
				//Enregistrer ce choix pour la session 
				localStorage.setItem('lapeyre_masquer_bandeau', 'oui');
			})
		}

		/********* Header sticky on scroll up **********/

		var lastScrollTop = 0,
			delta = 5;
		var header = $('.site-header');
		var headerHeight = $(header).outerHeight();

		let root = document.documentElement;
		root.style.setProperty('--hauteur-header', headerHeight + 'px');

		$(window).scroll(function () {
			var nowScrollTop = $(this).scrollTop();
			if (nowScrollTop < headerHeight) {
				$('body').removeClass('js-sticky-header');
			} else if (Math.abs(lastScrollTop - nowScrollTop) >= delta) {
				if (nowScrollTop > lastScrollTop) {
					//Scrolling down

					//Si le menu produits était ouvert, on le referme
					if ($(boutonMenuProduits).attr('aria-expanded') === 'true') {
						fermerMenuProduitDesktop();
					}
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
		if (width < 768) {
			var toggleCol = $('.sitemap .toggle-col');
			var cols = $('.sitemap .col');

			//Au chargement de la page, fermer toutes les colonnes en JS
			$(toggleCol).attr('aria-expanded', 'false');
			$(cols).slideUp();


			if (toggleCol.length > 0) {
				toggleCol.click(function (e) {
					var col = $('#' + $(this).attr('aria-controls'));

					if ($(this).attr('aria-expanded') === 'true') {
						//le sous-menu était ouvert, on le referme
						$(this).attr('aria-expanded', 'false');
						$(col).slideUp();
					} else {
						//on referme toutes les colonnes
						$(toggleCol).attr('aria-expanded', 'false');
						$(cols).slideUp();

						//on ouvre la colonne demandée
						$(this).attr('aria-expanded', 'true');
						$(col).slideDown();
					}
				});
			}
		}

		/********* Ouverture et fermeture du menu produit en desktop**********/
		var boutonMenuProduits = $('#ouvrir-produits-desktop');
		var menuProduits = $('#menu-produits-desktop');
		var overlayProduits = $('#overlay-produits ');
		var boutonsProduits = $('#menu-produits-desktop button.produit');

		$(boutonMenuProduits).on('click mouseover', function () {
			$(this).attr('aria-expanded', 'true');
			$(menuProduits).fadeIn();
			$(overlayProduits).show();


			//A l'ouverture du menu, désactiver les panneaux et boutons précédemment activés 
			$('#menu-produits-desktop .niv1, #menu-produits-desktop .niv2').removeClass('actif');
			$('#menu-produits-desktop .niveau-2, #menu-produits-desktop .niveau-3').removeClass('actif');
			//Activer le premier bouton de niveau 1
			$('#menu-produits-desktop .niv1:first-of-type').addClass('actif');
			//Mettre le focus sur le bouton pour refermer le menu
			$('#fermer-produits-desktop').focus();

			//Activer les premiers panneaux de niveau 2 et 3
			var premierPanneau = $('#menu-produits-desktop .niveau-2:first-of-type');
			$(premierPanneau).addClass('actif');
			$(premierPanneau).find('.niveau-3:first-of-type').addClass('actif');

			//Activer le premier bouton de niveau 2 à l'intérieur du premier panneau
			$(premierPanneau).find('.niv2:first-of-type').addClass('actif');
		})
		//Fermer au clic sur le bouton avec le picto close
		$('#fermer-produits-desktop').click(function () {
			fermerMenuProduitDesktop();
		});
		//Fermer au clic n'importe où à l'extérieur du menu
		$(overlayProduits).click(function () {
			fermerMenuProduitDesktop();
		});

		function fermerMenuProduitDesktop() {
			$(menuProduits).fadeOut();
			$(boutonMenuProduits).attr('aria-expanded', 'false');
			$(overlayProduits).hide();
			$(boutonMenuProduits).focus();
		}

		/********* Navigation dans menu produit DESKTOP**********/

		$(boutonsProduits).click(function () {
			var panneau = $('#' + $(this).attr('aria-controls'));


			if ($(this).hasClass('niv1')) {
				//Désactiver les boutons de niveau 1 et de niveau 2
				$('#menu-produits-desktop .niv1, #menu-produits-desktop .niv2').removeClass('actif');
				//Désactiver les panneaux de niveau 2 et de niveau 3
				$('#menu-produits-desktop .niveau-2, #menu-produits-desktop .niveau-3').removeClass('actif');

				//Activer aussi le premier bouton de niveau 2 et le panneau de niveau 3 que ce bouton contrôle
				var bouton2 = $(panneau).find('button:first-of-type');
				$(bouton2).addClass('actif');
				var panneau3 = $('#' + $(bouton2).attr('aria-controls'));
				$(panneau3).addClass('actif');
			} else {
				//On désactive les boutons de niveau 2
				$('#menu-produits-desktop .niv2').removeClass('actif');
				//Désactiver les panneaux de niveau 3
				$('#menu-produits-desktop .niveau-3').removeClass('actif');
			}
			//Dans les 2 cas on active le bouton cliqué et le panneau qu'il contrôle
			$(this).addClass('actif');
			$(panneau).addClass('actif');
		});

		/********* Ouverture et fermeture du volet de navigation et du menu produit en mobile**********/
		var boutonVolet = $('#volet-ouvrir');
		var volet = $('#volet-navigation');
		var overlay = $('#overlay-mobile');
		var boutonMenuProduitsMobile = $('#ouvrir-produits-mobile');
		var menuProduitsMobile = $('#menu-produits-mobile');


		//Ouvrir volet de navigation
		$(boutonVolet).on('click', function () {
			$(this).attr('aria-expanded', 'true');
			$(volet).addClass('toggled');
			$(overlay).addClass('actif');
			$('body').css('overflow', 'hidden');
			//Mettre le focus sur le premier lien
			$('.menu-mobile').children('a:first-of-type').focus();
		})

		//Ouvrir menu produits
		$(boutonMenuProduitsMobile).on('click', function () {
			$(this).attr('aria-expanded', 'true');
			$(menuProduitsMobile).addClass('actif');
			$(menuProduitsMobile).find('.niveau-1').addClass('actif');
			//Mettre le focus sur le premier bouton qui permet d'ouvrir un autre volet
			$('.niveau-1').children('button:first-of-type').focus();
		});

		//Fermer un panneau du menu produit au clic sur son bouton avec le picto fleche
		$('.fermer-panneau').click(function () {
			var panneauAFermer = $(this).parent('.top-menu').parent('.niveau');
			$(panneauAFermer).removeClass('actif');

			//Si c'est un panneau de niveau 1, on ferme aussi le menu produits
			if ($(panneauAFermer).hasClass('niveau-1')) {
				fermerMenuProduitsMobile();
			} else if ($(panneauAFermer).hasClass('niveau-2')) {
				//Restore focus sur le premier bouton du panneau de niveau 1
				$('.niveau-1').children('button:first-of-type').focus();

			} else if ($(panneauAFermer).hasClass('niveau-3')) {
				//Restore focus sur le premier bouton du panneau de niveau 2 qui a la classe actif
				$('.niveau-2.actif').children('button:first-of-type').focus();
			}
		});

		//Fermer tout le volet de navigation au clic sur n'importe quel bouton avec le picto close
		$('.fermer-menu').click(function () {
			fermerVoletMobile();
		})

		//Fermer tout le volet de navigation au clic n'importe où à l'extérieur du volet
		$(overlay).click(function () {
			fermerVoletMobile();
		});

		function fermerMenuProduitsMobile() {
			$(menuProduitsMobile).removeClass('actif');
			$(menuProduitsMobile).find('.niveau').removeClass('actif');
			$(boutonMenuProduitsMobile).attr('aria-expanded', 'false');

			//Restaurer focus sur le bouton pour l'ouvrir
			$(boutonMenuProduitsMobile).focus();

		}

		function fermerVoletMobile() {
			$('#volet-navigation').removeClass('toggled');
			$(boutonMenuProduits).attr('aria-expanded', 'false');
			$(overlay).removeClass('actif');

			//Fermer aussi le menu produit à l'intérieur du volet (pour avoir l'écran initial si on le rouvre juste après)
			fermerMenuProduitsMobile();

			//Restaurer scroll vertical
			$('body').css('overflow', 'unset');

			//Restaurer focus
			$(boutonVolet).focus();
		}


		/********* Navigation dans menu produit MOBILE **********/

		$('#menu-produits-mobile button.produit').click(function () {
			var panneau = $('#' + $(this).attr('aria-controls'));
			$(panneau).addClass('actif');
			if ($(panneau).hasClass('niveau-2')) {
				//Déplacer le focus sur le premier bouton du panneau qu'on vient d'ouvrir
				$(panneau).children('button:first-of-type').focus();
			} else {
				//On est dans un panneau de niveau 3 
				//Déplacer le focus sur le premier lien du panneau qu'on vient d'ouvrir
				$(panneau).find('.liste-produits a:first-of-type').focus();
			}

		});

		/********* Navigation complémentaire au clavier (toutes tailles d'écrans) **********/

		$(document).on(
			'keydown',
			function (event) {
				if (event.key == "Escape") {
					if($('.menu-produits.mobile').hasClass('actif')) {
						fermerMenuProduitsMobile();
					} else if($('.volet-navigation').hasClass('toggled')) {
						fermerVoletMobile();
					} else if ($(boutonMenuProduits).attr('aria-expanded') === 'true') {
						fermerMenuProduitDesktop();
					}
				} else if (event.key == "ArrowDown" || event.key == "ArrowUp") {
					var boutonFocus=$('button.produit:focus');
					if(boutonFocus.length > 0) {
						event.preventDefault();//pour éviter de scroller en desktop et de refermer le menu alors qu'on est en train de naviguer dedans
						
						var index=parseInt($(boutonFocus).attr('data-index'));
						var boutons=$(boutonFocus).parent('.niveau').children('button.produit');
						var total=boutons.length;

						if(event.key == "ArrowDown" && index < total - 1) {
						
							var nextBouton=$(boutonFocus).parent('.niveau').children('button.produit')[index+1];
							$(nextBouton).focus();
						} else if(event.key == "ArrowUp" && index > 0) {
							var nextBouton=$(boutonFocus).parent('.niveau').children('button.produit')[index-1];
							$(nextBouton).focus();
						}
					}
				}


			});
		/********* Défilement des sliders articles ou autres objets **********/
		//Vérifier si on a besoin de la navigation
		var sliders = $('.slider-wrap');
		maybeHideSliderNav();

		function maybeHideSliderNav() {
			if (sliders.length <= 0) {
				return;
			}
			$(sliders).each(function () {
				var slides = $(this).find('.slide');
				var nb = slides.length;
				var vWidth = $(slides).outerWidth();
				var sliderWidth = vWidth * nb + 20 * (nb - 1);
				if (sliderWidth <= $(this).outerWidth()) {
					//On n'a pas besoin de la navigation
					$(this).find('.nav-slider').hide();
					//reset la position du slider
					$(this).find('.slider').css('left', 0);
					$(this).find('.slider-drag').css('left', 0);
					//Cas particulier du slider par univers
					$(this).find('.slider-univers').addClass('js-center');

				} else {
					//On a besoin de la navigation
					$(this).find('.nav-slider').show();
					//Cas particulier du slider par univers
					$(this).find('.slider-univers').removeClass('js-center');

					//Rendre ce slider draggable ****************/
					var snapWidth=vWidth+20;
					$(this).find('.slider').draggable({
						axis: "x" , 
						grid:[ snapWidth, snapWidth ]
					});
				}
			});
		}

		//Navigation par les flèches
		var flecheSlider = $('.fleche-slider');
		if (flecheSlider.length > 0) {
			flecheSlider.click(function (e) {
				var block = $(this).parents('.slider-wrap');
				var slider = $(block).find('.slider');
				var flecheGauche = $(block).find('.gauche');
				var flecheDroite = $(block).find('.droite');
				var dots = $(block).find('.dot');

				var active = parseInt($(block).attr('data-active'));
				var direction = parseInt($(this).attr("data-direction"));
				var newSlide = active + direction;
				var slideWidth = $(slider).find('.slide').outerWidth() + 20;

				var total = parseInt(block.attr('data-total'));
				var derniere = total - 1;

				if (newSlide >= 0 && newSlide <= derniere) {
					var newLeft = -1 * newSlide * slideWidth;
					slider.css('left', newLeft);
					block.attr('data-active', newSlide);
					//actualiser les dots
					$(dots).removeClass('active');
					$(block).find('.dot[data-target="' + newSlide + '"]').addClass('active');
				}
				if (newSlide == 0) {
					$(flecheGauche).attr('disabled', true);
					$(flecheDroite).attr('disabled', false);
				} else if (newSlide == derniere) {
					$(flecheGauche).attr('disabled', false);
					$(flecheDroite).attr('disabled', true);
				} else {
					$(flecheGauche).attr('disabled', false);
					$(flecheDroite).attr('disabled', false);
				}
			});
		}

		//Navigation par les dots
		var dotSlider = $('.slider-wrap .dot');
		if (dotSlider.length > 0) {
			dotSlider.click(function (e) {
				var block = $(this).parents('.slider-wrap');
				var slider = $(block).find('.slider');
				var flecheGauche = $(block).find('.gauche');
				var flecheDroite = $(block).find('.droite');
				var dots = $(block).find('.dot');

				var newSlide = $(this).attr("data-target");
				var slideWidth = $(slider).find('.slide').outerWidth() + 20;

				var total = parseInt(block.attr('data-total'));
				var derniere = total - 1;

				if (newSlide >= 0 && newSlide <= derniere) {
					var newLeft = -1 * newSlide * slideWidth;
					slider.css('left', newLeft);
					block.attr('data-active', newSlide);
					//actualiser les dots
					$(dots).removeClass('active');
					$(block).find('.dot[data-target="slide-' + newSlide + '"]').addClass('active');
				}
				if (newSlide == 0) {
					$(flecheGauche).attr('disabled', true);
					$(flecheDroite).attr('disabled', false);
				} else if (newSlide == derniere) {
					$(flecheGauche).attr('disabled', false);
					$(flecheDroite).attr('disabled', true);
				} else {
					$(flecheGauche).attr('disabled', false);
					$(flecheDroite).attr('disabled', false);
				}
			});
		}

		//Mettre à jour les éléments de navigation quand un slider est "draggé"
		//Voir https://api.jqueryui.com/draggable/#event-stop
		$( ".slider" ).on( "dragstop", function( event, ui ) {

			var slider=ui.helper;
			var block = $(slider).parents('.slider-wrap');
			var flecheGauche = $(block).find('.gauche');
			var flecheDroite = $(block).find('.droite');
			var dots = $(block).find('.dot');
			var slideWidth = $(slider).find('.slide').outerWidth() + 20;
			var newSlide = -1 * ui.position.left / slideWidth;
			var newSlide = parseInt(newSlide);
			console.log('newSlide', newSlide);

			var total = parseInt(block.attr('data-total'));
			var derniere = total - 1;

			if (newSlide >= 0 && newSlide <= derniere) {
				
				block.attr('data-active', newSlide);
				//actualiser les dots
				$(dots).removeClass('active');
				$(block).find('.dot[data-target="' + newSlide + '"]').addClass('active');
			}
			if (newSlide == 0) {
				$(flecheGauche).attr('disabled', true);
				$(flecheDroite).attr('disabled', false);
			} else if (newSlide == derniere) {
				$(flecheGauche).attr('disabled', false);
				$(flecheDroite).attr('disabled', true);
			} else {
				$(flecheGauche).attr('disabled', false);
				$(flecheDroite).attr('disabled', false);
			}

		} );


		/********* Cas particulier du slider de navigation par type de page**********/
		var sliderNavPage = $('.nav-page-type .slider-wrap');
		if (sliderNavPage.length > 0) {
			$(sliderNavPage).each(function () {
				if ($(this).attr('data-total') == 3) {
					$(this).find('.dot:nth-child(2)').trigger("click");
					$(this).find('.dot:nth-child(2)').addClass("active");

				}
			});
		}

		/********* Ajustements quand on redimensionne la fenêtre **********/


		function ajusteOnResize() {
			var newWidth = window.innerWidth;
			if (newWidth > 768) {
				//Montrer toutes les colonnes du sitemap
				$('.sitemap .col').show();
			} else {
				//Refermer toutes les colonnes du sitemap qui devraient être fermées
				$('.sitemap .col').each(function () {
					var control = $(this).siblings('.toggle-col');
					if ($(control).attr('aria-expanded') === "false") {
						$(this).slideUp();
					}
				})

			}

			//Ajuster aussi hauteur header
			headerHeight = $(header).outerHeight();
			root.style.setProperty('--hauteur-header', headerHeight + 'px');

			//Vérifier si on a besoin de la navigation des sliders
			maybeHideSliderNav();

		}

		window.onresize = ajusteOnResize;


	}); //fin document ready
})(jQuery);