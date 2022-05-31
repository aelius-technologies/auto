<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Illuminate\Http\Request;

class MailService{
    public function handle(Request $request, Closure $next){
        $app = App::getInstance();
        $app->register('App\Providers\MailServiceProvider');
        return $next($request);
    }
}
