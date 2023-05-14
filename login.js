let error = $('#error');
let info = $('#info');
let infoHeader = $('#infoHeader');

$(document).ready(() => {
    $("form").submit((e) => {
        e.preventDefault();

        const formData = {
            username: $('#username').val(),
            password: $('#password').val(),
        }

        // Validation Checks
        if (/[^a-zA-Z0-9]/g.test(formData.username)) {
            error.text('Username can only contain alphabets and numbers');
            $('#username').focus();
            return;
        }

        if (formData.password.length < 8) {
            error.text('Password must be at least 8 characters');
            $('#password').focus();
            return;
        }

        $.ajax({
            method: "POST",
            url: './login.php',
            data: formData,
        }).done((data) => {
            console.log(data);
            infoHeader.css('display', 'block');

            // Display error message
            if (JSON.parse(data).error) {
                error.text(JSON.parse(data).error);
                info.text(JSON.parse(data).error);
                return;
            }

            // Display success message
            info.text("Login Successful");
            infoHeader.css('background-color', 'green');
            info.css('color', 'white');

            // Reset Form
            setTimeout(() => {
                $('form').reset();
            }, 2000);
        }).fail((err) => {
            error.text(err.error);
            console.log(err);
        });

    });
});