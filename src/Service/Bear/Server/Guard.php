<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Bear\Server;

use EaseBaidu\Kernel\Guard as ServiceGuard;

class Guard extends ServiceGuard
{
    /**
     * @return bool
     */
    protected function shouldReturnRawResponse(): bool
    {
        return ! is_null($this->app['request']->get('echostr'));
    }
}