<?php

namespace App\Http\Middleware;

use Closure;

class APIAuthenticate
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
        $apiKey=$request->header("API_KEY");
        if($apiKey != "ABC"){
            return response()->json(["message" => "Invalid API Key"],401);
        }
        return $next($request);
    }
}
