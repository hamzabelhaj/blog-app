/**
 * This File handles Users related Form Requests(GET/ POST/ UPDATE/ DELETE) using AJAX
 */

$(document).ready(function () {
    /* Escapes html chars */
    function escapeHTML(value) {
        if (typeof value === 'string') {
            return value
                .replace(/&/g, '&amp;') // Replace ampersand
                .replace(/</g, '&lt;')  // Replace less than
                .replace(/>/g, '&gt;')  // Replace greater than
                .replace(/"/g, '&quot;') // Replace double quote
                .replace(/'/g, '&apos;'); // Replace single quote
        } else {
            return value;
        }
    }

    /* Retrieve paginated users */
    $('.user-pagination').on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page') ?? 1;
        $.ajax({
            url: `${USERS_PAGE_URL}/${escapeHTML(page)}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const $container = $('.users-container');
                $container.empty();
                response.users.forEach(user => {
                    const tableRow = `
                        <tr class="user-row" data-user-id="${escapeHTML(user.id)}">
                           <td class="col-4">${escapeHTML(user.username)}</td>
                           <td class="col-4">${escapeHTML(user.email)}</td>
                            <td>
                                <span class="badge bg-${user.role === 'admin' ? 'danger' : (user.role === 'editor' ? 'warning' : (user.role === 'author' ? 'info' : 'secondary'))} ">
                                    ${escapeHTML(user.role)}
                                 </span>
                            </td>
                            <td >
                                <a href="${USERS_URL}/${escapeHTML(user.id)}/edit" class="btn btn-sm btn-outline-primary">Edit</a>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-user-btn" data-user-id="${escapeHTML(user.id)}">Delete</button>
                            </td>
                        </tr>`;
                    $container.append(tableRow);
                });
                updatePagination(response.currentPage, response.totalPages); //to update pagination links
            },
            error: function (xhr, status, error) {
                console.error('Loading users failed: ', error);
            }
        });
    });
    /* Update pagination links */
    function updatePagination(current, total) {
        const $pagination = $('.user-pagination');
        $pagination.empty();
        $pagination.append(`<li class="page-item ${current === 1 ? 'disabled' : ''}"><a href="#" class="page-link" data-page="${escapeHTML(current) - 1}">Previous</a></li>`);
        for (let i = 1; i <= total; i++) {
            $pagination.append(
                `<li class="page-item ${i === current ? 'active' : ''}">
                        <a href="#" class="page-link" data-page="${i}">${i}</a>
                    </li>`
            );
        }
        $pagination.append(`<li class="page-item ${current >= total ? 'disabled' : ''}"  ><a href="#" class="page-link" data-page="${escapeHTML(current) + 1}" >Next</a></li>`);
    }

    /* Updating current user's own profile */
    $('#profile-update-form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: UPDATE_PROFILE_URL,
            type: 'POST',
            data: {
                method: 'UPDATE',
                username: $('#username').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                csrf_token: CSRF_TOKEN,
            },
            success: function (response) {
                $('#profile-update-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Update successful...');
                setTimeout(() => {
                    window.location.href = PROFILE_URL; //get request
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
                $('#profile-update-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html(errorMsg);
            }
        });
    });

    /* Deleting current user's own profile */
    $('#delete-profile').on('click', function (e) {
        e.preventDefault();
        if (confirm("Are you sure you want to delete your profile? This cannot be undone.")) {
            $.ajax({
                url: DELETE_PROFILE_URL,
                type: 'POST',
                data: {
                    method: 'DELETE',
                    csrf_token: CSRF_TOKEN,
                },
                success: function () {
                    $('#delete-profile-feedback')
                        .removeClass('text-danger')
                        .addClass('text-success')
                        .text('Your profile has been deleted. Logging out...')
                        .fadeIn();

                    setTimeout(() => {
                        window.location.href = LOGOUT_URL; // Or any home page
                    }, 1500);
                },
                error: function (xhr) { //gets triggered by failure (http status codes)
                    alert("Failed to delete profile.");
                    console.error(xhr.responseText);
                }
            });
        }
    });

    /* Creating users */
    $('#create-user-form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: STORE_USER_URL,
            type: 'POST',
            data: {
                username: $('#username').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                repeatedPassword: $('#repeated-password').val(),
                role: $('#role').val(),
                csrf_token: CSRF_TOKEN,
            },
            success: function (response) {
                $('#create-user-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Creation successful...');
                setTimeout(() => {
                    window.location.href = USERS_URL;
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
                $('#create-user-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html(errorMsg);
            }
        });
    });

    /* Updating other users */
    $('#user-update-form').on('submit', function (e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        $.ajax({
            url: `${USERS_URL}/${escapeHTML(userId)}/update`,
            type: 'POST',
            data: {
                method: 'UPDATE',
                username: $('#username').val(),
                email: $('#email').val(),
                role: $('#role').val(),
                userId: userId,
                csrf_token: CSRF_TOKEN,
            },
            success: function (response) {
                $('#user-update-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Update successful...');
                setTimeout(() => {
                    window.location.href = USERS_URL; //get request
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
                $('#user-update-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html(errorMsg);
            }
        });
    });

    /* Deleting other users */
    $(document).on('click', '.delete-user-btn', function (e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        console.log(userId);
        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                url: `${USERS_URL}/${escapeHTML(userId)}/delete`,
                type: 'POST',
                data: {
                    method: 'DELETE',
                    csrf_token: CSRF_TOKEN,
                },
                success: function () {
                    $(`.user-row[data-user-id="${escapeHTML(userId)}"]`).fadeOut(300, function () {
                        $(this).remove();
                    });
                },
                error: function (xhr) { //gets triggered by failure (http status codes)
                    alert('Failed to delete the user.');
                    console.error(xhr.responseText);
                }
            });
        }
    });
});
