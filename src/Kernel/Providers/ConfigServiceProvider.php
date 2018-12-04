<?php

/**
 * EaseBaidu - A PHP Framework For Baidu SDK
 *
 * @package  Easeava/baidu
 * @author   Easeava <tthd@163.com>
 */

namespace EaseBaidu\Kernel\Providers;

use EaseBaidu\Kernel\Config;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        /**
         * @param \EaseBaidu\Kernel\Container $app
         * @return Config
         */
        $pimple['config'] = function ($app) {
            return new Config($app->getConfig());
        };
    }
}