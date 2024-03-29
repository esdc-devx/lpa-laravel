<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class Language
{
    public function __construct(Application $app, Redirector $redirector, Request $request)
    {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Make sure current locale exists.
        $locale = $request->segment(1);

        if (! in_array($locale, $this->app->config->get('app.supported_locales'))) {
            $segments = $request->segments();
            $segments[0] = $this->app->config->get('app.fallback_locale');
            return $this->redirector->to(implode('/', $segments));
        }

        $this->app->setLocale($locale);

        return $next($request);
    }
}
