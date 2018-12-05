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

use EaseBaidu\Kernel\Contracts\AccessTokenInterface;
use EaseBaidu\Kernel\Http\Response;
use EaseBaidu\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class BaseClient
{
    use HasHttpRequests {
        request as performRequest;
    }

    /**
     * @var Container
     */
    protected $app;

    /**
     * @var AccessTokenInterface
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $baseUri;

    /**
     * BaseClient constructor.
     *
     * @param Container $app
     * @param AccessTokenInterface|null $accessToken
     */
    public function __construct(Container $app, AccessTokenInterface $accessToken = null)
    {
        $this->app = $app;
        $this->accessToken = $accessToken;
    }

    /**
     * @param string $url
     * @param array $query
     * @return array|Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|ResponseInterface
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', $query);
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $query
     * @return array|Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|ResponseInterface
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpPostJson(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', [
            'query' => $query,
            'json' => $data
        ]);
    }

    /**
     * @param string $url
     * @param array $files
     * @param array $form
     * @param array $query
     * @return array|Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|ResponseInterface
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpUpload(string $url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', [
            'query' => $query,
            'multipart' => $multipart,
            'connect_timeout' => 30,
            'timeout' => 30,
            'read_timeout' => 30
        ]);
    }

    /**
     * @return AccessTokenInterface
     */
    public function getAccessToken(): AccessTokenInterface
    {
        return $this->accessToken;
    }

    /**
     * @param AccessTokenInterface $accessToken
     * @return $this
     */
    public function setAccessToken(AccessTokenInterface $accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @param bool $returnRaw
     * @return array|Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|ResponseInterface
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @return Response
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestRaw(string $url, string $method = 'GET', array $options = [])
    {
        return Response::buildFromPsrResponse($this->request($url, $method, $options, true));
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        if (! ($this->httpClient instanceof ClientInterface)) {
            $this->httpClient = $this->app['http_client'] ?? new Client();
        }

        return $this->httpClient;
    }

    /**
     *
     */
    public function registerHttpMiddlewares()
    {
        // retry
        $this->pushMiddleware($this->retryMiddleware(), 'retry');
        // access token
        $this->pushMiddleware($this->accessTokenMiddleware(), 'access_token');
        // log
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * @return callable
     */
    protected function retryMiddleware()
    {
        return Middleware::retry(function (
            $retries,
            RequestInterface $request,
            ResponseInterface $response = null
        ) {
            // Limit the number of retries to 2
            if ($retries < $this->app->config->get('http.max_retries', 1) && $response && $body = $response->getBody()) {
                // Retry on server errors
                $response = json_decode($body, true);

                if (!empty($response['errcode']) && in_array(abs($response['errcode']), [40001, 40014, 42001], true)) {
                    $this->accessToken->refresh();
                    $this->app['logger']->debug('Retrying with refreshed access token.');

                    return true;
                }
            }

            return false;
        }, function () {
            return abs($this->app->config->get('http.retry_delay', 500));
        });
    }

    /**
     * @return \Closure
     */
    protected function accessTokenMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if ($this->accessToken) {
                    $request = $this->accessToken->appluToRequest($request, $options);
                }

                return $handler($request, $options);
            };
        };
    }

    /**
     * @return callable
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);

        return Middleware::log($this->app['logger'], $formatter);
    }
}