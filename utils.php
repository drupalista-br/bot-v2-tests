<?php
use Bót\Dispatcher\Auth;
use Bót\Utils\Test;
$_GET['folderpath-root'] = dirname(__DIR__) . '/bót-v2';
require "{$_GET['folderpath-root']}/core/utils/vendor/autoload.php";
class UtilsTest {
    static function classes() {
        $class_name_id = 'agili';
        $package_id = 'bot-auth/nfse';
        require "{$_GET['folderpath-root']}/auths/nfse/vendor/autoload.php";
        $test = \Bót\Utils\Classes::getClass($class_name_id, $package_id);
        var_dump($test);
    }
}
UtilsTest::classes();
