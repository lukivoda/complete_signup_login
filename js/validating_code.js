// we are wrapping all code in a function that secures that everything we are doing is after the page loads
$(function() {

    $("#validate_code").submit(function (event) {
        //prevent default php processing
        event.preventDefault();
        //collect user inputs and queries
        var email_q = getQueryVariable('email');
        var validation_code_q = getQueryVariable('code');
        var validation_code_i = $("#code").val();
        $.ajax({
            //send them to snippets/validating_code.php using AJAX
            url: "snippets/validating_code.php",
            //we are using POST method
            type: "POST",
            //the data we are sending to server
            data: {email_q:email_q,validation_code_q:validation_code_q,validation_code_i:validation_code_i},
            //Ajax call successfull:show error or success message
            //data parameter is the data we are receiving from the server
            success: function (data) {
                if (data === 'success') {
                   window.location = "reset.php?email="+email_q+"&code="+validation_code_q;
                }else{
                    $("#validation_code_message").html("<div class='alert alert-danger alert-dismissible'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
                        "<span aria-hidden='true'>&times;</span>" +
                        "</button>"
                        + data + "</div>");

                }
            },
            //Ajax call fails:show Ajax call error
            error: function () {
                $("#validation_code_message").html("<div class='alert alert-danger'>There was an error with the ajax call.Please try again later!</div>");
            }
        });
    });



    function getQueryVariable(variable)
    {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            if(pair[0] == variable){return pair[1];}
        }
        return(false);
    }



});



