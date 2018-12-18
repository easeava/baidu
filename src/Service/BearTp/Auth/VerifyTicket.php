<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP\Auth;

use EaseBaidu\Kernel\Ticket;

class VerifyTicket extends Ticket
{

    /**
     * @return string
     */
    protected function getCacheKey(): string
    {
        return 'easebaidu.bear.tp_verify_ticket.'.$this->app['config']['client_id'];
    }
}