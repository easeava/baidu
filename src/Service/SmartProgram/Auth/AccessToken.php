<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartProgram\Auth;

use EaseBaidu\Kernel\AccessToken as BaseAccessToken;

class AccessToken extends BaseAccessToken
{

    /**
     * Credential for get token.
     *
     * @return array
     */
    protected function getCredentials(): array
    {
        /**
         * 参见 http://smartprogram.baidu.com/docs/develop/server/power_exp/
         */
        return [
            'grant_type' => 'client_credentials',
            'client_id' => $this->app['config']['app_key'],
            'client_secret' => $this->app['config']['secret'],
            'scope' => 'smartapp_snsapi_base',
        ];
    }
}