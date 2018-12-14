<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP;

use EaseBaidu\Kernel\Container;

class Application extends Container
{
    protected $providers = [
        Auth\ServiceProvider::class,
        Server\ServiceProvider::class,
    ];

    public function smart(string $client_id, string $refresh_token)
    {

    }
}