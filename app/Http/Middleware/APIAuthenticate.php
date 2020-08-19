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

//    public function handle($request, Closure $next){
//        header("Access-Control-Allow-Origin: *");
//
//        // ALLOW OPTIONS METHOD
//        $headers = [
//
//            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
//            'Access-Control-Allow-Headers'=> 'Accept, Content-Type, X-Auth-Token, Origin'
//        ];
//        if($request->getMethod() == "OPTIONS") {
//            // The client-side application can set only headers allowed in Access-Control-Allow-Headers
//            return Response::make('OK', 200, $headers);
//        }
//
//        $response = $next($request);
//        foreach($headers as $key => $value)
//            //$response->header($key, $value);
//            $response->headers->set($key, $value);
//        return $response;
//    }
}
