!function(r){r(document).ready(function(){var i,e,a=r(window).width(),t=r("#copier-url"),t=(0<t.length&&t.click(function(){let e=r(this).attr("data-url");navigator&&navigator.clipboard&&navigator.clipboard.writeText?navigator.clipboard.writeText(e).then(()=>{r(this).find(".avant").hide(),r(this).find(".apres").show()},()=>{alert("Le presse-papier n'est pas accessible sur ce navigateur. Vous pouvez copier le lien directement ici : "+e)}):alert("Le presse-papier n'est pas accessible sur ce navigateur. Vous pouvez copier le lien directement ici : "+e)}),r(".entry-content h2"));function n(e,a){r(e).slideUp(),r(a).attr("aria-expanded","false")}0<t.length&&(i=r("#liens-sommaire"),e=r("#toggle-sommaire"),r(t).each(function(e,a){r(a).attr("id","ancre-"+e),r(a).addClass("avec-num");var t=r(a).text(),n=parseInt(e+1),n='<span class="num num1">'+n+'</span><span class="num num2">'+n+'</span><span class="nom">'+t+"</span>";r(a).html(n),r(i).append('<li><a href="#ancre-'+e+'">'+n+"</a></li>")}),n(i,e),a<1014&&r(i).find("a").click(function(){n(i,e)}),r("#toggle-sommaire").click(function(){"true"===r(this).attr("aria-expanded")?(r(this).attr("aria-expanded","false"),r(i).slideUp()):(r(this).attr("aria-expanded","true"),r(i).slideDown())}));t=r(".page-banniere-single").attr("data-post-id");r.ajax({type:"POST",url:lapeyremagVars.ajax_url,data:{nonce:lapeyremagVars.nonce,action:"kasutan_incremente_vues",data:{post:t}},success:function(e){},error:function(e,a,t){console.log("erreur ajax",t)},timeout:6e4})})}(jQuery);