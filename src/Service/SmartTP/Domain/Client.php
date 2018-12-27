<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Domain;

use EaseBaidu\Kernel\BaseClient;

/**
 * Class Client
 * @see https://smartprogram.baidu.com/docs/develop/third/address/
 *
 * @package EaseBaidu\Service\SmartTP\Authorizer\SmartProgram\Domain
 */
class Client extends BaseClient
{
    /**
     * @param array $params
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function modify(array $params)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/app/modifydomain', $params);
    }

    /**
     * Set domains.
     *
     * @param array $web_view_domain
     * @param string $action
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setWebviewDomain(array $web_view_domain, $action = 'add')
    {
        return $this->httpPostJson('/rest/2.0/smartapp/app/modifywebviewdomain', compact('web_view_domain', 'action'));
    }
}