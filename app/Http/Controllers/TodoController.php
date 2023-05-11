<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTagRequest;
use App\Models\Todo;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user;
        $permission = $request->permission;

        $tags = Tag::whereHas('todos', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $todoQuery = Todo::with('tags')->where('user_id', $user->id);

        $hasTodoItem = $todoQuery->exists();

        $keywordFilter = $request->keyword;
        if($keywordFilter) {
            $todoQuery->where('task', 'LIKE', '%'.$request->keyword.'%');
        }

        $tagsFilter = $request->tags;
        if($tagsFilter) {
            $todoQuery->whereHas('tags', function ($query) use ($tagsFilter) {
                $query->whereIn('tag_id', $tagsFilter);
            });
        }

        $todos = $todoQuery->get();
        
        return view('todo', compact(
            'todos', 
            'tags', 
            'keywordFilter', 
            'tagsFilter', 
            'user', 
            'permission',
            'hasTodoItem'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        $todo = Todo::create([
            'user_id' => $request->user_id,
            'task' => $request->task
        ]);
        

        return response()->json(compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        try {
            $data = [
                'task' => $request->task,
                'done' => $request->done,
            ];

            $oldImage = '';
            $ImagePath = '';
            if($request->hasFile('image')) {
                if($todo->image) {
                    $oldImage = $todo->image;
                }

                $ImagePath = Storage::putFile('images', $request->file('image'));
                
                $data['image'] = $ImagePath;
            }
            $todo->update($data);

            if ($oldImage) {
                Storage::delete($oldImage);
            }

            return response()->json(compact('todo'));
        } catch (Exception $e) {
            Storage::delete($ImagePath); 

            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        try {
            DB::beginTransaction();

            $userId = $todo->user_id;

            $todo->tags()->detach();
            $image = $todo->image;
            
            $todo->delete();

            DB::commit();

            if ($image) {
                Storage::delete($image);
            }

            $hasTodoItem = Todo::where('user_id', $userId)->exists();

            return response()->json(compact('hasTodoItem'));
        } catch (Exception $e) {
            DB::rollBack(); 

            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function addTag(AddTagRequest $request, Todo $todo)
    {
        $tag = Tag::firstOrCreate(['name' => $request->name]);

        $isNew = false;
        if (!$todo->tags()->where('tags.id', $tag->id)->exists()) {
            $todo->tags()->attach($tag->id);
            $isNew = true;
        }

        return response()->json(compact('tag', 'isNew'));
    }

    public function deleteTag(Todo $todo, int $tagId)
    {
        $todo->tags()->detach($tagId);

        return response()->json(['message' => 'Deleted']);
    }

    public function deleteImage(Todo $todo)
    {
        Storage::delete($todo->image);
        
        $todo->image = null;
        $todo->save();

        return response()->json(['message' => 'Deleted']);
    }
    
    public function giveAccessView()
    {
        $users = User::where('id', '<>', Auth::id())->get();

        return view('give-access', compact('users'));
    }

    public function giveAccess(Request $request)
    {
        Auth::user()->givenAccesses()->syncWithoutDetaching([
            $request->user => ['permission' => $request->permission]
        ]);
        
        return response()->json(['message' => 'You shared your list']);
    }
}
