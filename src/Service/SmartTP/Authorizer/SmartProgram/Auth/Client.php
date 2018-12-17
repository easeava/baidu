<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Authorizer\SmartProgram\Auth;

use EaseBaidu\Kernel\BaseClient;

/**
 * Class Client
 * @see https://smartprogram.baidu.com/docs/develop/third/login/
 *
 * @package EaseBaidu\Service\SmartTP\Authorizer\SmartProgram\Auth
 */
class Client extends BaseClient
{
    /**
     * Get session info by code.
     *
     * @param string $code
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function session(string $code)
    {
        return $this->httpGet('/rest/2.0/oauth/getsessionkeybycode', [
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);
    }
}