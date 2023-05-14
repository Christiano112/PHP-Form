let error = $('#error');
let info = $('#info');
let infoHeader = $('#infoHeader');

$(document).ready(() => {
    $("form").submit((e) => {
        e.preventDefault();

        const formData = {
            fname: $('#fname').val(),
            lname: $('#lname').val(),
            username: $('#username').val(),
            gender: $('#gender').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            confirm_password: $('#confirm_password').val(),
        }


        // Validation Checks
        if (/[^a-zA-Z]/g.test(formData.fname) || /[^a-zA-Z]/g.test(formData.lname)) {
            error.text('Name can only contain alphabets');
            /[^a-zA-Z]/g.test(formData.fname) ? $('#fname').focus() : $('#lname').focus();
            return;
        }

        if (/[^a-zA-Z0-9]/g.test(formData.username)) {
            error.text('Username can only contain alphabets and numbers');
            $('#username').focus();
            return;
        }

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(formData.email)) {
            error.text('Email is not valid');
            $('#email').focus();
            return;
        }

        if (formData.password.length < 8) {
            error.text('Password must be at least 8 characters');
            $('#password').focus();
            return;
        } else if (formData.password !== formData.confirm_password) {
            error.text('Passwords do not match');
            $('#confirm_password').focus();
            return;
        }

        $.ajax({
            method: "POST",
            url: '../php/index.php',
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
            infoHeader.css('background-color', 'green');
            info.css('color', 'white');
            info.text('Account created successfully. Please login to continue');

            // Redirect to login page after 3 seconds
            setTimeout(() => {
                window.location.href = './login.html';
            }, 3000);
        }).fail((err) => {
            error.text(err.error);
            console.log(err);
        });

        // console.log(formData);
    });
});