<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP\Server;

use EaseBaidu\Kernel\Encryptor;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     */
    public function register(Container $app)
    {
        $app['encryptor'] = function ($app) {
            return new Encryptor(
                $app['config']['client_id'],
                $app['config']['token'],
                $app['config']['aes_key']
            );
        };

        $app['server'] = function ($app) {
            return new Guard($app);
        };
    }
}