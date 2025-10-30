<?php
use Bót\Dispatcher\Auth;
use Bót\Utils\Test;
$folderpath_root = dirname(__DIR__) . '/bót-v2';
require __DIR__ . '/Cache/autoload.php';
require "{$folderpath_root}/core/ui/wo/vendor/autoload.php";
require "{$folderpath_root}/core/dispatchers/auth/vendor/autoload.php";
class AuthNfseTest {
    static function agiliFirstRound(string $wo_id) {
        $task_id = 1;
        $class_name_id = 'agili';
        /*$cliente = '004';
        $cnpj = '18560087000117';
        $usuario = '48657832100';
        $senha_hash = 'nJZlZQVjZGRkZt==';*/
        $cliente = '087';
        $cnpj = '39414218000128';
        $usuario = '41732782091';
        $senha_hash = 'AQR3YwZlAl44ZwNgBGR=';
        $host = 'https://agili.paranaita.mt.gov.br';
        $unidade_gestora = 'MUNICIPIO DE PARANAITA';
        Test::clearTmp();
        CacheWoArgsTest::classNameIdLoginWorker('agili');
        CacheWoArgsTest::pair('Host', $host);
        CacheWoArgsTest::pair('Unidade Gestora', $unidade_gestora);
        CacheWoArgsTest::save();
        TasksTest::IDs($wo_id, $task_id);
        TasksTest::loginOwner($cliente);
        TasksTest::pair('Usuário', $usuario);
        TasksTest::pair('Senha Hash', $senha_hash);
        TasksTest::pair('Cnpj', $cnpj);
        TasksTest::save();
        $task = TasksTest::tasks()[$task_id][0];
        Auth::operator($wo_id,  $task);
    }
}
$wo_id = 'wo_id';
CacheWoArgsTest::woId($wo_id);
CacheWoArgsTest::packageIdLoginWorker('bot-auth/nfse');
AuthNfseTest::agiliFirstRound($wo_id);
