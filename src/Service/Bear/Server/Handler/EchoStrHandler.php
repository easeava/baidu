<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Bear\Server\Handler;

use EaseBaidu\Kernel\Container;
use EaseBaidu\Kernel\Contracts\EventHandlerInterface;
use EaseBaidu\Kernel\Decorators\FinallyResult;

class EchoStrHandler implements EventHandlerInterface
{

    /**
     * @var Container
     */
    protected $app;

    /**
     * EchoStrHandler constructor.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param null $payload
     * @return FinallyResult
     */
    public function handle($payload = null)
    {
        if ($str = $this->app['request']->get('echostr')) {
            return new FinallyResult($str);
        }
    }
}