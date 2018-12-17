<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Authorizer\Auth;

use EaseBaidu\Kernel\AccessToken as BaseAccessToken;
use EaseBaidu\Service\SmartTP\Application;
use Pimple\Container;

class AccessToken extends BaseAccessToken
{
    /**
     * @var string
     */
    protected $queryName = 'access_token';

    protected $endpointToGetToken = '/rest/2.0/oauth/token';

    /**
     * @var Application
     */
    protected $component;

    public function __construct(Container $app, Application $component)
    {
        parent::__construct($app);
        $this->component = $component;
    }

    /**
     * Credential for get token.
     *
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'refresh_token' => $this->app['config']['refresh_token'],
            'grant_type' => 'app_to_tp_refresh_token',
            'access_token' => $this->component['access_token']->getToken()[$this->tokenKey],
        ];
    }
}