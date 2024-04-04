<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();

        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'You do not have the necessary permissions to access this resource.');
    }
}
