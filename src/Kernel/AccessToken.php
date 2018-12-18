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

use EaseBaidu\Kernel\Exceptions\HttpException;
use EaseBaidu\Kernel\Exceptions\InvalidArgumentException;
use Pimple\Container;
use EaseBaidu\Kernel\Contracts\AccessTokenInterface;
use EaseBaidu\Kernel\Traits\HasHttpRequests;
use EaseBaidu\Kernel\Traits\InteractsWithCache;
use Psr\Http\Message\RequestInterface;

abstract class AccessToken implements AccessTokenInterface
{
    use HasHttpRequests, InteractsWithCache;

    /**
     * @var Container
     */
    protected $app;

    /**
     * @var string
     */
    protected $requestMethod = 'GET';

    /**
     * @var string
     */
    protected $endpointToGetToken;

    /**
     * @var string
     */
    protected $queryName;

    /**
     * @var array
     */
    protected $token;

    /**
     * @var int
     */
    protected $safeSeconds = 500;

    /**
     * @var string
     */
    protected $tokenKey = 'access_token';

    /**
     * @var string
     */
    protected $cachePrefix = 'easebaidu.kernel.access_token.';



    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param bool $refresh
     * @return array
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getToken(bool $refresh = false): array
    {
        $cacheKey = $this->getCacheKey();
        $cache = $this->getCache();

        if (! $refresh && $cache->has($cacheKey)) {
            $this->app['logger']->info('Get token from cache key:', [$cacheKey]);
            return $cache->get($cacheKey);
        }

        $token = $this->requestToken($this->getCredentials(), true);

        $this->app['logger']->info('Request get token:', $token);

        $this->setToken($token, $token['expires_in'] ?? 7200);

        return $token;
    }

    /**
     * @param string $token
     * @param int $lifetime
     * @return $this
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setToken(array $token, int $lifetime = 7200)
    {
        $this->getCache()->set($this->getCacheKey(), [
            $this->tokenKey => $token[$this->tokenKey],
            'expires_in' => $lifetime,
        ], $lifetime - $this->safeSeconds);

        return $this;
    }

    /**
     * @return AccessTokenInterface
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function refresh(): AccessTokenInterface
    {
        $this->getToken(true);

        return $this;
    }

    /**
     * @param array $credentials
     * @param bool $toArray
     * @return array|Http\Response|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestToken(array $credentials, $toArray = false)
    {
        $response = $this->sendRequest($credentials);

        $result = json_decode($response->getBody()->getContents(), true);

        $formatted = $this->castResponseToType($response, $this->app['config']->get('response_type'));

        if (empty($result[$this->tokenKey])) {
            throw new HttpException('Request access_token fail: ' . json_encode($result, JSON_UNESCAPED_UNICODE), $response, $formatted);
        }

        return $toArray ? $result : $formatted ;
    }

    /**
     * @param RequestInterface $request
     * @param array $requestOptions
     * @return RequestInterface
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface
    {
        parse_str($request->getUri()->getQuery(), $query);

        $query = http_build_query(array_merge($this->getQuery(), $query));

        return $request->withUri($request->getUri()->withQuery($query));
    }

    /**
     * Send http request.
     *
     * @param array $credentials
     * @return \GuzzleHttp\Psr7\MessageTrait
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendRequest(array $credentials)
    {
        $options = [
            ('GET' === $this->requestMethod) ? 'query' : 'json' => $credentials ,
        ];

        return $this->setHttpClient($this->app['http_client'])->request($this->getEndpoint(), $this->requestMethod, $options);
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getEndpoint(): string
    {
        if (empty($this->endpointToGetToken)) {
            throw new InvalidArgumentException('No endpoint for access token request.');
        }

        return $this->endpointToGetToken;
    }

    /**
     * @return string
     */
    protected function getCacheKey(): string
    {
        return $this->cachePrefix . md5(json_encode($this->getCredentials()));
    }

    /**
     * @return array
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function getQuery(): array
    {
        return [$this->queryName ?? $this->tokenKey => $this->getToken()[$this->tokenKey]];
    }

    /**
     * Credential for get token.
     *
     * @return array
     */
    abstract protected function getCredentials(): array ;
}