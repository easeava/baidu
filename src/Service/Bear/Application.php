<?php

/**
 * EaseBaidu - A PHP Framework For Baidu SDK
 *
 * @package  Easeava/baidu
 * @author   Easeava <tthd@163.com>
 */

namespace EaseBaidu\Service\Bear;

use EaseBaidu\Service\Base;
use EaseBaidu\Kernel\Container;

class Application extends Container
{
    protected $providers = [
        Auth\ServiceProvider::class,
        OAuth\ServiceProvider::class,
        Server\ServiceProvider::class,
        Menu\ServiceProvider::class,
        User\ServiceProvider::class,
        DataCube\ServiceProvider::class,
        Material\ServiceProvider::class,
        HomePage\ServiceProvider::class,
        CustomerService\ServiceProvider::class,
        Base\Media\ServiceProvider::class,
        Base\Jssdk\ServiceProvider::class,
    ];
}
