// we are wrapping all code in a function that secures that everything we are doing is after the page loads
$(function() {

    $("#recover-form").submit(function (event) {
        //prevent default php processing
        event.preventDefault();
        //collect user inputs
        var dataWithPost = $(this).serializeArray();
        $.ajax({
            //send them to signup.php using AJAX
            url: "snippets/recover_code.php",
            //we are using POST method
            type: "POST",
            //the data we are sending to server
            data: dataWithPost,
            //Ajax call successfull:show error or success message
            //data parameter is the data we are receiving from the server
            success: function (data) {
                if (data === 'success') {
                    $("#recovermessage").html("<div class='alert alert-success'>"
                        + 'Email successfully sent.Please check your email for the reset code!' + "</div>");
                }else{
                    $("#recovermessage").html("<div class='alert alert-danger alert-dismissible'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
                        "<span aria-hidden='true'>&times;</span>" +
                        "</button>"
                        + data + "</div>");
                }
            },
            //Ajax call fails:show Ajax call error
            error: function () {
                $("#recovermessage").html("<div class='alert alert-danger'>There was an error with the ajax call.Please try again later!</div>");
            }
        });
    });


});