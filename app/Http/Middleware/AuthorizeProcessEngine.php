<?php

namespace App\Http\Middleware;

use App\Models\Process\ProcessInstance;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class AuthorizeProcessEngine
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
        // Ensure that request contains an authToken and an engineProcessInstanceId that match a process instance.
        if (! $request->has('authToken') ||
            ! ProcessInstance::where(['engine_process_instance_id' => $request->route('engineProcessInstanceId'), 'engine_auth_token' => $request->authToken])->first()) {
                throw new AuthorizationException('Invalid engineProcessInstanceId and authToken combination.');
        }
        return $next($request);
    }
}
