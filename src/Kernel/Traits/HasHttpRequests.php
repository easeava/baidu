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

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;

trait HasHttpRequests
{
    use ResponseCastable;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

    /**
     * @var array
     */
    protected static $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    /**
     * Set guzzle default settings.
     *
     * @param array $defaults
     */
    public static function setDefaultOptions(array $defaults = [])
    {
        self::$defaults = $defaults;
    }

    /**
     * Return current guzzle default settings.
     *
     * @return array
     */
    public static function getDefaultOptions(): array
    {
        return self::$defaults;
    }

    /**
     * Set GuzzleHttp\Client.
     *
     * @param ClientInterface $client
     * @return HasHttpRequests
     */
    public function setHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Return GuzzleHttp\ClientInterface instance.
     *
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        if (! ($this->httpClient instanceof ClientInterface)) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }

    /**
     * Add a middleware.
     *
     * @param callable $middleware
     * @param string|null $name
     * @return HasHttpRequests
     */
    public function pushMiddleware(callable $middleware, string $name = null): self
    {
        if (! is_null($name)) {
            $this->middlewares[$name] = $middleware;
        } else {
            array_push($this->middlewares, $middleware);
        }

        return $this;
    }

    /**
     * Return all middlewares.
     *
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * Set GuzzleHttp\HandlerStack.
     *
     * @param HandlerStack $handlerStack
     * @return $this
     */
    public function setHandlerStack(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;

        return $this;
    }

    /**
     * Build a handler stack.
     *
     * @return HandlerStack
     */
    public function getHandlerStack()
    {
        if ($this->handlerStack) {
            return $this->handlerStack;
        }

        $this->handlerStack = HandlerStack::create();

        foreach ($this->middlewares as $name => $middleware) {
            $this->handlerStack->push($middleware, $name);
        }

        return $this->handlerStack;
    }


    /**
     * @param $url
     * @param string $method
     * @param array $options
     * @return \GuzzleHttp\Psr7\MessageTrait
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($url, string $method = 'GET', array $options = [])
    {
        $method = strtoupper($method);

        $options = array_merge(self::$defaults, $options, [
            'handler' => $this->getHandlerStack()
        ]);

        $options = $this->jsonOptions($options);

        if (property_exists($this, 'baseUri') && ! is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        /** @var \GuzzleHttp\Psr7\MessageTrait $response */
        $response = $this->getHttpClient()->request($method, $url, $options);
        $response->getBody()->rewind();

        return $response;
    }

    /**
     * @param array $options
     * @return array
     */
    protected function jsonOptions(array $options): array
    {
        if (isset($options['json']) && is_array($options['json'])) {
            $options['headers'] = array_merge($options['headers'] ?? [], ['Content-Type' => 'application/json']);

            if (empty($options['json'])) {
                $options['body'] = \GuzzleHttp\json_encode($options['json'], JSON_FORCE_OBJECT);
            } else {
                $options['body'] = \GuzzleHttp\json_encode($options['json'], JSON_UNESCAPED_UNICODE);
            }

            unset($options['json']);
        }

        return $options;
    }
}