<?php
use Bót\CoreUi\Wo\Cache\FilepathInput;
use Bót\CoreUi\Wo\Cache\Input;
use Bót\Cdp\Rfb\eCac\Certificado;
use Bót\Dispatcher\Browser;
use Bót\Dispatcher\Method;
use Bót\Dispatcher\Cdp;
use Bót\Utils\Gui\Server;
use Bót\Utils\Package;
use Bót\Utils\Test;
use Bót\Utils\f;
$folderpath_root = dirname(__DIR__) . '/bót-v2';
$wo_id = 'wo_id';
$clear_tmp = function() {
    Server::stop(Package::idByClass(Browser::class));
    Test::clearTmp();
};
$browser_launch = fn() => Test::notPublic(Browser::class, 'launch', [$wo_id, Certificado::class]);
$get_cookies = function() use ($wo_id) {
    $cookies = Method::getCookies($wo_id);
    $failed = !(isset($cookies[0]['name']) && isset($cookies[0]['value']));
    if ($failed)
        throw new \Exception('get_cookies failed');
};
$browser_done = fn() => Browser::done($wo_id);
$ecac_certificado = function() use ($wo_id) {
    $task = (function() use ($wo_id) {
        $filepath_task = Test::notPublic(Cdp::class, 'filepathTask', [$wo_id]);
        // Cached at Cdp::operator(), $cache_task();
        if (!file_exists($filepath_task)) {
            f::array2json($filepath_task, [
                'Login Owner' => '53652495187',
            ]);
        }
        return Cdp::getTask($wo_id);
    })();
    $filepath_wo_args = FilepathInput::woArgs($wo_id);
    $filepath_class_worker_name_id = Test::notPublic(Cdp::class, 'filepathClassWorkerNameId', [$wo_id]);
    if (!file_exists($filepath_wo_args)) {
        // Cached at Bót\CoreUi\Wo\Cmds\Input\*
        Input::woArgs($wo_id, [
            'Package Id Login Worker' => 'bot-cdp/rfb',
        ]);
    }
    // Cached at Cdp::operator(), $class_worker();
    if (!file_exists($filepath_class_worker_name_id))
        file_put_contents($filepath_class_worker_name_id, Certificado::NAME);
    Test::notPublic(Certificado::class, 'wsFlow', [$wo_id, $task]);
};
require_once "{$folderpath_root}/core/ui/wo/vendor/autoload.php";
require_once "{$folderpath_root}/core/dispatchers/cdp/vendor/autoload.php";
require_once "{$folderpath_root}/CDPs/rfb/vendor/autoload.php";
Test::execute(get_defined_vars());
die;
use Bót\Utils\CurlJar;

$jar = function() use ($wo_id) {

    $jar = CurlJar::fromCdp($cookies, ['fazenda.gov.br']);
    var_dump($jar);
};

