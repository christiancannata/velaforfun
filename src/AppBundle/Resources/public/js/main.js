(function(e){e.fn.smarticker=function(t){var n;var r=e.extend({pause:0,maxWidth:0,el:e(this),timeouts:[],nx:"start",newsRollerIndex:0,lastIndex:99999,clearTimeouts:function(){for(var t=0,n=r.timeouts.length;t<n;t++){clearTimeout(r.timeouts[t])}r.timeouts=[];e("*",r.el).clearQueue()}},e.fn.smarticker.defaults,t);return this.each(function(t){function m(){f=e("li",n);n.addClass("smarticker").wrapInner('<div class="smarticker-news"></div>');if(r.shuffle)e("li",n).randomize();if(g(f,"subcategory").length<1)r.subcategory=false;if(g(f,"category").length<1)r.category=false;if(r.subcategory==true){i.prependTo(n).addClass("sec1-2");e.each(g(f,"subcategory"),function(e,t){i.find("ul").append('<li data-subcategory="'+t+'"><a>'+t+"</a></li>")});if(r.theme==1||r.theme==2){i.find("ul").wrap('<div class="catlist"></div>');i.append('<span class="right"></span>').prepend('<span class="left"></span>')}}if(r.category==true&&g(f,"category").length>0){s.prependTo(n).addClass("sec1-2");e.each(g(f,"category"),function(e,t){s.find("ul").append('<li data-category="'+t+'"><a>'+t+"</a></li>")})}if(r.layout=="horizontal"){if(r.progressbar==true)u.appendTo(e(".smarticker-news",n));if(r.controllerType!=false)o.appendTo(n);if(r.category==false)n.addClass("no-category");if(r.subcategory==false)n.addClass("no-subcategory");n.addClass("theme"+r.theme);n.addClass("box size1");n.addClass("c"+r.controllerType);n.addClass("s-"+r.direction);e(".smarticker-news",n).addClass("sec7 newsholder");e(".smarticker-news > ul",n).attr("id","newsholder").addClass("newsholder");if(r.rounded==true)n.addClass("rounded");if(r.direction=="rtl")n.addClass("rtl");if(r.border==true)n.addClass("border");if(r.shadow==true)n.addClass("shadow");if(r.googleFont==true)n.addClass("googlefont");if(r.category==false||r.subcategory==false){e(".smarticker-news",n).removeClass("sec7").addClass("sec10")}if(r.category==false&&r.subcategory==false&&r.titlesection==true){e(".smarticker-news",n).removeClass("sec7").addClass("sec10");a.prependTo(n).text(r.title)}w();e(".next-news",n).on("click",function(){r.pause=1;r.nx="next";r.clearTimeouts();w();if(!e(".pause-news").hasClass("play-news"))r.pause=0});e(".prev-news",n).on("click",function(){r.pause=1;r.nx="prev";r.clearTimeouts();w();r.nx="next";if(!e(".pause-news").hasClass("play-news"))r.pause=0});e(".pause-news",n).on("click",function(){if(e(this).hasClass("play-news")){r.pause=0;w();e(this).removeClass("play-news").text("Pause")}else{r.clearTimeouts();r.pause=1;e(this).addClass("play-news").text("Play")}})}if(r.layout=="vertical"){}else{N("Layout type not valid! It must be Vertical Or Horizontal")}}function g(t,n){var r=[];e.each(t,function(t,i){if(e.inArray(e(i).data(n),r)==-1&&e(i).data(n)!=undefined)r.push(e(i).data(n))});return y(r)}function y(e){for(var t,n,r=e.length;r;t=parseInt(Math.random()*r),n=e[--r],e[r]=e[t],e[t]=n);return e}function b(){if(r.nx!="start"){r.lastIndex=r.newsRollerIndex}if(!r.nx||r.nx=="next"||r.nx=="start"){if(r.nx!="start")r.newsRollerIndex++;if(r.newsRollerIndex==e(".newsholder",n).find("li").length)r.newsRollerIndex=0;r.nx="next"}if(r.nx&&r.nx=="prev"){r.newsRollerIndex--;if(r.newsRollerIndex==-1)r.newsRollerIndex=e(".newsholder",n).find("li").length-1}}function w(){b();r.clearTimeouts();if(r.category==false||e(".smarticker-news ul li",n).eq(r.lastIndex).data("category")==e(".smarticker-news ul li",n).eq(r.newsRollerIndex).data("category")){E()}else{e(".active-ag",n).removeClass("active-ag");var t=e(".newsholder li",n).eq(r.newsRollerIndex).data("category");t=e(".smarticker-category ul li",n).index(e('.smarticker-category ul li[data-category="'+t+'"]',n));e(".smarticker-category ul li",n).eq(t).addClass("active-ag");t=e(".active-ag",n);e(".smarticker-category",n).animate({scrollTop:t.offset().top-t.parent().offset().top+t.parent().scrollTop()},r.speed-r.speed/2.5,function(){if(r.subcategory!=false){E()}else{S()}var t=e(".newsholder",n).find("li").eq(r.newsRollerIndex).data("color");if(t!=undefined&&r.catcolor!=false){e(".active-ag a",n).stop().animate({color:"#"+t},r.speed-r.speed/2.5)}else e(".active-ag a",n).stop().animate({color:"#999"},r.speed-r.speed/2.5)})}}function E(){if(r.subcategory==false||e(".smarticker-news ul li",n).eq(r.lastIndex).data("subcategory")==e(".smarticker-news ul li",n).eq(r.newsRollerIndex).data("subcategory")){N("Last index subcategory: "+r.lastIndex+"="+e(".smarticker-news ul li",n).eq(r.lastIndex).data("subcategory"));N("This subcategory: "+r.newsRollerIndex+"="+e(".smarticker-news ul li",n).eq(r.newsRollerIndex).data("subcategory"));if(e(".smarticker-news ul li",n).eq(r.newsRollerIndex).data("color")==e(".smarticker-news ul li",n).eq(r.lastIndex).data("color")){S();return false}}e(".active-cat",n).removeClass("active-cat");var t=e(".newsholder li",n).eq(r.newsRollerIndex).data("subcategory");t=e(".smarticker-cats li",n).index(e('.smarticker-cats li[data-subcategory="'+t+'"]',n));e(".smarticker-cats ul li",n).eq(t).addClass("active-cat");t=e(".active-cat",n);var i=t.parent();if(e(".catlist",n).length>0)i=e(".catlist",n);else i=e(".smarticker-cats",n);i.animate({scrollTop:Math.max(t.offset().top-i.offset().top+i.scrollTop(),0)},r.speed-r.speed/2.5,S);var s=e(".newsholder",n).find("li").eq(r.newsRollerIndex).data("color");if(s!=undefined&&r.subcatcolor!=false){e(".smarticker-cats li",n).animate({backgroundColor:"#"+s},r.speed-r.speed/2.5)}else e(".smarticker-cats li",n).animate({backgroundColor:"#c3c3c3"},r.speed-r.speed/2.5)}function S(){e(".newsholder",n).css({display:"block",height:"100%"});u.css("width","100%").animate({width:0},r.pausetime);if(r.animation=="default"){if(e(".activeRollerItem",n).length>0){var t=e(".activeRollerItem",n);t.animate({top:-25,opacity:0},r.speed-r.speed/1.2,function(){t.css("display","none")}).removeClass("activeRollerItem")}var i=e(".newsholder",n).find("li").eq(r.newsRollerIndex).addClass("activeRollerItem");i.css({top:"25px",display:"block"}).animate({opacity:1,top:0},r.speed-r.speed/2.5,function(){x()})}if(r.animation=="slide"){if(r.direction=="ltr"){if(e(".activeRollerItem",n).length>0){t=e(".activeRollerItem",n);t.animate({left:250,opacity:0},r.speed-r.speed/1.5,function(){t.css("display","none")}).removeClass("activeRollerItem")}var i=e(".newsholder li",n).eq(r.newsRollerIndex).addClass("activeRollerItem");i.css({left:"-150px",display:"block",opacity:"1"}).animate({opacity:1,left:10},r.speed-r.speed/3,function(){x()})}else{if(e(".activeRollerItem",n).length>0){t=e(".activeRollerItem",n);t.animate({right:250,opacity:0},r.speed-r.speed/1.5,function(){t.css("display","none")}).removeClass("activeRollerItem")}var i=e(".newsholder li",n).eq(r.newsRollerIndex).addClass("activeRollerItem");i.css({right:"-150px",display:"block",opacity:"1"}).animate({opacity:1,right:10},r.speed-r.speed/3,function(){x()})}}if(r.animation=="fade"){if(e(".activeRollerItem",n).length>0){t=e(".activeRollerItem",n);t.fadeOut(r.speed/2,function(){t.removeClass("activeRollerItem")})}var i=e(".newsholder li",n).eq(r.newsRollerIndex).addClass("activeRollerItem");i.css({top:"0",display:"none"}).fadeIn(r.speed/2,function(){x()})}if(r.animation=="typing"){if(e(".activeRollerItem",n).length>0){t=e(".activeRollerItem",n);var s=e('<div class="hider"></div>');var o="left";if(r.direction=="rtl")o="right";s.prependTo(e(".smarticker-news",n)).css({width:"0px",dir:"0px",height:"100%",position:"absolute","background-color":n.css("background-color"),"z-index":"2"});s.animate({width:t.width()+30},r.speed,function(){t.fadeOut(100,function(){t.css("opacity","0").removeClass("activeRollerItem");s.fadeOut(100,function(){var t=e(".newsholder li",n).eq(r.newsRollerIndex).addClass("activeRollerItem").css({display:"block",opacity:"1"});s.remove();var i=e('<div class="cover"><div class="flasher">_</div></div>');i.prependTo(e(".smarticker-news",n));i.css({"background-color":n.css("background-color")});if(r.direction=="ltr"){i.animate({left:t.width()+30},t.width()*8,function(){i.remove();x()})}else{i.animate({right:t.width()+30},t.width()*8,function(){i.remove();x()})}})})})}else{var i=e(".newsholder li",n).eq(r.newsRollerIndex).addClass("activeRollerItem").css({display:"block",opacity:"1"});var a=e('<div class="cover"><div class="flasher">_</div></div>');a.prependTo(e(".smarticker-news",n));a.css({"background-color":n.css("background-color")});if(r.direction=="ltr"){a.animate({left:i.width()+30},i.width()*8,function(){a.remove();if(r.pause==0){x()}})}else{a.animate({right:i.width()+30},i.width()*8,function(){a.remove();if(r.pause==0){x()}})}}}}function x(){r.maxWidth=0;r.maxWidth=n.width();r.maxWidth=r.maxWidth-e(".smarticker-cats",n).width();r.maxWidth=r.maxWidth-e(".smarticker-category",n).width();if(r.smartController!=false)r.maxWidth=r.maxWidth-e(".smart-controller",n).width();N("element: "+e(".activeRollerItem",n).width());N("max: "+r.maxWidth);if(e(".activeRollerItem",n).width()>r.maxWidth){r.maxWidth=e(".activeRollerItem",n).width()-r.maxWidth+10;e(".activeRollerItem",n).css("display","block");if(r.direction=="rtl"){e(".activeRollerItem",n).css("left","auto").animate({right:10},250).delay(1e3).animate({right:-r.maxWidth},r.maxWidth*30,function(){e(".activeRollerItem",n).delay(1e3).animate({right:10},300,function(){T()})})}else{e(".activeRollerItem",n).animate({left:10},250).delay(1e3).animate({left:-r.maxWidth},r.maxWidth*30,function(){e(".activeRollerItem",n).delay(1e3).animate({left:10},300,function(){T()})})}}else{T()}}function T(){if(r.pause!=1){r.timeouts.push(setTimeout(w,r.pausetime))}}function N(e){if(console.log&&r.developerMode==true){console.log(e)}}var n=e(this);var i=e('<div class="smarticker-cats"><ul></ul></div>');var s=e('<div class="smarticker-category"><ul></ul></div>');r.newsRollerIndex=r.startindex;var o=e('<div class="smart-controller"><span class="prev-news">Previous</span><span class="pause-news">Pause</span><span class="next-news">Next</span></div>').css("background",n.css("background"));var u=e('<div class="progress-bar"></div>');var a=e('<div class="sec1-2 tickertitle"></div>');var f=e("li",n);e("li",n).css("display","none");if(e.trim(r.rssFeed)!=""){n.append('<div class="loading" Style="text-align:center;color:#666;font-size:12px">Loading Rss feed, Please wait ...</div>');var l=r.rssFeed.split(",");var c=[];var h=[];var p=[];if(r.rssCats)h=r.rssCats.split(",");if(r.rssSources)c=r.rssSources.split(",");if(r.rssColors)p=r.rssColors.split(",");var d=0;v();function v(){var t=e.trim(l[d]);if(r.category!=false){if(c[d]!=undefined){c[d]='data-category="'+c[d]+'"'}else{c[d]='data-category="Rss"'}}else{c[d]=""}if(r.subcategory!=false){if(h[d]!=undefined){h[d]='data-subcategory="'+h[d]+'"'}else{h[d]='data-subcategory="Feed"'}}else{h[d]=""}if(p[d]!=undefined){p[d]='data-color="'+p[d]+'"'}else{p[d]='data-color="c3c3c3"'}var i=n;if(n.find("ul").length>0)i=n.find("ul");if(n.find("ul").length==0){i.append("<ul></ul>");i=n.find("ul")}if(r.feedLoader=="")r.googleApi=true;n.addClass("google-api");var s=window.location.protocol+"//ajax.googleapis.com/ajax/services/feed/load?v=1.0&num="+r.rssLimit+"&callback=?&q="+encodeURIComponent(t);var o;if(!r.googleApi||r.feedLoader!=""){n.removeClass("google-api").addClass("feed-loader");s=r.feedLoader;o={siteurl:t,limit:r.rssLimit}}e.ajax({type:"GET",url:s,data:o,dataType:"json",beforeSend:function(){e(".loading",n).text("Loading feed "+(d+1)+" of "+l.length)},error:function(){N("Unable to load feed, Incorrect path or invalid feed")},success:function(t){var i;if(!r.googleApi||r.feedLoader!="")i=t.item;else i=t.responseData.feed.entries;e.each(i,function(t,s){var s=e(this)[0];var o=e('<li style="display:none" '+p[d]+" "+h[d]+" "+c[d]+'><a target="'+r.linkTarget+'" href="'+s.link+'"></a></li>');o.find("a").text(s.title);var u=n;if(n.find("ul").length>0)u=n.find("ul");if(r.feedsOrder=="older")o.appendTo(u);else o.prependTo(u);if(t==i.length-1){if(d==l.length-1){e(".loading",n).fadeOut(200,function(){e(".loading",n).remove()});m()}else{d++;v()}}})}})}}else{m()}})};e.fn.smarticker.defaults={theme:1,direction:"ltr",layout:"horizontal",animation:"default",speed:1e3,startindex:0,pausetime:3e3,rounded:false,shadow:true,border:false,category:true,subcategory:true,titlesection:true,title:"Headlines",progressbar:false,catcolor:true,subcatcolor:true,shuffle:false,rssFeed:"",rssLimit:10,rssCats:"",rssSources:"",rssColors:"",googleApi:true,feedLoader:"",feedsOrder:"older",linkTarget:"_blank",controllerType:false,googleFont:true,developerMode:false}})(jQuery);(function(e){e.fn.randomize=function(){var t=this.get(),n=function(e){return Math.floor(Math.random()*e)},r=e.map(t,function(){var r=n(t.length),i=e(t[r]).clone(true)[0];t.splice(r,1);return i});this.each(function(t){e(this).replaceWith(e(r[t]))});return e(r)}})(jQuery)

var meteoIcon = [];
meteoIcon["01d"] = "wi-day-sunny";

meteoIcon["02d"] = "wi-day-cloudy";
meteoIcon["03d"] = "wi-cloud";
meteoIcon["04d"] = "wi-cloudy";
meteoIcon["09d"] = "wi-showers";
meteoIcon["10d"] = "wi-day-showers";
meteoIcon["11d"] = "wi-thunderstorm";
meteoIcon["13d"] = "wi-snow";
meteoIcon["50d"] = "wi-fog";
meteoIcon["01n"] = "wi-moon-new";
meteoIcon["02n"] = "wi-night-cloudy";
meteoIcon["03n"] = "wi-cloud";
meteoIcon["04n"] = "wi-cloudy";
meteoIcon["09n"] = "wi-showers";
meteoIcon["10n"] = "wi-night-showers";
meteoIcon["11n"] = "wi-thunderstorm";
meteoIcon["13n"] = "wi-snow";
meteoIcon["50n"] = "wi-fog";

function goToByScroll(id){
    // Remove "link" from the ID
    id = id.replace("link", "");
    // Scroll
    $('html,body').animate({
            scrollTop: $("#"+id).offset().top},
        'slow');
}


function vota(voto){
    var button = $(this);
    $("button").attr("disabled", "disabled");
    $.ajax({
        type: "POST",
        url: "vota/"+voto,
        success: function (response) {
            if (response.success == true) {
             //   $(".vota-"+voto).html("Voto inserito!");
                $("#modalVoto").modal();
                $("#voto-"+voto).html(parseInt($("#voto-"+voto).html())+1);

            } else {
            }
            $("button").removeAttr("disabled");

        }
    });
}
function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}



jQuery(document).ready(function ($) {

    $('.smarticker6').smarticker();

    if($("#fos_user_registration_form_profilePictureFile").length>0 && $(".hwi_oauth_registration_register").length==0){
        var html='<div class="form-group"> <label class="control-label required" for="blogbundle_articolo_profilePictureFile">Carica un Avatar</label> <span class="input-group-btn"> <span class="btn btn-primary btn-file">Sfoglia… <input type="file" name="fos_user_registration_form[profilePictureFile]" id="fos_user_registration_form_profilePictureFile"> </span> </span> <input type="text" readonly="" class="form-control"> </div>';
        $("#fos_user_registration_form_profilePictureFile").parent().html(html);
    }

    if($("#app_user_registration_profilePictureFile").length>0 && $(".hwi_oauth_registration_register").length==0){
        var html='<div class="form-group"> <label class="control-label required" for="blogbundle_articolo_profilePictureFile">Carica un Avatar</label> <span class="input-group-btn"> <span class="btn btn-primary btn-file">Sfoglia… <input type="file" name="app_user_registration[profilePictureFile]" id="app_user_registration_profilePictureFile"> </span> </span> <input type="text" readonly="" class="form-control"> </div>';
        $("#app_user_registration_profilePictureFile").parent().html(html);
    }




    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.form-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
    });



    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {

        if(localStorage.getItem("geolocation")){
            var meteo = JSON.parse(localStorage.getItem("geolocation"));

            $("#loadingMeteoMobile").hide();
            $("#mobile-localized-city").html(meteo.geoposition.name);
            $("#mobile-localized-city").show();
            $("#mobile-localized-arrow").show();

        }


        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, null, {maximumAge: 900000});
        } else {

            $("#loadingMeteoMobile").hide();
            $("#mobile-localized-city").html("Attiva la localizzazione");
            $("#mobile-localized-city").fadeIn();
            $("#mobile-localized-arrow").fadeIn();

        }
    }







    if($(".datetimepicker").length>0){
        $('select[id*=day]').selectpicker(
            {title: "Giorno"}
        );
        $('select[id*=month]').selectpicker(
            {title: "Mese"}
        );
        $('select[id*=year]').selectpicker(
            {title: "Anno"}
        );
    }

    $('select').selectpicker(
        {title: "Seleziona un elemento"}
    );

/*
    if($('#fos_user_registration_form_dataNascita').length>0){

    var html='<div id="datetimepicker" class="input-append date">';
        html+='<input data-format="dd/MM/yyyy hh:mm:ss" type="text"  id="fos_user_registration_form_dataNascita" required="required" name="fos_user_registration_form[dataNascita]"></input>';
        html+='  <span class="add-on">';
        html+='  <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>';
        html+= ' </span>';
        html+= ' </div>';

        $("#fos_user_registration_form_dataNascita").replaceWith(html);

        $('#fos_user_registration_form_dataNascita').datetimepicker({
            format: 'dd/MM/yyyy'
        });
    }

 */


    $(".marker-description button").click(function () {
        location.href = $(this).attr("id");
    });


    $(".back-link").click(function () {
        window.history.back();
        return false;
    });

    $(".check").click(function () {
        $(".check").removeClass("checked");
        $(this).addClass("checked");
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $(".flip-container").addClass("hover");
            $(".front,.flip-container").css("height", "50px");
            $("#nav-top").addClass("shadow-menu");
            $('#sub-menu').fadeIn(500);
        } else {


            $(".flip-container").removeClass("hover");
            $(".front,.flip-container").css("height", "50px");
            $('#sub-menu').fadeOut(500);
            $("#nav-top").removeClass("shadow-menu");
        }
    });


    $(".delete-entity").click(function () {
        var button = $(this);
        $("button").attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: $(this).attr('data-route'),
            success: function (response) {
                if (response.success == true) {
                    button.closest("tr").fadeOut();
                    showalert($("#response-div"), "Eliminato con successo!", "success");

                } else {
                    showalert($("#response-div"), "Errore nell'eliminazione", "error");
                }
                $("button").removeAttr("disabled");

            }
        });
    });


    $(".dropdown-toggle").click(function () {
        if ($(this).find("i").hasClass("fa-bars")) {
            $(this).find("i").switchClass("fa-bars", "fa-arrow-left");
        } else {
            $(this).find("i").switchClass("fa-arrow-left", "fa-bars");
        }
    });


    var mapmargin = 50;
    $('#map').css("height", ($(window).height() - mapmargin));
    $(window).on("resize", resize);
    resize();
    function resize() {

        if ($(window).width() >= 980) {
            $('#map').css("height", ($(window).height() - mapmargin));
            $('#map').css("margin-top", 50);
        } else {
            $('#map').css("height", ($(window).height() - (mapmargin + 12)));
            $('#map').css("margin-top", -21);
        }

    }

    if ($("#selectPorto").length > 0) {
        $.get('/porti/jsondata', function (data) {
            $("#selectPorto").typeahead({source: data});
        }, 'json');
    }


    if ($("#selectPorto3").length > 0) {
        $.get('/porti/jsondata', function (data) {
            $("#selectPorto3").typeahead({source: data});
        }, 'json');
    }


    if ($("#selectNodo").length > 0) {
        $.get('/nodi/jsondata', function (data) {
            $("#selectNodo").typeahead({source: data, valueKey: 'permalink'});
        }, 'json');
    }

    $("#formCercaPorto").submit(function (e) {
        e.preventDefault();
        if ($("#selectPorto").val() != "") {
            var form = $(this);
            var url = form.attr("action");
            if (url.indexOf("{permalink}") >= 0 && form.attr("method") == "GET") {
                e.preventDefault();
                url = url.replace('{permalink}', $("#selectPorto").typeahead("getActive").permalink);
                location.href = url;
            }
        }


    });


    $("#formCercaPorto3").submit(function (e) {
        e.preventDefault();
        if ($("#selectPorto3").val() != "") {
            var form = $(this);
            var url = form.attr("action");
            if (url.indexOf("{permalink}") >= 0 && form.attr("method") == "GET") {
                e.preventDefault();
                url = url.replace('{permalink}', $("#selectPorto3").typeahead("getActive").permalink);
                location.href = url;
            }
        }


    });


    $("#formTraduci").submit(function (e) {
        e.preventDefault();
        if ($("#traduci").val() != "") {
            var form = $(this);
            var url = form.attr("action");
            if (url.indexOf("{permalink}") >= 0 && form.attr("method") == "GET") {
                e.preventDefault();
                url = url.replace('{permalink}', $("#traduci").val());
                location.href = url;
            }
        }


    });


    $("#formCercaNodo").submit(function (e) {
        e.preventDefault();
        if ($("#selectNodo").val() != "") {
            var form = $(this);
            var url = form.attr("action");
            if (url.indexOf("{permalink}") >= 0 && form.attr("method") == "GET") {
                e.preventDefault();
                url = url.replace('{permalink}', $("#selectNodo").typeahead("getActive").permalink);
                location.href = url;
            }
        }


    });

    if($('.scroll-pane').length>0){
        $('.scroll-pane').jScrollPane({autoReinitialise:true});
    }


    if($('.scroll-pane-horizontal').length>0){
        $('.scroll-pane-horizontal').jScrollPane({autoReinitialise:true});
    }


    /* $("#loginFacebook").click(function (e) {
     e.preventDefault();
     $.ajax({
     type: "GET",
     url: "/secure_area/connect/service/facebook",
     success: function (response) {
     $("#loginFacebookModal .modal-body").html(response);
     $('#loginFacebookModal').modal();
     }
     });


     });
     */

});
function redirectScambioPosto(response) {
    if(typeof response.response!="undefined")
    location.href = '/forum/velaforfun/topic/' + response.response;
}
function redirectImbarco(response) {
     if(typeof response.response!="undefined")
     location.href = '/forum/velaforfun/topic/' + response.response;
}


function redirectRicetta(response) {


    location.href = '/archivio/' + response.response;
}

function checkNomeBarca(response){
        console.log(response);
}

function setRisultatiAnnunciImbarco(response) {
    $("table tbody").html(response);

    if ($("#notificaAnnuncio:checked").length > 0) {
        $("#annuncioCreato").modal();

    }

}

function riceviNotifica(response) {
    $("#modalVideo").modal("hide");
    $("#annuncioCreato").modal();

}


function setRisultatiScambioPosto(response) {
    $("table tbody").html(response);

    if ($("#notificaAnnuncio:checked").length > 0) {
        $("#annuncioCreato").modal();

    }
}

function setNuovoCommentoPorto(response) {
    $.get("/commento-porto/" + response.response, function (commento) {


        if ($("#commentiPorto").length > 0) {
            var nuovoCommento = ' <li class="media"> <div class="media-left"> <a href="#">';

            if (commento.utente.facebook_i_d != "") {
                nuovoCommento += '<img width="50" alt="avatar" src="http://graph.facebook.com/' + commento.utente.facebook_i_d + '/picture?type=square" class="media-object">';

            } else {
                if (typeof commento.utente.profilePicturePath != "undefined") {
                    nuovoCommento += '<img width="50" alt="avatar" src="/uploads/utenti/profilo/' + commento.utente.profilePicturePath + '" class="media-object">';

                } else {

                }
            }
            var tipoCommento = "";
            if (commento.tipo_commento == "POSITIVO") {
                tipoCommento = '<i class="fa fa-thumbs-up foreground-verde"></i>';

            }
            if (commento.tipo_commento == "NEGATIVO") {
                tipoCommento = '<i class="fa fa-thumbs-down foreground-rosso"></i>';

            }
            if (commento.tipo_commento == "NEUTRO") {
                tipoCommento = '<i class="fa fa-pause fa-rotate-90 foreground-grey"></i>';

            }

            nuovoCommento += '</a> </div> <div class="media-body"> <h4 class="media-heading">' + commento.utente.username + '</h4> <p>' + tipoCommento + " - " + commento.testo + '</p> <p style=\"font-size: 12px\">Inserito il ' + commento.timestamp + '</p> <p>';

            nuovoCommento += '</p> </div> </li>';


        } else {

            var box = '<div class="scroll-pane"><ul class="media-list" id="commentiPorto"></ul></div>';

            $(".no-commento").remove();
            $(".commento").append(box);

            location.href = '';
        }
        $('#commentiPorto li:eq(0)').before(nuovoCommento);

    });


};


function localizzami() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {

    }
}
function showPosition(position) {


    if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $('#meteoModal').modal();
    }else{


    }

    $.get("/porti/localizzami/jsondata?lat=" + position.coords.latitude + "&long=" + position.coords.longitude, function (meteo) {




        // Provide your access token
        L.mapbox.accessToken = 'pk.eyJ1IjoiY2hyaXN0aWFuMTQ4OCIsImEiOiJZaldjZlM0In0.hXiRMyyCDLdQZUrqXF2eNw';
        // Create a map in the div #map

        var arrayPorto2 = {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [
                    position.coords.longitude, position.coords.latitude

                ]
            },
            "properties": {
                "title": "Tu sei qui!",
                "marker-color": "#429CBC",
                "marker-size": "medium",
                "marker-symbol": "harbor"
            }
        };
        var map = L.mapbox.map('mappaMeteo', 'christian1488.m5b7ic2b');

        myLayer = L.mapbox.featureLayer(arrayPorto2).addTo(map);


        $("#meteo-localized-box").removeClass("hide");
        $("#div-localized").fadeOut();


        $("#meteo-localized-nome-2").html(meteo.geoposition.name);

        $("#meteo-localized-temperatura-2").html(parseInt(meteo.geoposition.main.temp) + "°");
        $("#meteo-localized-vento-2").html(meteo.geoposition.wind.speed + " km/h");
        $("#meteo-localized-umidita-2").html(meteo.geoposition.main.humidity + " %");
        $("#meteo-localized-icon-2").addClass(meteo.geoposition.weather[0].icon);

        $("#meteoModal #loading").addClass("hide");
        $("#meteoModal #contenuto").removeClass("hide");



        map.setView([position.coords.latitude, position.coords.longitude], 9);


        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $("#loadingMeteoMobile").hide();
            $("#mobile-localized-city").html(meteo.geoposition.name);
            $("#mobile-localized-city").fadeIn();
            $("#mobile-localized-arrow").fadeIn();
        }

        localStorage.setItem("geolocation", JSON.stringify(meteo));

    });



}

