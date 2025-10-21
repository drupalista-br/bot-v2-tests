<?php
use Bót\CoreUi\Wo\Cache\FilepathInput;
use Bót\CoreUi\Wo\Cache\Input;
use Bót\Cdp\Rfb\eCac\Certificado;
use Bót\Dispatcher\Browser;
use Bót\Dispatcher\Cdp;
use Bót\Utils\Gui\Server;
use Bót\Utils\Package;
use Bót\Utils\CurlJar;
use Bót\Utils\Curl;
use Bót\Utils\Test;
use Bót\Utils\Fs;
use Bót\Utils\f;
$folderpath_root = dirname(__DIR__) . '/bót-v2';
$wo_id = 'wo_id';
$clear_tmp = function() {
    Server::stop(Package::idByClass(Browser::class));
    Test::clearTmp();
};
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
$operator = function(string $task_id, string $class_worker) use ($wo_id) {
    require __DIR__ . '/mocks/cdp-operator.php';
    $task = [
        'Task Id' => $task_id,
        'Class Name Id Login Worker' => $class_worker::NAME,
        'Login Owner' => '53652495187',
    ];
    $filepath_wo_args = FilepathInput::woArgs($wo_id);
    $test = function() use ($task_id, $class_worker, $wo_id) {
        $filepath_o = Fs::tmp(Package::idByClass($class_worker)) . '/test-operator.html';
        $filepath_jar = CurlJar::filepath($wo_id, owner: $task_id);
        $not_logged_in = fn() : bool => !str_contains(file_get_contents($filepath_o), 'id="perfilAcesso"');
        Curl::uri($class_worker::WS_FLOW_DONE_URI)
            ->o($filepath_o)
            ->cookieJar($filepath_jar)
            ->flags(['--compressed', '-v'])
            ->run();
        if ($not_logged_in())
            throw new \Exception("Not logged in: {$filepath_o}");
    };
    if (!file_exists($filepath_wo_args)) {
        // Cached at Bót\CoreUi\Wo\Cmds\Input\*
        Input::woArgs($wo_id, [
            'Package Id Login Worker' => 'bot-cdp/rfb',
        ]);
    }
    Cdp::operator($wo_id, $task);
    $test();
};
$operator_first_round = function() use ($clear_tmp, $operator) {
    $clear_tmp();
    $operator(1, Certificado::class);
};
$operator_second_round = fn() => $operator(2, Certificado::class);
require_once "{$folderpath_root}/core/ui/wo/vendor/autoload.php";
require_once "{$folderpath_root}/core/dispatchers/cdp/vendor/autoload.php";
require_once "{$folderpath_root}/CDPs/rfb/vendor/autoload.php";
Test::execute(get_defined_vars());
