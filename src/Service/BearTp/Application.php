<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP;

use Closure;
use EaseBaidu\Kernel\Container;
use EaseBaidu\Service\BearTP\Authorizer\Auth\AccessToken;
use EaseBaidu\Service\BearTP\Authorizer\Bear\OAuth\ComponentDelegate;
use EaseBaidu\Service\BearTP\Authorizer\Server\Guard;

class Application extends Container
{
    protected $providers = [
        Base\ServiceProvider::class,
        Auth\ServiceProvider::class,
        Server\ServiceProvider::class,
    ];

    /**
     * @var
     */
    protected $refreshTokenCallback;

    /**
     * @param Closure $closure.
     *
     * @return $this
     */
    public function setRefreshTokenCallback(Closure $closure)
    {
        $this->refreshTokenCallback = $closure;

        return $this;
    }

    public function bear(string $client_id, string $refresh_token = null, AccessToken $accessToken = null)
    {
        $application = new Authorizer\Bear\Application($this->getAuthorizerConfig($client_id, $refresh_token), $this->getReplaceServices($accessToken) + [
                'encryptor' => $this['encryptor'],
        ]);

        $application->extend('oauth', function ($socialite) {
            /* @var \EaseAva\Socialite\Providers\BaiduProvider $socialite */
            return $socialite->component(new ComponentDelegate($this));
        });

        return $application;
    }

    /**
     * @param string $callbackUrl
     * @return string
     */
    public function getPreAuthorizationUrl(string $callbackUrl)
    {
        $params = [
            'tp_client_id' => $this['config']['client_id'],
            'pre_auth_code' => $this->getPreCode(),
            'redirect_uri' => $callbackUrl,
        ];

        return 'https://openapi.baidu.com/oauth/2.0/tp/login_page?' . http_build_query($params);
    }

    /**
     * @param string $client_id
     * @param string|null $refresh_token
     * @return mixed
     */
    protected function getAuthorizerConfig(string $client_id, string $refresh_token = null)
    {
        return $this['config']->merge(compact('client_id', 'refresh_token'))->toArray();
    }

    /**
     * @param AccessToken|null $accessToken
     * @return array
     */
    protected function getReplaceServices(AccessToken $accessToken = null)
    {
        $services = [
            'access_token' => $accessToken ?: function ($app) {
                $access_token = new AccessToken($app, $this);

                if ($this->refreshTokenCallback) {
                    $access_token->setRefreshTokenCallback($this->refreshTokenCallback);
                }

                return $access_token;
            },

            'server' => function ($app) {
                return new Guard($app);
            },
        ];

        foreach (['cache', 'http_client', 'log', 'logger', 'request'] as $reuse) {
            if (isset($this[$reuse])) {
                $services[$reuse] = $this[$reuse];
            }
        }

        return $services;
    }

    /**
     * @return mixed
     */
    public function getPreCode()
    {
        $debug = $this->config['debug'] ?? 0;

        return $this->getPreAuthorizationCode($debug)['pre_auth_code'] ?? '';
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->base->$name(...$arguments);
    }
}