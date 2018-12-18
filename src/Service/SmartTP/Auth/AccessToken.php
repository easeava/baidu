<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Auth;

use EaseBaidu\Kernel\AccessToken as BaseAccessToken;
use EaseBaidu\Kernel\Exceptions\HttpException;

/**
 * Class AccessToken
 * SmartTP AccessToken
 *
 * @package EaseBaidu\Service\SmartTP\Auth
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @var string
     */
    protected $endpointToGetToken = '/public/2.0/smartapp/auth/tp/token';

    /**
     * @param array $credentials
     * @param bool $toArray
     * @return array|\EaseBaidu\Kernel\Http\Response|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws HttpException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestToken(array $credentials, $toArray = false)
    {
        $response = $this->sendRequest($credentials);

        $result = json_decode($response->getBody()->getContents(), true);

        $formatted = $this->castResponseToType($response, $this->app['config']->get('response_type'));

        if ($result['msg'] !== 'success') {
            throw new HttpException('Request access_token fail: ' . json_encode($result, JSON_UNESCAPED_UNICODE), $response, $formatted);
        }

        return $toArray ? $result['data'] : $formatted->data ;
    }

    /**
     * Credential for get token.
     *
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'client_id' => $this->app['config']['app_key'],
            'ticket' => $this->app['verify_ticket']->getTicket(),
        ];
    }
}