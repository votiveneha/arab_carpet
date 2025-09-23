<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Check session or fallback to default locale
        $locale = Session::get('locale', config('app.locale'));

        // Set locale
        App::setLocale($locale);

        return $next($request);
    }
}
?>