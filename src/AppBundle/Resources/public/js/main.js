jQuery(document).ready(function ($) {


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


    $.get('/porti/jsondata', function (data) {
        $("#selectPorto").typeahead({source: data});
    }, 'json');

    $.get('/nodi/jsondata', function (data) {
        $("#selectNodo").typeahead({source: data, valueKey: 'permalink'});
    }, 'json');


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



function setNuovoCommentoPorto(){

};