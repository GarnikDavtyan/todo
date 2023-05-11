@extends('layouts.app')

@section('content')

    <div class="container mt-1">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">{{$user->name}}'s list</div>
                    <div class="card-body">
                        @if($permission == \App\Helpers\SharedAccessHelper::READWRITE)
                            <div class="error-response alert alert-danger d-none"></div>
                            <div class="d-flex justify-content-between align-items-center">
                                <input id="task" type="text" class="col-md-11" placeholder="Enter task" attr-user-id="{{$user->id}}" />
                                <button id="add-task"class="btn btn-success">Add</button>
                            </div>
                            
                            <hr>
                        @endif
                        <form id="filter-container" method="GET" action="/todos" class="{{$hasTodoItem ? '' : 'd-none'}}">
                            @if(request()->has('user_id'))
                                <input type="hidden" name="user_id" value="{{ request()->input('user_id') }}">
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="keyword">Filter by keyword:</label>
                                        <input type="text" class="form-control" id="keyword" name="keyword" value="{{$keywordFilter}}" placeholder="Enter keyword">
                                    </div>
                                </div>
                                @if($tags)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tags">Filter by tags:</label>
                                            <select id="tags-select" class="w-100" name="tags[]" multiple>
                                                @foreach($tags as $tag)
                                                    <option {{$tagsFilter && in_array($tag->id, $tagsFilter) ? 'selected' : ''}} value="{{$tag->id}}">{{$tag->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-12 text-center my-1">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                        <ul id="list" class="list-group">
                            @foreach($todos as $todo)
                                @include('partials.todo-item', compact('todo', 'user', 'permission'))
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
