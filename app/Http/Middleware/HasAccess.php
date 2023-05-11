<?php

namespace App\Http\Middleware;

use App\Helpers\SharedAccessHelper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Auth::id();
        $permission = SharedAccessHelper::READWRITE;

        if($request->user_id) {
            $userId = $request->user_id;

            $access = Auth::user()->gottenAccesses()->where('users.id', $userId);
            $hasAccess = $access->exists();

            abort_if(!$hasAccess, 403);

            $permission = $access->first()->pivot->permission;
        }

        $user = User::find($userId);

        $request->merge([
            'user' => $user,
            'permission' => $permission
        ]);

        return $next($request);
    }
}
