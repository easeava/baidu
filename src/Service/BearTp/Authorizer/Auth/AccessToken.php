<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP\Authorizer\Auth;

use Closure;
use EaseBaidu\Service\BearTP\Application;
use Pimple\Container;

class AccessToken extends \EaseBaidu\Kernel\AccessToken
{
    /**
     * @var string
     */
    protected $queryName = 'access_token';

    /**
     * @var string
     */
    protected $endpointToGetToken = '/rest/2.0/cambrian/tp/api_authorizer_token';

    /**
     * @var string
     */
    protected $cachePrefix = 'easebaidu.bear_tp.app.access_token.';

    /**
     * @var
     */
    protected $refreshTokenCallback;

    /**
     * @var Application
     */
    protected $component;

    public function __construct(Container $app, Application $component)
    {
        parent::__construct($app);

        $this->component = $component;
    }

    public function setRefreshTokenCallback(Closure $closure)
    {
        $this->refreshTokenCallback = $closure;

        return $this;
    }

    /**
     * Credential for get token.
     *
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'tp_access_token' => $this->component['access_token']->getToken()['tp_access_token'],
            'refresh_token' => $this->app['config']['refresh_token'],
        ];
    }

    /**
     * @param array $token
     * @param int $lifetime
     * @return $this|\EaseBaidu\Kernel\AccessToken
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setToken(array $token, int $lifetime = 7200)
    {
        parent::setToken($token);

        if ($this->refreshTokenCallback) {
            call_user_func($this->refreshTokenCallback, $token, $this->app);
        }

        $this->app['config']['refresh_token'] = $token['refresh_token'];

        return $this;
    }
}