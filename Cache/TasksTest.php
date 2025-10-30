<?php
use Bót\CoreUi\Wo\Cache\Input;
class TasksTest {
    private static string $wo_id;
    private static string $task_id;
    private static array $values = [];
    static function IDs(string $wo_id, string $task_id) {
        self::$wo_id = $wo_id;
        self::$task_id = $task_id;
        self::$values['Task Id'] = $task_id;
    }
    /**
     * Either on Args or Tasks tab.
     */
    static function classNameIdLoginWorker(string $value) {
        self::$values['Class Name Id Login Worker'] = $value;
    }
    static function loginOwner(string $value) {
        self::$values['Login Owner'] = $value;
    }
    static function pair(string $key, string $value) {
        self::$values[$key] = $value;
    }
    static function save() {
        Input::tasks(self::$wo_id, self::tasks());
    }
    static function tasks() : array {
        return [self::$task_id => [self::$values]];
    }
}
