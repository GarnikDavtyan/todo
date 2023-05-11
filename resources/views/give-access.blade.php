@extends('layouts.app')

@section('content')

    <div class="container mt-1">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">Give access</div>
                    <div class="card-body">
                        @if(!$users->isEmpty())
                            <form id="access-form" class="row justify-content-center">
                                <label for="user" class="mt-2">Users</label>
                                <select id="users-select" name="user" class="form-select">
                                    @foreach($users as $user) 
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                                
                                <label for="permission" class="mt-2">Permission</label>
                                <select id="permissions-select" name="permission" class="form-select">
                                    @foreach(\App\Helpers\SharedAccessHelper::permissions as $permission)
                                        <option value="{{$permission['value']}}">{{$permission['name']}}</option>
                                    @endforeach
                                </select>

                                <div class="success-response alert alert-success d-none my-1"></div>

                                <button type="submit" class="btn btn-success col-md-3 mt-3">Save</button>
                            </form>
                        @else
                            <div class="success-response alert alert-danger my-1">No other users</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
