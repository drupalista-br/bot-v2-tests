<?php
use Bót\CoreUi\Wo\Cache\Input;
class CacheWoArgsTest {
    private static string $wo_id;
    private static array $values = [];
    static function woId(string $wo_id) {
        self::$wo_id = $wo_id;
    }
    static function pair(string $key, string $value) {
        self::$values[$key] = $value;
    }
    static function packageIdLoginWorker(string $value) {
        self::$values['Package Id Login Worker'] = $value;
    }
    /**
     * Either on Args or Tasks tab.
     */
    static function classNameIdLoginWorker(string $value) {
        self::$values['Class Name Id Login Worker'] = $value;
    }
    static function save() {
        Input::woArgs(self::$wo_id, self::$values);
    }
}
