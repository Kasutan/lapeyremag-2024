!function(r){r(document).ready(function(){var t,e,a=r(window).width(),i=r("#copier-url"),i=(0<i.length&&i.click(function(){let e=r(this).attr("data-url");navigator&&navigator.clipboard&&navigator.clipboard.writeText?navigator.clipboard.writeText(e).then(()=>{r(this).find(".avant").hide(),r(this).find(".apres").show()},()=>{alert("Le presse-papier n'est pas accessible sur ce navigateur. Vous pouvez copier le lien directement ici : "+e)}):alert("Le presse-papier n'est pas accessible sur ce navigateur. Vous pouvez copier le lien directement ici : "+e)}),r(".entry-content h2"));function n(e,a){r(e).slideUp(),r(a).attr("aria-expanded","false")}0<i.length&&(t=r("#liens-sommaire"),e=r("#toggle-sommaire"),r(i).each(function(e,a){r(a).attr("id","ancre-"+e);var i=r(a).text(),i='<span class="num">'+parseInt(e+1)+'</span><span class="nom">'+i+"</span>";r(a).html(i),r(t).append('<li><a href="#ancre-'+e+'">'+i+"</a></li>")}),a<1014&&(n(t,e),r(t).find("a").click(function(){n(t,e)})),r("#toggle-sommaire").click(function(){"true"===r(this).attr("aria-expanded")?(r(this).attr("aria-expanded","false"),r(t).slideUp()):(r(this).attr("aria-expanded","true"),r(t).slideDown())}))})}(jQuery);