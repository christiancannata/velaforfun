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

function goToByScroll(id) {
    // Remove "link" from the ID
    id = id.replace("link", "");
    // Scroll
    $('html,body').animate({
            scrollTop: $("#" + id).offset().top
        },
        'slow');
}


$(".eliminaImbarco").click(function () {
    var button = $(this);
    $("button").attr("disabled", "disabled");
    $.ajax({
        type: "POST",
        url: "/annuncio-imbarco/elimina/" + button.attr("data-id"),
        success: function (response) {
            if (response.success == true) {
                //   $(".vota-"+voto).html("Voto inserito!");
                location.href='';
            } else {
            }
            $("button").removeAttr("disabled");

        }
    });

});

$(".eliminaScambioPosto").click(function () {
    var button = $(this);
    $("button").attr("disabled", "disabled");
    $.ajax({
        type: "POST",
        url: "/annuncio-scambio-posto/elimina/" + button.attr("data-id"),
        success: function (response) {
            if (response.success == true) {
                //   $(".vota-"+voto).html("Voto inserito!");
                location.href='';
            } else {
            }
            $("button").removeAttr("disabled");

        }
    });

});


function vota(voto) {
    var button = $(this);
    $("button").attr("disabled", "disabled");
    $.ajax({
        type: "POST",
        url: "vota/" + voto,
        success: function (response) {
            if (response.success == true) {
                //   $(".vota-"+voto).html("Voto inserito!");

                $("#modalVoto").modal();

                $("#voto-" + voto).html(parseInt($("#voto-" + voto).html()) + 1);
                setTimeout(function () {
                    $("#modalVoto").modal("hide");
                }, 2000);

            } else {
            }
            $("button").removeAttr("disabled");

        }
    });
}


function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}


jQuery(document).ready(function ($) {

    if (!localStorage.getItem("visualizzatoBenvenuto")) {
        $("#modalBeta").modal();
        localStorage.setItem("visualizzatoBenvenuto", 1);
    }


    if ($('.smarticker6').length > 0) {
        $('.smarticker6').smarticker();
    }

    if ($("#fos_user_registration_form_profilePictureFile").length > 0 && $(".hwi_oauth_registration_register").length == 0) {
        var html = '<div class="form-group"> <label class="control-label required" for="blogbundle_articolo_profilePictureFile">Carica un Avatar</label> <span class="input-group-btn"> <span class="btn btn-primary btn-file">Sfoglia… <input type="file" name="fos_user_registration_form[profilePictureFile]" id="fos_user_registration_form_profilePictureFile"> </span> </span> <input type="text" readonly="" class="form-control"> </div>';
        $("#fos_user_registration_form_profilePictureFile").parent().html(html);
    }

    if ($("#app_user_registration_profilePictureFile").length > 0 && $(".hwi_oauth_registration_register").length == 0) {
        var html = '<div class="form-group"> <label class="control-label required" for="blogbundle_articolo_profilePictureFile">Carica un Avatar</label> <span class="input-group-btn"> <span class="btn btn-primary btn-file">Sfoglia… <input type="file" name="app_user_registration[profilePictureFile]" id="app_user_registration_profilePictureFile"> </span> </span> <input type="text" readonly="" class="form-control"> </div>';
        $("#app_user_registration_profilePictureFile").parent().html(html);
    }


    $(document).on('change', '.btn-file :file', function () {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready(function () {
        $('.btn-file :file').on('fileselect', function (event, numFiles, label) {

            var input = $(this).parents('.form-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                input.val(log);
            } else {
                if (log) alert(log);
            }

        });
    });


    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {

        if (localStorage.getItem("geolocation")) {
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


    if ($(".datetimepicker").length > 0) {
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


    $(".marker-description button").click(function () {
        location.href = $(this).attr("id");
    });


    $(".settaArticoloCrud").click(function () {

        var articoloCorrelato = $("#selectArticolo").typeahead("getActive").permalink;


        var res = articoloCorrelato.split("|");

        $("#blogbundle_articolo_titoloCorrelato").val(res[0]);
        $("#blogbundle_articolo_testoCorrelato").val(res[3]);
        $("#blogbundle_articolo_textFileImage").attr("value", res[2]);
        $("#blogbundle_articolo_linkCorrelato").val(res[1]);

        $("#blogbundle_articolo_textFileImage").parent().show();
        $("#blogbundle_articolo_textFileImage").after("<button class='btn btn-primary' onclick='$(\"#blogbundle_articolo_textFileImage\").parent().hide();$(\"#blogbundle_articolo_immagineCorrelata\").parent().show();'>Carica file</button>");
        $("#blogbundle_articolo_immagineCorrelata").parent().hide();
    });


    $(".eliminaSelezionati").click(function () {
        var button = $(this);
        $("button").attr("disabled", "disabled");

        var val = [];
        $(':checkbox:checked').each(function (i) {
            val[i] = $(this).val();
        });

        console.log(val);
        $.ajax({
            type: "POST",
            url: $(this).attr('data-route') + val.join(),
            success: function (response) {
                if (response.success == true) {
                    $(":checkbox:checked").closest("tr").remove();
                    showalert($("#response-div"), "Eliminato con successo!", "success");

                } else {
                    showalert($("#response-div"), "Errore nell'eliminazione", "error");
                }
                $("button").removeAttr("disabled");

            }
        });


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

    $(document).on('click', ".delete-entity", function() {
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

    $(".delete-single-entity").click(function () {
        var button = $(this);
        $("button").attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: $(this).attr('data-route'),
            success: function (response) {
                if (response.success == true) {
                    showalert($("#response-div"), "Eliminato con successo!", "success");

                } else {
                    showalert($("#response-div"), "Errore nell'eliminazione", "error");
                }


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


    if ($("#selectArticolo").length > 0) {
        $.get('/archivio/json-all', function (data) {
            $("#selectArticolo").typeahead({source: data});
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

    if ($('.scroll-pane').length > 0) {
        $('.scroll-pane').jScrollPane({autoReinitialise: true});
    }


    if ($('.scroll-pane-horizontal').length > 0) {
        $('.scroll-pane-horizontal').jScrollPane({autoReinitialise: true});
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
    if (typeof response.response != "undefined")
        location.href = '/forum/velaforfun/topic/' + response.response;
}

function reload(response) {
    location.href = '';
}
function redirectImbarco(response) {
    if (typeof response.response != "undefined")
        location.href = '/forum/velaforfun/topic/' + response.response;
}


function redirectRicetta(response) {


    location.href = '/archivio/' + response.response;
}

function checkNomeBarca(response) {
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
    } else {


    }

    $.get("/porti/localizzami/jsondata?lat=" + position.coords.latitude + "&long=" + position.coords.longitude, function (meteo) {



        // Provide your access token
        L.mapbox.accessToken = 'pk.eyJ1Ijoid2FrYXJldmEiLCJhIjoiMzhjZGQ4M2VlZThhNjZlYWZmODg1OTE2MWUyNjlkNjYifQ.jBl7tEajPnDGJzYqxMGMag';
        // Create a map in the div #map
        var mapMeteo = L.mapbox.map('mappaMeteo', 'wakareva.e12b38a9');


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


$("#meteo-localized-box").removeClass("hide");
        $("#div-localized").fadeOut();


        $("#meteo-localized-nome-2").html(meteo.geoposition.name);

        //    $("#meteo-localized-temperatura-2").html(parseInt(meteo.geoposition.main.temp) + "°");
        $("#meteo-localized-vento-2").html(meteo.geoposition.wind.speed + " km/h");
        $("#meteo-localized-umidita-2").html(meteo.geoposition.main.humidity + " %");
        $("#meteo-localized-icon-2").addClass(meteo.geoposition.weather[0].icon);


        for (var i = 0; i < 4; i++) {
            $("#meteo-localized-icon-" + (i + 3)).addClass(meteo.altroMeteo[i].weather[0].icon);
            $("#meteo-localized-time-" + (i + 3)).html(meteo.altroMeteo[i].time);

        }


        $("#meteoModal #loading").addClass("hide");
        $("#meteoModal #contenuto").removeClass("hide");



        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $("#loadingMeteoMobile").hide();

            $("#mobile-localized-city").html(meteo.geoposition.name);
            $("#mobile-localized-city").fadeIn();
            $("#mobile-localized-arrow").fadeIn();
        }

        localStorage.setItem("geolocation", JSON.stringify(meteo));

        mapMeteo.setView([position.coords.latitude, position.coords.longitude],10);

        myLayer = L.mapbox.featureLayer(arrayPorto2).addTo(mapMeteo);



        myLayer.on('ready', function() {
            // featureLayer.getBounds() returns the corners of the furthest-out markers,
            // and map.fitBounds() makes sure that the map contains these.
            mapMeteo.fitBounds(myLayer.getBounds());
        });



    });


}

