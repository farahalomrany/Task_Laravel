<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAdminToken
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = null;
        try{
            $user  = JWTAuth::parseToken()->authenticate();
        }catch(\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this -> returnError('404','Invalid_Token');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this -> returnError('404','Expired_Token');
            } else {
                return $this -> returnError('404','Token_notfound');
            }
        }catch(\Throwable $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this -> returnError('404','Invalid_Token');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this -> returnError('404','Expired_Token');
            } else {
                return $this -> returnError('404','Token_notfound');
            }
        }
        if(!$user)
        $this -> returnError(trans('Unauthenticated'));
        return $next($request);
       
    }
}
