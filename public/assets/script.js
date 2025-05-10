$(document).ready(function () {

    // Handle register form
    $('#register-form').on('submit', function (e) {
        e.preventDefault(); 
        $.ajax({
            url: '/register',
            type: 'POST',
            data: $(this).serialize(), // it converts all the form input values into a string that can be sent in a URL or HTTP request body (typically for POST). (sends values associated to the name attributes in the form)
            datatype: 'json',
            success: function (response) {
                $('#register-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Registration successful...');

                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            },
            error: function (res) {
                console.log("failure");
                const errorMsg = Array.isArray(res.error)
                    ? res.error.join(', ')
                    : res.error || 'An error occurred.';
                    
                $('#register-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .text(errorMsg);
            }
        });
    });

    // Handle login form
    $('#login-form').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/login',
            type: 'POST',
            data: $(this).serialize(),
            success: function () {
                $('#login-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('✅ Login successful! Redirecting...');

                setTimeout(() => {
                    window.location.href = '/dashboard'; // Or any home page
                }, 1000);
            },
            error: function (xhr) {
                let res = {};
                try {
                    res = JSON.parse(xhr.responseText);
                } catch {}

                const errorMsg = res.error || 'Invalid credentials.';
                $('#login-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .text(errorMsg);
            }
        });
    });

});
