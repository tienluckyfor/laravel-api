<?php
if (request()->segment(1) != 'sites' && !request()->server('THEME')) {
    return;
}
function getViewToken()
{
    $theme = request()->server('THEME');
    $token = request()->server('TOKEN');
    if (request()->segment(1) == 'sites') {
        $theme = request()->segment(2);
        $_SERVER['_THEME'] = $theme;
        try {
            require_once __DIR__ . '/ReadEnv.php';
            $path = resource_path() . '/views/sites/' . $theme . '/.env';
            $env = ReadEnv::load($path);
            $token = $env['TOKEN'];
            $_SERVER['_TOKEN'] = $token;
        } catch (Exception $e) {
        }
    }
    $view = "/sites/$theme";
    $base_url = request()->server('THEME') ? "" : $view;
    return [$view, $token, $base_url];
}

function getViewToken1()
{
    $theme = request()->server('THEME');
    $_theme = @$_SERVER['_THEME'];
    $view = $theme ? "/sites/$theme" : "/sites/$_theme";
    $base_url = $theme ? "" : $view;
    $token = request()->server('TOKEN');
    $_token = @$_SERVER['_TOKEN'];
    return [$view, $token ?? $_token, $base_url];
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
//dd($config);
require_once __DIR__ . '/Restful.php';
$http = new Restful($config->api_url, $config->token);
view()->share('config', $config);
view()->share('http', $http);
if (!isset($con)) {
    try {
        $res = $http->get('/configs/1', ['_system' => true])->json();
        $con = $res['data'];
        view()->share('con', $con);
        $sys = (object)$res['_system'];
        view()->share('sys', $sys);
    } catch (Exception $e) {
        view()->share('con', null);
        view()->share('sys', null);
    }
}

require_once __DIR__ . '/Media.php';
$media = new Media();
view()->share('media', $media);

try {
    setSeo($con['title'], $con['description'], $media->set($con['image'])->first());
} catch (Exception $e) {
    setSeo(config('codeby.seo_title'), config('codeby.seo_description'), config('codeby.seo_image'));
}
