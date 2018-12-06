<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Kernel\Traits;

use EaseBaidu\Kernel\Clause\Clause;
use EaseBaidu\Kernel\Container;
use EaseBaidu\Kernel\Contracts\EventHandlerInterface;
use EaseBaidu\Kernel\Decorators\FinallyResult;
use EaseBaidu\Kernel\Decorators\TerminateResult;
use EaseBaidu\Kernel\Exceptions\InvalidArgumentException;
use function foo\func;

trait Observable
{
    protected $handlers = [];

    protected $clauses = [];

    /**
     * @param $handler
     * @param string $condition
     * @return Clause
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function push($handler, $condition = '*')
    {
        list($handler, $condition) = $this->resolveHandlerAndCondition($handler, $condition);

        if (! isset($this->handlers[$condition])) {
            $this->handlers[$condition] = [];
        }

        array_push($this->handlers[$condition], $handler);

        return $this->newClause($handler);
    }

    /**
     * @param $handler
     * @param string $condition
     * @return Clause
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function unshift($handler, $condition = '*')
    {
        list($handler, $condition) = $this->resolveHandlerAndCondition($handler, $condition);

        if (!isset($this->handlers[$condition])) {
            $this->handlers[$condition] = [];
        }

        array_unshift($this->handlers[$condition], $handler);

        return $this->newClause($handler);
    }

    /**
     * @param $condition
     * @param $handler
     * @return Clause
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function observer($condition, $handler)
    {
        return $this->push($handler, $condition);
    }

    /**
     * @param $condition
     * @param $handler
     * @return Clause
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function on($condition, $handler)
    {
        return $this->push($handler, $condition);
    }

    /**
     * @param $event
     * @param $payload
     * @return mixed|null
     */
    public function dispatch($event, $payload)
    {
        return $this->notify($event, $payload);
    }

    /**
     * @param $event
     * @param $payload
     * @return mixed|null
     */
    public function notify($event, $payload)
    {
        $result = null;

        foreach ($this->handlers as $condition => $handlers) {
            if ('*' === $condition || ($condition & $event) === $event) {
                foreach ($handlers as $handler) {
                    /**
                     * @var \EaseBaidu\Kernel\Clause\Clause $clause
                     */
                    if ($clause = $this->clauses[spl_object_hash((object) $handler)] ?? null) {
                        if ($clause->intercepted($payload)) {
                            continue 2;
                        }
                    }

                    $response = $this->callHandler($handler, $payload);

                    switch (true) {
                        case $response instanceof TerminateResult:
                            return $response->content;
                        case true === $response:
                            continue 2;
                        case false === $response:
                            break 2;
                        case ! empty($response) && ! ($result instanceof FinallyResult):
                            $result = $response;
                    }
                }
            }
        }

        return $result instanceof FinallyResult ? $result->content : $result ;
    }

    /**
     * @return array
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * @param $handler
     * @return Clause
     */
    protected function newClause($handler)
    {
        return $this->clauses[spl_object_hash((object) $handler)] = new Clause();
    }

    /**
     * @param callable $handler
     * @param $payload
     * @return mixed
     */
    protected function callHandler(callable $handler, $payload)
    {
        try {
            return $handler($payload);
        } catch (\Exception $e) {
            if (property_exists($this, 'app') && $this->app instanceof Container) {
                $this->app['logger']->error($e->getCode().': '.$e->getMessage(), [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        }
    }

    /**
     * @param $handler
     * @return \Closure
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function makeClosure($handler)
    {
        if (is_callable($handler)) {
            return $handler;
        }

        if (is_string($handler)) {
            if (! class_exists($handler)) {
                throw new InvalidArgumentException(sprintf('Class "%s" not exists.', $handler));
            }

            if (! in_array(EventHandlerInterface::class, (new \ReflectionClass($handler))->getInterfaceNames(), true)) {
                throw new InvalidArgumentException(sprintf('Class "%s" not an instance of "%s".', $handler, EventHandlerInterface::class));
            }

            return function ($payload) use ($handler) {
                return (new $handler($this->app ?? null))->handle($payload);
            };
        }

        if ($handler instanceof EventHandlerInterface) {
            return function () use ($handler) {
                return $handler->handle(...func_get_args());
            };
        }

        throw new InvalidArgumentException('No valid handler is found in arguments.');
    }

    /**
     * @param $handler
     * @param $condition
     * @return array
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function resolveHandlerAndCondition($handler, $condition)
    {
        if (is_int($handler) || (is_string($handler)) && ! class_exists($handler)) {
            list($handler, $condition) = [$condition, $handler];
        }

        return [$this->makeClosure($handler), $condition];
    }
}