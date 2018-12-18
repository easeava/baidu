<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP\Base;

use EaseBaidu\Kernel\BaseClient;

/**
 * Class Client
 * @package EaseBaidu\Service\BearTP\Base
 */
class Client extends BaseClient
{
    /**
     * @see https://xiongzhang.baidu.com/open/wiki/chapter5/section5.3.5.html 获取access_token
     *
     * @param string $authorization_code
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handleAuthorize(string $authorization_code)
    {
        return $this->httpGet('/rest/2.0/cambrian/tp/api_query_auth', compact('authorization_code'));
    }

    /**
     * Create pre-authorization code.
     * @see https://xiongzhang.baidu.com/open/wiki/chapter5/section5.3.1.html 获取预授权码
     *
     * @param int $debug
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPreAuthorizationCode(int $debug = 0)
    {
        return $this->httpGet('/rest/2.0/cambrian/tp/api_create_preauthcode', compact('debug'));
    }
}