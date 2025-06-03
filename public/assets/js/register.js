/**
 * This File handles Register Form POST Request using AJAX
 */

$(document).ready(function () {
    $('#register-form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: REGISTER_URL,
            type: 'POST',
            data: {
                username: $('#username').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                repeatedPassword: $('#repeated-password').val(),
                csrf_token: CSRF_TOKEN,
            },
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
});