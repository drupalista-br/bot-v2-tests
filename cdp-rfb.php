<?php
use Bót\Dispatcher\Browser;
use Bót\Dispatcher\Cdp;
use Bót\Utils\Gui\Server;
use Bót\Utils\Package;
use Bót\Utils\Test;
$folderpath_root = dirname(__DIR__) . '/bót-v2';
require __DIR__ . '/Cache/autoload.php';
require "{$folderpath_root}/core/ui/wo/vendor/autoload.php";
require "{$folderpath_root}/core/dispatchers/cdp/vendor/autoload.php";
class CdpRfbTest {
    static function certificadoFirstRound(string $wo_id) {
        $task_id = 1;
        $class_name_id = 'ecac-certificado';
        $login_owner = '53652495187';
        self::clearTmpFirstRound();
        CacheWoArgsTest::save();
        TasksTest::IDs($wo_id, $task_id);
        TasksTest::classNameIdLoginWorker($class_name_id);
        TasksTest::loginOwner($login_owner);
        TasksTest::save();
        $task = TasksTest::tasks()[$task_id][0];
        Cdp::operator($wo_id,  $task);
        // $test();
    }
    static function pgdasCodigoFirstRound(string $wo_id) {
        $task_id = 1;
        $class_name_id = 'pgdas-codigo';
        $login_owner = '25063364000141';
        $cpf_responsavel = '01234908123';
        $codigo = '962170240284';
        self::clearTmpFirstRound();
        CacheWoArgsTest::save();
        TasksTest::IDs($wo_id, $task_id);
        TasksTest::classNameIdLoginWorker($class_name_id);
        TasksTest::loginOwner($login_owner);
        TasksTest::pair('Cpf Responsável', $cpf_responsavel);
        TasksTest::pair('Código', $codigo);
        TasksTest::save();
        $task = TasksTest::tasks()[$task_id][0];
        Cdp::operator($wo_id,  $task);
    }
    private static function clearTmpFirstRound() {
        Server::stop(Package::idByClass(Browser::class));
        Test::clearTmp();
    }
}
$wo_id = 'wo_id';
CacheWoArgsTest::woId($wo_id);
CacheWoArgsTest::packageIdLoginWorker('bot-cdp/rfb');
CdpRfbTest::pgdasCodigoFirstRound($wo_id);
