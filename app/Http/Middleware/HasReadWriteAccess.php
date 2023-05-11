<?php

namespace App\Http\Middleware;

use App\Helpers\SharedAccessHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasReadWriteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->user_id;

        if(!$userId) {
            $todo = $request->route()->parameter('todo');
            $userId = $todo->user_id;
        }

        if($userId != Auth::id()) {
            $access = Auth::user()->gottenAccesses()->where('users.id', $userId)->first();
 
            abort_if(!$access || $access->pivot->permission != SharedAccessHelper::READWRITE , 403);
        }
        
        return $next($request);
    }
}
