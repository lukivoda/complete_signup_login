// we are wrapping all code in a function that secures that everything we are doing is after the page loads
$(function() {

    $("#register-form").submit(function (event) {
        //prevent default php processing
        event.preventDefault();
        //collect user inputs
        var dataWithPost = $(this).serializeArray();
        //console.log(dataWithPost);//[{name: "username", value: "lukivoda"},{name: "email", value: "stevanris@gmail.com"},{name: "password", value: "Steffi12"},{name: "password2", value: "Steffi12"}]
        $.ajax({
            //send them to signup.php using AJAX
            url: "snippets/register_code.php",
            //we are using POST method
            type: "POST",
            //the data we are sending to signup.php
            data: dataWithPost,
            //Ajax call successfull:show error or success message
            //data parameter is the data we are receiving from the signup.php
            success: function (data) {
                if (data === 'success') {
                    window.location = 'index.php';
                }else{
                    $("#signupmessage").html("<div class='alert alert-danger alert-dismissible'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
                        "<span aria-hidden='true'>&times;</span>" +
                        "</button>"
                        + data + "</div>");
                }
            },
            //Ajax call fails:show Ajax call error
            error: function () {
                $("#signupmessage").html("<div class='alert alert-danger'>There was an error with the ajax call.Please try again later!</div>");
            }
        });
    });


});