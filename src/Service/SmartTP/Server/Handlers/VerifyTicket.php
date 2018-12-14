<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Server\Handlers;

use EaseBaidu\Kernel\Contracts\EventHandlerInterface;
use EaseBaidu\Service\SmartTP\Application;

class VerifyTicket implements EventHandlerInterface
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param mixed $payload
     */
    public function handle($payload = null)
    {
        if (! empty($payload['Ticket'])) {
            $this->app['verify_ticket']->setTicket($payload['Ticket']);
        }
    }
}