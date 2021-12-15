<?php
if (request()->segment(1) != 'sites') {
    return;
}
function getViewToken()
{
    $theme = request()->server('THEME');
    $token = request()->server('TOKEN');
    if (!$theme && request()->segment(1) == 'sites') {
        $theme = request()->segment(2);
        $_SERVER['_THEME'] = $theme;
        try {
            require_once __DIR__ . '/DotEnv.php';
            (new DotEnv(resource_path() . '/views/sites/' . $theme . '/.env'))->load();
            $token = getenv('TOKEN');
            $_SERVER['_TOKEN'] = $token;
        } catch (Exception $e) {
        }
    }
    $view = "/sites/$theme";
    $base_url = request()->server('THEME') ? "" : $view;
    return [$view, $token, $base_url];
}

function setSeo($title = null, $description = null, $image = null)
{
    if ($title) {
        $seo['title'] = $title;
    }
    if ($description) {
        $seo['description'] = $description;
    }
    if ($image) {
        $seo['image'] = $image;
    }
    if (isset($seo)) {
        view()->share('seo', $seo);
    }
}

[$view, $token, $base_url] = getViewToken();
$static = str_replace('sites', 'statics', $view);

$config = (object)[
    'url'      => Request::url(),
    'static'   => $static,
    'view'     => $view,
    'layout'   => $view . '/layouts',
    'token'    => $token,
    'api_url'  => config('codeby.api_url'),
    'base_url' => $base_url,
];
require_once __DIR__ . '/Restful.php';
$http = new Restful($config->api_url, $config->token);
view()->share('config', $config);
view()->share('http', $http);
if (!isset($con)) {
    try {
        \Illuminate\Support\Facades\Log::channel('single')->info('1', []);

        $res = $http->get('/configs/1', ['_system' => true])->json();
        \Illuminate\Support\Facades\Log::channel('single')->info('2', []);

        $con = $res['data'];
        view()->share('con', $con);
        $sys = (object)$res['_system'];
        view()->share('sys', $sys);
    } catch (Exception $e) {
        view()->share('con', null);
        view()->share('sys', null);
    }
}

require_once __DIR__.'/Media.php';
$media = new Media();
view()->share('media', $media);

try {
//    dd($media, $con['image']);
    setSeo($con['title'], $con['description'], $media->set($con['image'])->first());
} catch (Exception $e) {
    setSeo(config('codeby.seo_title'), config('codeby.seo_description'), config('codeby.seo_image'));
}
