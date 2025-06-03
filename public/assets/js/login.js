/**
 * This File handles Login Form POST Request using AJAX
 */

$(document).ready(function () {
    // Handle login form
    $('#login-form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: LOGIN_URL,
            type: 'POST',
            data: {
                email: $('#email').val(),
                password: $('#password').val(),
                csrf_token: CSRF_TOKEN,
            },
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
