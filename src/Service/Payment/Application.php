<?php

/**
 * EaseBaidu - A PHP Framework For Baidu SDK
 *
 * @package  Easeava/baidu
 * @author   Easeava <tthd@163.com>
 */

namespace EaseBaidu\Service\Payment;

use Closure;
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

    /**
     * @param \Closure $closure
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @codeCoverageIgnore
     *
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function handlePaidNotify(Closure $closure)
    {
        return (new Notify\Paid($this))->handle($closure);
    }
}
