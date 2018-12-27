<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Authorizer\SmartProgram\Package;

use EaseBaidu\Kernel\BaseClient;

/**
 * Class Client
 * @see https://smartprogram.baidu.com/docs/develop/third/apppage/
 *
 * @package EaseBaidu\Service\SmartTP\Authorizer\SmartProgram\Package
 */
class Client extends BaseClient
{
    /**
     * Uplaod package.
     *
     * @param int $template_id
     * @param int $ext_json
     * @param string $user_version
     * @param string $user_desc
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload(int $template_id, int $ext_json, string $user_version, string $user_desc)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/package/upload', compact('template_id', 'ext_json', 'user_version', 'user_desc'));
    }

    /**
     * Submit package audit.
     *
     * @param string $content
     * @param string $package_id
     * @param string $remark
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submitAudit(string $content, string $package_id, string $remark)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/package/submitaudit', compact('content', 'package_id', 'remark'));
    }

    /**
     * Release package.
     *
     * @param string $package_id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function release(string $package_id)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/package/release', compact('package_id'));
    }

    /**
     * @param string $package_id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function rollback(string $package_id)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/package/rollback', compact('package_id'));
    }

    /**
     * With draw.
     *
     * @param string $package_id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function withdraw(string $package_id)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/package/withdraw', compact('package_id'));
    }

    /**
     * Get trial details.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTrial()
    {
        return $this->httpGet('/rest/2.0/smartapp/package/gettrial');
    }

    /**
     * Get package list.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list()
    {
        return $this->httpGet('/rest/2.0/smartapp/package/get');
    }

    /**
     * Get package detail.
     *
     * @param int|null $type
     * @param int|null $package_id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDetail(int $type = null, int $package_id = null)
    {
        return $this->httpGet('/rest/2.0/smartapp/package/getdetail', compact('type', 'package_id'));
    }
}