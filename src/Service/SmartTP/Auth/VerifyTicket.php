<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Auth;

use EaseBaidu\Kernel\Exceptions\RuntimeException;
use EaseBaidu\Kernel\Traits\InteractsWithCache;
use EaseBaidu\Service\SmartTP\Application;

class VerifyTicket
{
    use InteractsWithCache;

    /**
     * @var Application
     */
    protected $app;

    /**
     * VerifyTicket constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $ticket
     * @return $this
     * @throws RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setTicket(string $ticket)
    {
        $ok = $this->getCache()->set($this->getCacheKey(), $ticket, 3600);

        if (! $ok) {
            throw new RuntimeException('Failed to cache verify ticket.');
        }

        $this->app['logger']->debug('Set tp ticket:', ['ticket' => $ticket]);

        return $this;
    }

    /**
     * @return mixed|null
     * @throws RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTicket()
    {
        if ($cached = $this->getCache()->get($this->getCacheKey())) {
            return $cached;
        }

        throw new RuntimeException('Credential "tp_verify_ticket" does not exist in cache.');
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return 'easebaidu.smart_tp.verify_ticket.'.$this->app['config']['client_id'];
    }
}