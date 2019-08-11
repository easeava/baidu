<?php

/**
 * EaseBaidu - A PHP Framework For Baidu SDK
 *
 * @author   Easeava <tthd@163.com>
 */

namespace EaseBaidu\Service\Payment;

use EaseBaidu\Kernel\Container;

/**
 * Payment Application.
 *
 * @property \EaseBaidu\Service\Payment\Jssdk\Client             $jssdk
 */
class Application extends Container
{
    protected $providers = [
        Jssdk\ServiceProvider::class,
    ];
}
