<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Kernel;

use EaseBaidu\Kernel\Exceptions\RuntimeException;
use EaseBaidu\Kernel\Traits\InteractsWithCache;

abstract class Ticket
{
    use InteractsWithCache;

    /**
     * @var Container
     */
    protected $app;

    /**
     * Ticket constructor.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
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

        throw new RuntimeException('Credential "ticket" does not exist in cache.');
    }

    /**
     * @return string
     */
    abstract protected function getCacheKey(): string ;
}