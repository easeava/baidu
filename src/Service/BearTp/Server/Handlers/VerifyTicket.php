<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP\Server\Handlers;

use EaseBaidu\Kernel\Contracts\EventHandlerInterface;
use EaseBaidu\Service\BearTP\Application;

class VerifyTicket implements EventHandlerInterface
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * VerifyTicket constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param mixed $payload
     */
    public function handle($payload = null)
    {
        if (! empty($payload['TpVerifyTicket'])) {
            $this->app['tp_verify_ticket']->setTicket($payload['TpVerifyTicket']);
        }
    }
}