<?php

namespace App\Http\Middleware;
use Laravel\Passport\Passport;
use Carbon\Carbon;

use Closure;

class AdminApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header('Authorization');

        $auth = Passport::token()->where('token',$header)
                                  ->where('revoked', false)
                                  ->where('expires_at','>=', Carbon::now())
                                  ->first();
        if(!$auth)
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $request->merge([
            'login_id'=>$auth->user_id,
            'login_user' => $auth->name
        ]);

        return $next($request);
    }
}
