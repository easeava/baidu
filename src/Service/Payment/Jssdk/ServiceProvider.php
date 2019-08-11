<?php

/**
 * EaseBaidu - A PHP Framework For Baidu SDK
 *
 * @author   Easeava <tthd@163.com>
 */

namespace EaseBaidu\Service\Payment\Jssdk;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 *
 * @author Easeava <tthd@163.com>
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['jssdk'] = function ($app) {
            return new Client($app);
        };
    }
}
