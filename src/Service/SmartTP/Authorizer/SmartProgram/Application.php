<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Authorizer\SmartProgram;

use EaseBaidu\Service\SmartProgram\Application as SmartProgram;

class Application extends SmartProgram
{
    public function __construct(array $config = [], array $prepends = [])
    {
        parent::__construct($config, $prepends);

        $providers = [
            Setting\ServiceProvider::class,
            Domain\ServiceProvider::class,
            Media\ServiceProvider::class,
            Package\ServiceProvider::class,
        ];

        foreach ($providers as $provider) {
            $this->register(new $provider());
        }
    }
}