!function(n){n(document).ready(function(){n(window).width();var t,e=n("#copier-url"),e=(0<e.length&&e.click(function(){let e=n(this).attr("data-url");navigator&&navigator.clipboard&&navigator.clipboard.writeText?navigator.clipboard.writeText(e).then(()=>{n(this).find(".avant").hide(),n(this).find(".apres").show()},()=>{alert("Le presse-papier n'est pas accessible sur ce navigateur. Vous pouvez copier le lien directement ici : "+e)}):alert("Le presse-papier n'est pas accessible sur ce navigateur. Vous pouvez copier le lien directement ici : "+e)}),n(".entry-content h2"));0<e.length&&(t=n("#liens-sommaire"),n(e).each(function(e,a){n(a).attr("id","ancre-"+e);var i=n(a).html(),i='<span class="num">'+parseInt(e+1)+'</span><span class="nom">'+i+"</span>";n(a).html(i),n(t).append('<li><a href="#ancre-'+e+'">'+i+"</a></li>")}),n(t).slideUp(),n("#toggle-sommaire").click(function(){"true"===n(this).attr("aria-expanded")?(n(this).attr("aria-expanded","false"),n(t).slideUp()):(n(this).attr("aria-expanded","true"),n(t).slideDown())}))})}(jQuery);