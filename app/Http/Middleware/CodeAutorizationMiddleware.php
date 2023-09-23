<?php

namespace App\Http\Middleware;

use Closure;

class CodeAutorizationMiddleware
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
        $dataHeades =  $request->headers->all();
        if (!isset($dataHeades['code-app'])) {
            return response(['error' => 'Unauthorized'], 401);
        }
        $appCode = env('APP_CODE', '');
        $code = $dataHeades['code-app'];
        if ($appCode != $code[0]) {
            return response(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
