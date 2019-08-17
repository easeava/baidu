<?php

/**
 * EaseBaidu - A PHP Framework For Baidu SDK
 *
 * @package  Easeava/baidu
 * @author   Easeava <tthd@163.com>
 */

namespace EaseBaidu;

use Illuminate\Support\Str;

/**
 * Class Factory.
 *
 * @method static \EaseBaidu\Service\Bear\Application               bear(array $config)
 * @method static \EaseBaidu\Service\BearTP\Application             bearTP(array $config)
 * @method static \EaseBaidu\Service\Payment\Application            payment(array $config)
 * @method static \EaseBaidu\Service\SmartProgram\Application       smartProgram(array $config)
 */
class Factory
{
    /**
     * @param $name
     * @param array $config
     * @return mixed
     */
    public static function make($name, array $config)
    {
        $namespace = Str::studly($name);
        $service = sprintf('\\EaseBaidu\\Service\\%s\\Application', $namespace);

        return new $service($config);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
