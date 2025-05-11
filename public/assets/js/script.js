$(document).ready(function () {

    // Handle register form
    $('#register-form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: REGISTER_URL,
            type: 'POST',
            data: $(this).serialize(), // it converts all the form input values into a string that can be sent in a URL or HTTP request body (typically for POST). (sends values associated to the name attributes in the form)
            dataType: 'json', // to save parsing
            success: function (response) {
                $('#register-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Registration successful...');

                setTimeout(() => {
                    window.location.href = LOGIN_URL;
                }, 1500);
            },
            error: function (xhr) { //xml http request
                let errorMsg = '';
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (Array.isArray(res.error)) {
                        res.error.forEach(err => {
                            errorMsg += `<div>${err}</div>`;
                        });
                    } else if (typeof res.error === 'string') {
                        errorMsg += `<div>${res.error}</div>`;
                    } else {
                        errorMsg += '<div>An error occurred.</div>';
                    }

                } catch (e) {
                    console.error('Invalid JSON', e);
                }
                $('#register-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html(errorMsg);
            }
        });
    });

    // Handle login form
    $('#login-form').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: LOGIN_URL,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function () {
                $('#login-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Login successful...');

                setTimeout(() => {
                    window.location.href = HOME_URL; // Or any home page
                }, 1000);
            },
            error: function (xhr) { //gets triggered by failure http status codes
                let errorMsg = '';
                try {
                    const res = JSON.parse(xhr.responseText);
                   if (typeof res.error === 'string') {
                        errorMsg += `<div>${res.error}</div>`;
                    } else {
                        errorMsg += '<div>An error occurred.</div>';
                    }
                } catch (e) {
                    console.error('Invalid JSON', e);
                }

                $('#login-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html(errorMsg);
            }
        });
    });

});
