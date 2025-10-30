<?php
use Bót\CoreUi\Wo\Cache\Input;
use Bót\Cdp\Rfb\eCac\Certificado\Procuracao;
use Bót\Cdp\Rfb\eCac\Certificado;
use Bót\Cdp\Rfb\Pgdas\eCac\Procuracao as PgdasProcuracao;
use Bót\Cdp\Rfb\Pgdas\Codigo;
use Bót\Dispatcher\Browser;
use Bót\Dispatcher\Cdp;
use Bót\Utils\Gui\Server;
use Bót\Utils\CurlHeader;
use Bót\Utils\Package;
use Bót\Utils\Curl;
use Bót\Utils\Test;
use Bót\Utils\Fs;
use Bót\Utils\f;
require __DIR__ . '/Cache/autoload.php';
$folderpath_root = dirname(__DIR__) . '/bót-v2';
$wo_id = 'wo_id';
$clear_tmp = function() use ($wo_id) {
    Server::stop(Package::idByClass(Browser::class));
    Test::clearTmp();
};
$task = function(string $task_id, string $class_worker, string $login_owner, array $other_values = NULL) : array {
    $task = [
        'Task Id' => $task_id,
        'Class Name Id Login Worker' => $class_worker::NAME,
        'Login Owner' => $login_owner,
    ];
    if ($other_values)
        $task = array_merge($task, $other_values);
    return $task;
};
$cache_wo = function(array $tasks) use ($wo_id) {
    Input::tasks($wo_id, $tasks);
};
$operator = function(array $task, string $login_owner) use ($wo_id) {
    $tests = function() use ($wo_id, $login_owner) {
        $no_jar = !file_exists(CurlHeader::filepathJar($wo_id, $login_owner));
        $agent_not_cached = CurlHeader::agentNotCached($wo_id);
        if ($no_jar)
            throw new \Exception("No jar for wo {$wo_id}, login owner {$login_owner}");
        if ($agent_not_cached)
            throw new \Exception("No agent for {$wo_id}");
    };
    Cdp::operator($wo_id, $task);
    $tests();
};
$certificado_second_round = function() use ($task, $cache_wo, $operator) {
    $login_owner = '53652495187';
    $task_id = 2;
    $task = $task(
        task_id: $task_id,
        class_worker: Certificado::class,
        login_owner: $login_owner,
    );
    $tasks = [
        $task_id => [$task],
    ];
    $cache_wo($tasks);
    $operator($task, $login_owner);
};
$procuracao_first_round = function() use ($clear_tmp, $task, $cache_wo, $operator) {
    $login_owner = '53652495187';
    $outorgado = '03256427000143';
    $task_id = 1;
    $task = $task(
        task_id: $task_id,
        class_worker: Procuracao::class,
        login_owner: $login_owner,
        other_values: ['Cnpj/Cpf Outorgante' => $outorgado],
    );
    $tasks = [
        $task_id => [$task],
    ];
    $clear_tmp();
    $cache_wo($tasks);
    $operator($task, login_owner: $outorgado);
};
$procuracao_two_rounds = function() {

};
$pgdas_procuracao_first_round = function() use ($clear_tmp, $task, $cache_wo, $operator) {
    $login_owner = '53652495187';
    $outorgado = '03256427000143';
    $task_id = 1;
    $task = $task(
        task_id: $task_id,
        class_worker: PgdasProcuracao::class,
        login_owner: $login_owner,
        other_values: ['Cnpj/Cpf Outorgante' => $outorgado],
    );
    $tasks = [
        $task_id => [$task],
    ];
    $clear_tmp();
    $cache_wo($tasks);
    $operator($task, login_owner: $outorgado);
};
$test = function() {

};
require_once "{$folderpath_root}/core/ui/wo/vendor/autoload.php";
require_once "{$folderpath_root}/core/dispatchers/cdp/vendor/autoload.php";
require_once "{$folderpath_root}/CDPs/rfb/vendor/autoload.php";
Test::execute(get_defined_vars());
