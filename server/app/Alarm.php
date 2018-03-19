<?php
namespace App;

use Symfony\Component\Debug\Exception\ClassNotFoundException;

class Alarm
{
    public static $driver;

    public static function init($type)
    {
        $class = false !== strpos($type, '\\') ? $type : '\\App\\Alarm\\Driver\\' . ucwords($type);
        if (class_exists($class)) {
            self::$driver = new $class();
        } else {
            throw new ClassNotFoundException('class not exists:' . $class, $class);
        }
    }

    public static function send($data, $type = 'QiyeWechat')
    {
        // 是否开启报警
        if (true !== config('wechat.alarm_switch')) {
            return true;
        }
        self::init($type);

        return static::$driver->send($data);
    }

}
