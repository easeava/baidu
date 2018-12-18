<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP\Auth;

class AccessToken extends \EaseBaidu\Kernel\AccessToken
{
    /**
     * @var string
     */
    protected $tokenKey = 'tp_access_token';

    /**
     * @var string
     */
    protected $endpointToGetToken = '/oauth/2.0/token';

    /**
     * Credential for get token.
     *
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'grant_type' => 'tp_credentials',
            'tp_client_id' => $this->app['config']['client_id'],
            'tp_client_secret' => $this->app['config']['secret'],
            'tp_verify_ticket' => $this->app['tp_verify_ticket']->getTicket(),
        ];
    }
}