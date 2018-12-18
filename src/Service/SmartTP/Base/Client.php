<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Base;

use EaseBaidu\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * Get authorization info.
     * @see https://smartprogram.baidu.com/docs/develop/third/pro/ 使用授权码换小程序的接口调用凭据和授权信息
     *
     * @param string|null $code
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handleAuthorize(string $code = null)
    {
        $params = [
            'code' => $code ?? $this->app['request']->get('authorization_code'),
            'grant_type' => 'app_to_tp_authorization_code',
        ];

        return $this->httpGet('/rest/2.0/oauth/token', $params);
    }

    /**
     * Get smart program base info.
     * @see https://smartprogram.baidu.com/docs/develop/third/pro/ 获取小程序基础信息
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAuthorizer(string $access_token)
    {
        return $this->httpGet('/rest/2.0/smartapp/app/info', compact('access_token'));
    }

    /**
     * Get pre auth code.
     * @see https://smartprogram.baidu.com/docs/develop/third/pro/ 获取预授权码pre_auth_code
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPreAuthorizationCode()
    {
        $result = $this->httpGet('/rest/2.0/smartapp/tp/createpreauthcode');

        if (! $result['errno']) {
            return $result['data'];
        }

        return $result;
    }
}