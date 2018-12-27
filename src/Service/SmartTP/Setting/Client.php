<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Setting;

use EaseBaidu\Kernel\BaseClient;

/**
 * Class Client.
 * @see https://smartprogram.baidu.com/docs/develop/third/info/
 *
 * @package EaseBaidu\Service\SmartTP\Authorizer\SmartProgram
 */
class Client extends BaseClient
{
    /**
     * Get all category.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllCategories(int $category_type = 2)
    {
        return $this->httpGet('/rest/2.0/smartapp/app/category/list', compact('category_type'));
    }

    /**
     * Update category.
     *
     * @param array $category
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateCategory(array $category)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/app/category/update', $category);
    }

    /**
     * Update icon.
     *
     * @param string $image_url
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateIcon(string $image_url)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/app/modifyheadimage', compact('image_url'));
    }

    /**
     * Update signature.
     *
     * @param string $signature
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateSignature(string $signature)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/app/modifysignature', compact('signature'));
    }

    /**
     * Pause.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pause()
    {
        return $this->httpPostJson('/rest/2.0/smartapp/app/pause');
    }

    /**
     * Resume.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function resume()
    {
        return $this->httpPostJson('/rest/2.0/smartapp/app/resume');
    }

    /**
     * Get qrcode.
     *
     * @param string|null $path
     * @param string|null $package_id
     * @param int $width
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function qrcode(string $path = null, string $package_id = null, int $width = 200)
    {
        return $this->httpGet('/rest/2.0/smartapp/app/qrcode', compact('path', 'package_id', 'width'));
    }

    /**
     * Set or Update nickname.
     *
     * @param string $nick_name
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setNickname(string $nick_name)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/app/setnickname', compact('nick_name'));
    }
}