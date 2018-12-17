<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP;

use EaseBaidu\Kernel\Container;
use EaseBaidu\Service\SmartProgram\Encryptor;
use EaseBaidu\Service\SmartTP\Authorizer\Auth\AccessToken;
use EaseBaidu\Service\SmartTP\Authorizer\SmartProgram\Auth\Client;
use EaseBaidu\Service\SmartTP\Server\Guard;

class Application extends Container
{
    protected $providers = [
        Base\ServiceProvider::class,
        Auth\ServiceProvider::class,
        Server\ServiceProvider::class,
    ];

    /**
     * Create smart program application.
     *
     * @param string $client_id
     * @param string|null $refresh_token
     * @param AccessToken|null $accessToken
     * @return Authorizer\SmartProgram\Application
     */
    public function smart(string $client_id, string $refresh_token = null, AccessToken $accessToken = null)
    {
        return new Authorizer\SmartProgram\Application($this->getAuthorizerConfig($client_id, $refresh_token), $this->getReplaceServices($accessToken) + [
            'encryptor' => function () {
                return new Encryptor(
                    $this['config']['app_id'],
                    $this['config']['token'],
                    $this['config']['aes_key']
                );
            },

            'auth' => function ($app) {
                return new Client($app);
            },
        ]);
    }

    /**
     * @param string $callbackUrl
     * @return string
     */
    public function getPreAuthorizationUrl(string $callbackUrl)
    {
        $params = [
            'client_id' => $this['config']['app_key'],
            'pre_auth_code' => $this->getPreAuthorizationCode()['pre_auth_code'],
            'redirect_uri' => $callbackUrl,
        ];

        return 'https://smartprogram.baidu.com/mappconsole/tp/authorization?' . http_build_query($params);
    }

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
                return new AccessToken($app, $this);
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
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->base->$name(...$arguments);
    }
}