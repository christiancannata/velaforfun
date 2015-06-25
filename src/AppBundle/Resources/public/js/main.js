jQuery(document).ready(function ($) {



    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {

        }
    }


    /*if (typeof localStorage.getItem("geolocation") != undefined) {
     var meteo = JSON.parse(localStorage.getItem("geolocation"));
     if (meteo != null) {
     $("#meteo-localized-nome").html(meteo.geoposition.name);

     $("#meteo-localized-temperatura").html(parseInt(meteo.geoposition.main.temp) + "°");
     $("#meteo-localized-vento").html(meteo.geoposition.wind.speed + " km/h");
     $("#meteo-localized-umidita").html(meteo.geoposition.main.humidity + " %");
     $("#meteo-localized-icon").addClass(meteo.geoposition.weather[0].icon);

     $("#meteo-localized-box").removeClass("hide");
     $("#div-localized").fadeOut();
     }

     } */


    $('select').selectpicker(
        {title: "Seleziona un elemento"}
    );

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


    $('.scroll-pane').jScrollPane();


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
    location.href = '/forum/velaforfun/topic/' + response.response;
}
function redirectImbarco(response) {


    location.href = '/forum/velaforfun/topic/' + response.response;
}


function redirectRicetta(response) {


    location.href = '/archivio/' + response.response;
}


function setRisultatiAnnunciImbarco(response) {
    var risultati = "";
    for (var i = 0; i < response.length; i++) {
        var risultato = response[i];

        risultati += '<tr> <td>' + risultato.topic.title + '</td> <td>' + risultato.timestamp + '</td> <td>0</td> <td> <button onclick="location.href=\'/forum/velaforfun/topic/' + risultato.topic.id + '\'" class="btn btn-primary hvr-glow" type="button">Leggi Annuncio</button> </td> </tr>';

    }
    if (response.length == 0) {
        risultati = "<tr><td colspan=5 >La ricerca non ha prodotto risultati</td></tr>";
    }

    $("table tbody").html(risultati);
    if ($("#notificaAnnuncio:checked").length > 0) {
        $("#annuncioCreato").modal();

    }

}

function riceviNotifica(response) {
    $("#modalVideo").hide();
    $("#annuncioCreato").modal();

}


function setRisultatiScambioPosto(response) {
    var risultati = "";
    for (var i = 0; i < response.length; i++) {
        var risultato = response[i];
        risultati += '<tr> <td>' + risultato.topic.title + '</td> <td>' + risultato.timestamp + '</td> <td>0</td> <td> <button onclick="location.href=\'/forum/velaforfun/topic/' + risultato.topic.id + '\'" class="btn btn-primary hvr-glow" type="button">Leggi Annuncio</button> </td> </tr>';

    }
    if (response.length == 0) {
        risultati = "<tr><td colspan=5 >La ricerca non ha prodotto risultati</td></tr>";
    }

    $("table tbody").html(risultati);

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


    if( ! /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        $('#meteoModal').modal();
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

        localStorage.setItem("geolocation", JSON.stringify(meteo));

        map.setView([position.coords.latitude, position.coords.longitude], 9);



        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {


            $("#mobile-localized-city").html(meteo.geoposition.name);
            $("#mobile-localized").fadeIn();


        }



    });





}