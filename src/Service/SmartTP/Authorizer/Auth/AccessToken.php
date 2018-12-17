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

    protected $componet;

    public function __construct(Container $app, Application $compoent)
    {
        parent::__construct($app);

        $this->componet = $compoent;
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
            'grant_type' => 'app_to_tp_authorization_code',
        ];
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        $params = [
            'refresh_token' => $this->app['config']['refresh_token'],
            'grant_type' => 'app_to_tp_refresh_token',
        ];

        return '/rest/2.0/oauth/token?' . http_build_query($params);
    }
}