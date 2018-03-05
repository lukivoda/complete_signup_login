// we are wrapping all code in a function that secures that everything we are doing is after the page loads
$(function() {

    $("#login-form").submit(function (event) {
        //prevent default php processing
        event.preventDefault();
        //collect user inputs
        var dataWithPost = $(this).serializeArray();
        $.ajax({
            //send them to snippets/login_code.php using AJAX
            url: "snippets/login_code.php",
            //we are using POST method
            type: "POST",
            //the data we are sending to snippets/login_code.php
            data: dataWithPost,
            //Ajax call successfull:show error or success message
            //data parameter is the data we are receiving from the snippets/login_code
            success: function (data) {
                if (data === 'success') {
                    window.location = 'admin.php';
                }else{
                    $("#loginmessage").html("<div class='alert alert-danger alert-dismissible'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
                        "<span aria-hidden='true'>&times;</span>" +
                        "</button>"
                        + data + "</div>");
                }
            },
            //Ajax call fails:show Ajax call error
            error: function () {
                $("#loginmessage").html("<div class='alert alert-danger'>There was an error with the ajax call.Please try again later!</div>");
            }
        });
    });


});