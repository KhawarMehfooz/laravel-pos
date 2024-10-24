<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSuperAdmin{
    public function handle(Request $request, Closure $next){
        if($request->user() && $request->user()->role === 'superadmin'){
            return $next($request);
        }

        return response()->json([
            'status'=>false,
            'message'=>'Unauthorized. Requires superadmin role'
        ],403);
    }
}