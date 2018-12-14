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

use EaseBaidu\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * Get session by code.
     *
     * @param string $code
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function session(string $code)
    {
        /**
         * 参见 https://smartprogram.baidu.com/docs/develop/api/open_log/#login/
         * Session Key
         */
        $params = [
            'code' => $code,
            'client_id' => $this->app['config']['app_key'],
            'sk' => $this->app['secret']
        ];

        return $this->httpPostJson('/nalogin/getSessionKeyByCode', $params);
    }
}