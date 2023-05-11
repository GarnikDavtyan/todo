
$('#add-task').on('click', () => {

    $('.error-response').addClass('d-none');
    let taskInput = $('#task');
    let task = taskInput.val();
    let userId = taskInput.attr('attr-user-id');
    let list = $('#list')

    $.ajax({
        url: '/todos',
        method: 'POST',
        data: {
            user_id: userId,
            task: task 
        },
        success: function(response) {
            let filterKeyword = $('#keyword').val();
            let isTagFilterSelected = $('#tags-select option:selected').length > 0

            if (!isTagFilterSelected && response.todo.task.includes(filterKeyword)) {
                let newTask = `
                    <li class="list-group-item" attr-id="${response.todo.id}">
                        <div id="task-${response.todo.id}" class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="d-none image-container">
                                    <a class="task-img-link" href="" target="blank">
                                        <div class="position-relative">
                                            <img src="" class="img-thumbnail me-1 task-img" alt="img" width="150" height="150">
                                            <button type="button" class="delete-image btn btn-danger btn-sm position-absolute bottom-0 end-0 mb-1 me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </a>
                                </div>
                                <input class="form-check-input done-task" type="checkbox"  @checked(response.todo.done)/>
                                <span id="task-span-${response.todo.id}" class="text-break ms-1">${response.todo.task}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-warning edit-task">Edit</button>
                                <button class="btn btn-danger delete-task ms-1">Delete</button>
                                <button id="add-tag-enable-${response.todo.id}" class="btn btn-primary add-tag-enable ms-1 text-nowrap">Add a tag</button>
                            </div>
                        </div>
                        <div id="edit-task-${response.todo.id}" class="d-none row">
                            <div class="row col-md-10">
                                <input type="text" class="form-control edit-task-input col-md-12" value="${response.todo.task}"/>
                                <div class="d-flex align-items-center mt-1 col-md-6">
                                    <label for="formFileSm" class="image-label form-label text-nowrap m-0">Add Image</label>
                                    <input class="image-input form-control form-control-sm ms-1" id="formFileSm" type="file" accept="image/png, image/gif, image/jpeg">
                                </div>
                            </div>
                            <div class="d-flex align-items-start col-md-2">
                                <button class=" btn btn-success mx-1 save-task">Save</button>
                                <button class="btn btn-secondary cancel-edit">Cancel</button>
                            </div>
                        </div>
                        <div id="tag-container-${response.todo.id}" class="d-flex align-items-center d-none">
                            <div id="tag-list-${response.todo.id}" class="d-flex flex-wrap"></div>
                        </div>
                        <div id="add-tag-container-${response.todo.id}" class="d-none d-flex justify-content-between align-items-center mt-2">
                            <input type="text" class="form-control add-tag-input" placeholder="Add tag">
                            <button class="btn btn-success add-tag mx-1">Add</button>
                            <button class="btn btn-secondary cancel-tag">Cancel</button>
                        </div>
                    </li>
                `;

                $('#filter-container').removeClass('d-none');

                list.append(newTask);
            }
            taskInput.val('');
        },
        error: function(xhr) {
            $('.error-response').text(xhr.responseJSON.message);
            $('.error-response').removeClass('d-none');
        }
    });
});

$('#list').on('click', '.edit-task', function() {
    let id = $(this).closest('li').attr('attr-id');

    $('#task-' + id).addClass('d-none');
    $('#edit-task-' + id).removeClass('d-none');
});

$('#list').on('click', '.cancel-edit', function() {
    let listItem = $(this).closest('li');
    let imageInput = listItem.find('.image-input');
    let id = listItem.attr('attr-id');

    let taskInput = listItem.find('.edit-task-input');
    let taskSpan = $('#task-span-' + id);
    taskInput.val(taskSpan.text());
    imageInput.val('');

    $('#task-' + id).removeClass('d-none');
    $('#edit-task-' + id).addClass('d-none');
});

$('#list').on('click', '.save-task', editTask);

$('#list').on('change', '.done-task', editTask);

function editTask() {
    $('.error-response').addClass('d-none');
    let listItem = $(this).closest('li');
    let id = listItem.attr('attr-id');
    let taskInput = listItem.find('.edit-task-input');
    let doneInput = listItem.find('.done-task');
    let imageInput = listItem.find('.image-input');
    let taskImage = listItem.find('.task-img');
    let taskImageLink = listItem.find('.task-img-link');
    let imageContainer = listItem.find('.image-container');
    
    let taskSpan = $('#task-span-' + id);

    let data = new FormData();
    data.append('task', taskInput.val());
    data.append('done', doneInput.is(":checked") ? 1 : 0);
    if (imageInput[0].files[0]) {
        data.append('image', imageInput[0].files[0]);
    }
    data.append('_method', 'PUT');

    $.ajax({
        url: `/todos/${id}`,
        method: 'POST',
        data: data,
        processData: false,
        contentType: false,
        success: function(response) {
            let filterKeyword = $('#keyword').val();
            if (response.todo.task.includes(filterKeyword)) {
                taskInput.val(response.todo.task);
                if(response.todo.done == true) {
                    doneInput.attr('checked', true)
                }

                if(response.todo.image) {
                    taskImage.attr('src', 'storage/' + response.todo.image)
                    taskImageLink.attr('href', 'storage/' + response.todo.image)
                    imageContainer.removeClass('d-none');
                    let imageLabel = listItem.find('.image-label');
                    imageLabel.text('Change Image');
                    imageInput.val('');
                }

                taskSpan.text(response.todo.task);
                $('#task-' + id).removeClass('d-none');
                $('#edit-task-' + id).addClass('d-none');
            } else {
                listItem.remove();
            }
        },
        error: function(xhr) {
            $('.error-response').text(xhr.responseJSON.message);
            $('.error-response').removeClass('d-none');
        }
    });
}

$('#list').on('click', '.delete-task', function() {
    let listItem = $(this).closest('li');
    let id = listItem.attr('attr-id');
    $.ajax({
        url: `/todos/${id}`,
        method: 'DELETE',
        success: function(response) {
            listItem.remove();
            if(!response.hasTodoItem) {
                $('#filter-container').addClass('d-none');
            }
        }
    });
});

$('#list').on('click', '.add-tag-enable', function() {
    let id = $(this).closest('li').attr('attr-id');

    $('#add-tag-container-' + id).removeClass('d-none');
    $('#add-tag-enable-' + id).addClass('d-none');
});

$('#list').on('click', '.cancel-tag', function() {
    let listItem = $(this).closest('li');
    let id = listItem.attr('attr-id');

    $('#add-tag-container-' + id).addClass('d-none');
    $('#add-tag-enable-' + id).removeClass('d-none');
});

$('#list').on('click', '.add-tag', function() {
    $('.error-response').addClass('d-none');
    let listItem = $(this).closest('li');
    let id = listItem.attr('attr-id');
    
    let tagInput = listItem.find('.add-tag-input');

    $.ajax({
        url: `/todos/${id}/tag`,
        method: 'POST',
        data: { name: tagInput.val() },
        success: function(response) {
            $('#tag-container-' + id).removeClass('d-none');
            if (response.isNew) {
                $('#tag-list-' + id).append(
                    `<span class="badge bg-info ms-1 mt-1 d-flex align-items-center">
                        ${response.tag.name}
                        <button type="button" class="delete-tag btn btn-info btn-sm" attr-id="${response.tag.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                            </svg>
                        </button>
                    </span>`
                );

                let existingValues = $('#tags-select option').map(function() {
                    return Number($(this).val());
                }).get();
                if (!existingValues.includes(response.tag.id)) {
                    $('#tags-select').append(`<option value="${response.tag.id}">${response.tag.name}</option>`);
                }
            } else {
                $('.error-response').text('Already exists');
                $('.error-response').removeClass('d-none');
            }
        },
        error: function(xhr) {
            $('.error-response').text(xhr.responseJSON.message);
            $('.error-response').removeClass('d-none');
        }
    });
});

$('#list').on('click', '.delete-tag', function() {
    let listItem = $(this).closest('li');
    let taskId = listItem.attr('attr-id');
    let tagId = $(this).attr('attr-id');
    let span = $(this).closest('span');
    $.ajax({
        url: `/todos/${taskId}/tag/${tagId}`,
        method: 'DELETE',
        success: function() {
            span.remove();
        }
    });
});

$('#list').on('click', '.delete-image', function(e) {
    e.preventDefault();

    let listItem = $(this).closest('li');
    let imageContainer = listItem.find('.image-container');
    let id = listItem.attr('attr-id');
    $.ajax({
        url: `/todos/${id}/image`,
        method: 'DELETE',
        success: function() {
            imageContainer.addClass('d-none');
            let imageLabel = listItem.find('.image-label');
            imageLabel.text('Add Image');
        }
    });
});

$('#access-form').on('submit', function(e) {
    e.preventDefault();
    $('.success-response').addClass('d-none');
        
    $.ajax({
        url: '/todos/give-access',
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            $('.success-response').text(response.message);
            $('.success-response').removeClass('d-none');
        }
    });
});

$( document ).ready(function() {
    $("#tags-select").select2({
        placeholder: "Select tags"
    });
});