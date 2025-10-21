<?php
use Bót\Cdp\Rfb\eCac\Certificado;
use Bót\Dispatcher\Browser;
use Bót\Dispatcher\Method;
use Bót\Utils\Gui\Server;
use Bót\Utils\Package;
use Bót\Utils\Test;
$folderpath_root = dirname(__DIR__) . '/bót-v2';
$wo_id = 'wo_id';
$clear_tmp = function() {
    Server::stop(Package::idByClass(Browser::class));
    Test::clearTmp();
};
$launch = fn(string $class_worker) => Test::notPublic(Browser::class, 'launch', [$wo_id, $class_worker]);
$get_cookies = function() use ($wo_id) {
    $cookies = Method::getCookies($wo_id);
    $failed = !(isset($cookies[0]['name']) && isset($cookies[0]['value']));
    if ($failed)
        throw new \Exception('get_cookies failed');
};
$close = fn() => Browser::close($wo_id);
$has_window_open = fn() : bool => Test::notPublic(Browser::class, 'hasWindowOpen', [$wo_id]);
$is_launched = fn(string $class_worker) : bool => Test::notPublic(Browser::class, 'isLaunched', [$wo_id, $class_worker]);
$browser = function(string $class_worker) use ($wo_id, $launch, $is_launched) {
    $browser_not_launched = !$is_launched($class_worker);
    $launch_browser = function() use ($class_worker, $launch, $wo_id) {
        Browser::close($wo_id);
        $launch($class_worker);
    };
    if ($browser_not_launched)
        $launch_browser();
};
require_once "{$folderpath_root}/core/dispatchers/cdp/vendor/autoload.php";
require_once "{$folderpath_root}/CDPs/rfb/vendor/autoload.php";
//Test::execute(get_defined_vars());
$close();
//$browser(Certificado::class);
//$launch(Certificado::class);
