function postForm( $form, callback ){

    /*
     * Get all form values
     */
    var values = {};
    $.each( $form.serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });


    var formData = new FormData($form[0]);
    /*
     * Throw the form values to the server!
     */
    $.ajax({
        type        : $form.attr( 'method' ),
        url         : $form.attr( 'action' ),
        data        : formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success     : function(data) {
            callback( data );
        }
    });

}


function showalert(obj,message,alerttype) {
    if($(".response-form").length>0){
        $(".response-form").html('<div role="alert" id="alertdiv" class="alert alert-' +  alerttype + '"><span>'+message+'</span></div>')
    }else{
        obj.append('<div role="alert" id="alertdiv" class="alert alert-' +  alerttype + '"><span>'+message+'</span></div>')
    }

    setTimeout(function() { // this will automatically close the alert and remove this if the users doesnt close it in 5 secs

        $("#alertdiv").fadeOut();

    }, 6000);
}
