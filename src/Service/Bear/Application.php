<?php

/**
 * EaseBaidu - A PHP Framework For Baidu SDK
 *
 * @package  Easeava/baidu
 * @author   Easeava <tthd@163.com>
 */

namespace EaseBaidu\Service\Bear;

use EaseBaidu\Kernel\Container;

class Application extends Container
{
    protected $providers = [
        OAuth\ServiceProvider::class,
    ];
}
