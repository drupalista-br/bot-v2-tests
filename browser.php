<?php
use Bót\Utils\Test;
$_GET['folderpath-root'] = dirname(__DIR__) . '/bót-v2';
class BrowserTest {
    static function clearTmp(string $wo_id) {
        require_once "{$_GET['folderpath-root']}/core/dispatchers/cdp/vendor/autoload.php";
        \Bót\Utils\Gui\Server::stop(\Bót\Utils\Package::idByClass(\Bót\Dispatcher\Browser::class));
        \Bót\Dispatcher\Browser::close($wo_id);
        Test::clearTmp();
    }
    static function notLaunched(string $wo_id, string $class_worker) : bool {
        require_once "{$_GET['folderpath-root']}/core/dispatchers/cdp/vendor/autoload.php";
        return Test::notPublic(\Bót\Dispatcher\Browser::class, 'notLaunched', [$wo_id, $class_worker]);
    }
    static function close(string $wo_id) {
        require_once "{$_GET['folderpath-root']}/core/dispatchers/cdp/vendor/autoload.php";
        \Bót\Dispatcher\Browser::close($wo_id);
    }
    static function launch(string $wo_id, string $class_worker) {
        require_once "{$_GET['folderpath-root']}/core/ui/wo/vendor/autoload.php";
        require_once "{$_GET['folderpath-root']}/core/dispatchers/cdp/vendor/autoload.php";
        require_once "{$_GET['folderpath-root']}/CDPs/rfb/vendor/autoload.php";
        Test::notPublic(\Bót\Dispatcher\Browser::class, 'launch', [$wo_id, $class_worker]);
    }
}
//Test::execute(get_defined_vars());

//BrowserTest::launch('wo_id_1', \Bót\Cdp\Rfb\eCac\Certificado::class);
//BrowserTest::clearTmp('wo_id_1');
