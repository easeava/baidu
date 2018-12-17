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
use GuzzleHttp\Exception\BadResponseException;

class Client extends BaseClient
{
    /**
     * Get authorization info.
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