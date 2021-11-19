<?php
use Illuminate\Support\Facades\Route;

Route::get('test', function(){
    return view('laravel-api::test');

//echo 'Test';
//    $viewDir = resource_path('views');
//    dd($viewDir);
//    echo 'Hello from the calculator package! ee';
//    require_once config('codeby.config');
});

function viewCache($token, $view_path)
{
    if (config('app.env') == 'local') {
        return view($view_path);
    }
    $key = $token . url()->full();
    $key = md5($key);
    $value = Cache::get($key, function () use ($view_path) {
        return view($view_path)->render();
    });
    Cache::put($key, $value, now()->addMinutes(10));
    return $value;
}

Route::any('sites/{theme}/{view?}/{any?}', function ($theme, $view = 'index') {
    $view_path = "sites/$theme/$view";
//    $_SERVER['_THEME'] = $theme;
//    try {
//        require_once __DIR__.'/../_class/DotEnv.php';
//        (new DotEnv(resource_path() . '/views/sites/' . $theme . '/.env'))->load();
//        $_SERVER['_TOKEN'] = getenv('TOKEN');
//    } catch (Exception $e) {
//    }
    if (!View::exists($view_path)) {
        abort(404);
    }
    return viewCache($_SERVER['_TOKEN'], $view_path);
})->where('any', '.*');

Route::get('/{view?}/{any?}', function ($view = 'index') {
    $theme = request()->server('THEME');
    $token = request()->server('TOKEN');
    if ($theme && $token) {
        $view_path = "sites/$theme/$view";
        if (!View::exists($view_path)) {
            abort(404);
        }
        return viewCache($token, $view_path);
    }
    return view('welcome');
})->where('any', '.*');
