<?php
use Bót\Utils\Test;
$get_cookies = function(string $wo_id) {
    $cookies = \Bót\Dispatcher\Method::getCookies($wo_id);
    $failed = !(isset($cookies[0]['name']) && isset($cookies[0]['value']));
    if ($failed)
        throw new \Exception('get_cookies failed');
};

$_GET['folderpath-root'] = dirname(__DIR__) . '/bót-v2';
class MethodTest {

}
//Test::execute(get_defined_vars());
require_once "{$_GET['folderpath-root']}/core/ui/wo/vendor/autoload.php";
require_once "{$_GET['folderpath-root']}/core/dispatchers/cdp/vendor/autoload.php";
require_once "{$_GET['folderpath-root']}/CDPs/rfb/vendor/autoload.php";
