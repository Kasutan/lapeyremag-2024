!function(c){c(document).ready(function(){var a,i,s,e=c(window).width(),t=c(".bandeau-header li"),d=(1<t.length&&(a=0,c(t).each(function(e,t){c(t).outerHeight()>a&&(a=c(t).outerHeight())}),c(".bandeau-header, .bandeau-header li").css("height",a+"px"),c(".bandeau-header li:last-of-type").attr("data-next","0"),setInterval(function(){c(".bandeau-header li.last-active").removeClass();var e=c(".bandeau-header li.active"),t=c(e).attr("data-next");c(e).addClass("last-active"),c(".bandeau-header li[data-position="+t+"]").addClass("active")},7e3)),c(".volet-navigation .ouvrir-sous-menu")),r=(0<d.length&&d.click(function(e){var t=c(this).siblings(".sub-menu");c(this).hasClass("js-ouvert")?(c(this).removeClass("js-ouvert"),c(t).slideUp()):(d.removeClass("js-ouvert"),c(".volet-navigation .sub-menu").slideUp(),c(this).addClass("js-ouvert"),c(t).slideDown())}),0),n=c(".site-header"),l=c(n).outerHeight();let o=document.documentElement;o.style.setProperty("--hauteur-header",l+"px"),c(window).scroll(function(){var e=c(this).scrollTop();e<l?c("body").removeClass("js-sticky-header"):5<=Math.abs(r-e)&&(r<e?c("body").removeClass("js-sticky-header"):c("body").addClass("js-sticky-header"),r=e)}),e<768&&(i=c(".sitemap .toggle-col"),s=c(".sitemap .col"),c(i).attr("aria-expanded","false"),c(s).slideUp(),0<i.length)&&i.click(function(e){var t=c("#"+c(this).attr("aria-controls"));"true"===c(this).attr("aria-expanded")?(c(this).attr("aria-expanded","false"),c(t).slideUp()):(c(i).attr("aria-expanded","false"),c(s).slideUp(),c(this).attr("aria-expanded","true"),c(t).slideDown())});var h=c(".slider-wrap");function u(){h.length<=0||c(h).each(function(){var e=c(this).find(".vignette"),t=e.length;c(e).outerWidth()*t+20*(t-1)<=c(this).outerWidth()?c(this).find(".nav-slider").hide():c(this).find(".nav-slider").show()})}u();t=c(".fleche-produits");0<t.length&&t.click(function(e){var t=c(this).parents(".acf-block-boutique-produits-slider"),a=c(t).find("ul.products"),i=c(t).find(".gauche"),s=c(t).find(".droite"),d=parseInt(a.attr("data-active"))+parseInt(c(this).attr("data-direction")),r=c(a).find(".product").outerWidth()+10,n=c(t).find(".interieur").innerWidth(),t=c(t).find("li.product").length,n=parseInt(n/r);n=n<t?t-n+1:t,0<=d&&d<n&&(a.css("left",-1*d*r),a.attr("data-active",d)),0==d?(c(i).attr("disabled",!0),c(s).attr("disabled",!1)):d==n?(c(i).attr("disabled",!1),c(s).attr("disabled",!0)):(c(i).attr("disabled",!1),c(s).attr("disabled",!1))}),window.onresize=function(){768<window.innerWidth?c(".sitemap .col").show():c(".sitemap .col").each(function(){var e=c(this).siblings(".toggle-col");"false"===c(e).attr("aria-expanded")&&c(this).slideUp()}),l=c(n).outerHeight(),o.style.setProperty("--hauteur-header",l+"px"),u()}})}(jQuery);