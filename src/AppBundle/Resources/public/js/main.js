jQuery(document).ready(function ($) {

    $('select').selectpicker();

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

