/**
 * This File handles Posts related Form Requests (GET/ POST/ UPDATE/ DELETE) using AJAX
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

    /* Retrieving dashboard paginated posts */
    $('.dashboard-pagination').on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page') ?? 1;
        //console.log(escapeHTML(page));
        $.ajax({
            url: `${DASHBOARD_POSTS_PAGE_URL}/${escapeHTML(page)}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const $container = $('.dashboard-posts-container');
                $container.empty();
                response.posts.forEach(post => {
                    const tableRow = `
                        <tr class="post-row" data-post-id="${escapeHTML(post.id)}">
                           <td class="col-8">${escapeHTML(post.title)}</td>
                            <td>
                                <span class="badge bg-${post.status === 'published' ? 'success' : (post.status === 'draft' ? 'secondary' : 'warning')}">
                                    ${post.status === 'published' ? 'Published' : (post.status === 'draft' ? 'Draft' : '')}
                                </span>
                            </td>
                            <td>
                                <a href="${DASHBOARD_POSTS_URL}/${escapeHTML(post.id)}/edit" class="btn btn-sm btn-outline-primary">Edit</a>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-post-btn" data-post-id="${escapeHTML(post.id)}">Delete</button>
                                <a href="${POSTS_URL}/${escapeHTML(post.id)}" class="btn btn-sm btn-outline-secondary">View</a>
                            </td>
                        </tr>`;
                    $container.append(tableRow);
                });
                updatePagination(response.currentPage, response.totalPages, '.dashboard-pagination'); //update pagination links
            },
            error: function (error) {
                console.error('Loading posts failed: ', error);
            }
        });
    });

    /* Retrieving public paginated posts */
    $('.public-pagination').on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page') ?? 1;
        $.ajax({
            url: `${POSTS_PAGE_URL}/${escapeHTML(page)}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const $container = $('.public-posts-container');
                $container.empty();
                response.posts.forEach(post => {
                    const postCard = `
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header d-flex justify-content-between">
                                <span>${new Date(post.created_at).toLocaleDateString()}</span>
                                <span>By ${escapeHTML(post.username)}</span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">${escapeHTML(post.title)}</h5>
                                <p class="card-text">${post.body}</p>
                                <a href="${POSTS_URL}/${escapeHTML(post.id)}" class="btn btn-primary btn-sm">Read More</a>
                            </div>
                        </div>`;
                    $container.append(postCard);
                });
                updatePagination(response.currentPage, response.totalPages, '.public-pagination'); //to update pagination links
            },
            error: function (xhr, status, error) {
                console.error('Loading posts failed: ', error);
            }
        });
    });
    /* To update pagination links */
    function updatePagination(current, total, paginationSelector) {
        const $pagination = $(paginationSelector);
        $pagination.empty();

        // Previous
        if (current > 1) {
            $pagination.append(`<li class="page-item"><a class="page-link" data-page="${escapeHTML(current) - 1}" href="#">Previous</a></li>`);
        } else {
            $pagination.append(`<li class="page-item disabled"><span class="page-link">Previous</span></li>`);
        }

        // Page numbers
        for (let i = 1; i <= total; i++) {
            $pagination.append(`<li class="page-item ${i === current ? 'active' : ''}"><a class="page-link" data-page="${i}" href="#">${i}</a></li>`);
        }

        // Next
        if (current < total) {
            $pagination.append(`<li class="page-item"><a class="page-link" data-page="${escapeHTML(current) + 1}" href="#">Next</a></li>`);
        } else {
            $pagination.append(`<li class="page-item disabled"><span class="page-link">Next</span></li>`);
        }
    }

    /* Creating new posts */
    $('#create-post-form').on('submit', function (e) {
        e.preventDefault();
        const isPublishChecked = $('#publish').is(':checked');
        $.ajax({
            url: STORE_POST_URL,
            type: 'POST',
            data: {
                title: $('#title').val(),
                body: $('#body').val(),
                publish: isPublishChecked ? 1 : 0, //values sent as strings
                csrf_token: CSRF_TOKEN,
            },
            success: function () {
                console.log("success");
                $('#create-post-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Creation successful...');
                setTimeout(() => {
                    window.location.href = DASHBOARD_POSTS_URL; // Or any home page
                }, 1000);
            },
            error: function (xhr) { //gets triggered by failure (http status codes)
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
                $('#create-post-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html(errorMsg);
            }
        });
    });

    /* Updating posts */
    $('#edit-post-form').on('submit', function (e) {
        e.preventDefault();
        const postId = $(this).data('post-id');
        const isPublishChecked = $('#publish').is(':checked');
        $.ajax({
            url: `${DASHBOARD_POSTS_URL}/${escapeHTML(postId)}/update`,
            type: 'POST',
            data: {
                title: $('#title').val(),
                body: $('#body').val(),
                postId: postId,
                publish: isPublishChecked ? 1 : 0, //values sent as strings
                csrf_token: CSRF_TOKEN,
            },
            success: function () {
                console.log("success");
                $('#update-post-feedback')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .text('Update successful...');
                setTimeout(() => {
                    window.location.href = DASHBOARD_POSTS_URL; // Or any home page
                }, 1000);
            },
            error: function (xhr) { //gets triggered by failure (http status codes)
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
                $('#update-post-feedback')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html(errorMsg);
            }
        });
    });

    /* Deleting posts */
    $(document).on('click', '.delete-post-btn', function (e) {
        e.preventDefault();
        const postId = $(this).data('post-id');
        if (confirm("Are you sure you want to delete this post?")) {
            $.ajax({
                url: `${DASHBOARD_POSTS_URL}/${escapeHTML(postId)}/delete`,
                type: 'POST',
                data: {
                    method: 'DELETE',
                    csrf_token: CSRF_TOKEN,
                },
                success: function () {
                    $(`.post-row[data-post-id="${escapeHTML(postId)}"]`).fadeOut(300, function () {
                        $(this).remove();
                    });
                },
                error: function (xhr) { //gets triggered by failure (http status codes)
                    alert('Failed to delete the post.');
                    console.error(xhr.responseText);
                }
            });
        }
    });

});