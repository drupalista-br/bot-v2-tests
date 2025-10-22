<?php
use Bót\CoreUi\Wo\Cache\FilepathInput;
use Bót\CoreUi\Wo\Cache\Input;
use Bót\Cdp\Rfb\eCac\Certificado;
use Bót\Dispatcher\Browser;
use Bót\Dispatcher\Cdp;
use Bót\Utils\Gui\Server;
use Bót\Utils\CurlHeader;
use Bót\Utils\Package;
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
$operator = function(string $task_id, string $class_worker, string $owner) use ($wo_id) {
    $task = [
        'Task Id' => $task_id,
        'Class Name Id Login Worker' => $class_worker::NAME,
        'Login Owner' => $owner,
    ];
    $filepath_wo_args = FilepathInput::woArgs($wo_id);
    $tests = function() use ($wo_id, $task_id) {
        $no_jar = !file_exists(CurlHeader::filepathJar($wo_id, owner: $task_id));
        $agent_not_cached = CurlHeader::agentNotCached($wo_id);
        if ($no_jar)
            throw new \Exception("No jar for wo {$wo_id}, task id {$task_id}");
        if ($agent_not_cached)
            throw new \Exception("No agent for {$wo_id}, task id {$task_id}");
    };
    if (!file_exists($filepath_wo_args)) {
        // Cached at Bót\CoreUi\Wo\Cmds\Input\*
        Input::woArgs($wo_id, [
            'Package Id Login Worker' => 'bot-cdp/rfb',
        ]);
    }
    Cdp::operator($wo_id, $task);
    $tests();
};
$operator_first_round = function() use ($clear_tmp, $operator) {
    $clear_tmp();
    $operator(1, Certificado::class, '53652495187');
};
$operator_second_round = fn() => $operator(2, Certificado::class, '53652495187');
require_once "{$folderpath_root}/core/ui/wo/vendor/autoload.php";
require_once "{$folderpath_root}/core/dispatchers/cdp/vendor/autoload.php";
require_once "{$folderpath_root}/CDPs/rfb/vendor/autoload.php";
Test::execute(get_defined_vars());
