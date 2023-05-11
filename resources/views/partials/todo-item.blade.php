<li class="list-group-item" attr-id="{{$todo->id}}">
    <div id="task-{{$todo->id}}" class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="{{$todo->image ? '' : 'd-none'}} image-container">
                <a class="task-img-link" href="{{ $todo->image ? 'storage/' . $todo->image : ''}}" target="blank">
                    <div class="position-relative">
                        <img src="{{ $todo->image ? 'storage/' . $todo->image : ''}}" class="img-thumbnail me-1 task-img" alt="img" width="150" height="150">
                        @if($permission == \App\Helpers\SharedAccessHelper::READWRITE)
                            <button type="button" class="delete-image btn btn-danger btn-sm position-absolute bottom-0 end-0 mb-1 me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </a>
            </div>
            <input class="form-check-input done-task" type="checkbox" @checked($todo->done) @disabled($permission == \App\Helpers\SharedAccessHelper::READ)/>
            <span id="task-span-{{$todo->id}}" class="text-break ms-1">{{$todo->task}}</span>
        </div>
        @if($permission == \App\Helpers\SharedAccessHelper::READWRITE)
            <div class="d-flex align-items-center">
                <button class="btn btn-warning edit-task">Edit</button>
                <button class="btn btn-danger delete-task ms-1">Delete</button>
                <button id="add-tag-enable-{{$todo->id}}" class="btn btn-primary add-tag-enable ms-1 text-nowrap">Add a tag</button>
            </div>
        @endif
    </div>
    @if($permission == \App\Helpers\SharedAccessHelper::READWRITE)
        <div id="edit-task-{{$todo->id}}" class="d-none row">
            <div class="row col-md-10">
                <input type="text" class="form-control edit-task-input col-md-12" value="{{$todo->task}}"/>
                <div class="d-flex align-items-center mt-1 col-md-6">
                    <label for="formFileSm" class="image-label form-label text-nowrap m-0">{{$todo->image ? 'Change' : 'Add'}} Image</label>
                    <input class="image-input form-control form-control-sm ms-1" id="formFileSm" type="file" accept="image/png, image/gif, image/jpeg">
                </div>
            </div>
            <div class="d-flex align-items-start col-md-2">
                <button class=" btn btn-success mx-1 save-task">Save</button>
                <button class="btn btn-secondary cancel-edit">Cancel</button>
            </div>
        </div>
    @endif
    <div id="tag-container-{{$todo->id}}" class="d-flex align-items-center {{$todo->tags->isEmpty() ? 'd-none' : ''}}">
        <div id="tag-list-{{$todo->id}}" class="d-flex flex-wrap">
            @foreach($todo->tags as $tag)
                <span class="badge bg-info ms-1 mt-1 d-flex align-items-center">
                    {{$tag->name}}
                    @if($permission == \App\Helpers\SharedAccessHelper::READWRITE)
                        <button type="button" class="delete-tag btn btn-info btn-sm" attr-id="{{$tag->id}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                            </svg>
                        </button>
                    @endif
                </span>
            @endforeach
        </div>
    </div>
    @if($permission == \App\Helpers\SharedAccessHelper::READWRITE)
        <div id="add-tag-container-{{$todo->id}}" class="d-none d-flex justify-content-between align-items-center mt-2">
            <input type="text" class="form-control add-tag-input" placeholder="Add tag">
            <button class="btn btn-success add-tag mx-1">Add</button>
            <button class="btn btn-secondary cancel-tag">Cancel</button>
        </div>
    @endif
</li>