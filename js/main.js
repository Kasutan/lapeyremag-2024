(function($) {

	$( document ).ready(function() {
		var width=$(window).width();

		
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
		
		/********* Désactiver certains liens parents en desktop **********/
		//NON car ils continuent de recevoir le focus et d'être lus par les lecteurs d'écrans
		/*
		if(width >= 960 ) {
			$('.disable.menu-item-has-children > a').click(function(e) {
				e.preventDefault();
			})
		}*/



		/****************** Helpers formulaire *************************/	
		
		var colForm=$('.forminator-col.pleine-largeur');
		if(colForm.length > 0) {
			$(colForm).each(function(index) {
				$(this).parent('.forminator-row').addClass('pleine-largeur');
			});
		}

		//Supprimer les attributs aria-describedby qui pointent vers des éléments inexistants
		var inputsAvecAria=$('input[aria-describedby]');
		if(inputsAvecAria.length > 0) {
			$(inputsAvecAria).each(function() {
				var cible=$(this).attr('aria-describedby');
				if($('#'+cible).length === 0) {
					$(this).removeAttr('aria-describedby');
				}
			});
		}
		

	}); //fin document ready
})( jQuery );


/*=================================================
Animations
=================================================*/
//Only Use the IntersectionObserver if it is supported

if ('IntersectionObserver' in window) {
	const config = {
		//rootMargin: '50px 20px 75px 30px',
		//threshold: [0, 0.25, 0.75, 1]
		};

		
	observer = new IntersectionObserver((entries) => {
		entries.forEach(entry => {
			if (entry.intersectionRatio > 0) {
			entry.target.classList.add('fade-in');
			observer.unobserve(entry.target);
			} else {
			entry.target.classList.remove('fade-in');
			}
		}, config);
	});

	const fadeInElements=document.querySelectorAll('.js-fade-in-on-visible');
	fadeInElements.forEach(elem => {
		observer.observe(elem);
	});


	observer2 = new IntersectionObserver((entries) => {
		entries.forEach(entry => {
			if (entry.intersectionRatio > 0) {
			jQuery(entry.target).children().addClass('cascade');
			observer.unobserve(entry.target);
			} 
		}, config);
	});

	const cascadeElements=document.querySelectorAll('.js-cascade-on-visible');
	cascadeElements.forEach(item => {
		observer2.observe(item);
	});

} else {
	//if Intersection Observer is not supported, add classes right away
	jQuery('.js-animate-on-visible-cascade').addClass('cascade'); 
	jQuery('.js-animate-on-visible').addClass('fade-in');
}
