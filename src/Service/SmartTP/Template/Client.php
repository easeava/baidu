<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Template;

use EaseBaidu\Kernel\BaseClient;

/**
 * Class Client
 * @see https://smartprogram.baidu.com/docs/develop/third/module/
 *
 * @package EaseBaidu\Service\SmartTP\Template
 */
class Client extends BaseClient
{
    /**
     * Template list.
     *
     * @param int $page
     * @param int $page_size
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list(int $page = 1, int $page_size = 10)
    {
        return $this->httpGet('/rest/2.0/smartapp/template/gettemplatelist', compact('page', 'page_size'));
    }

    /**
     * Delete template.
     *
     * @param int $template_id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(int $template_id)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/template/deltemplate', compact('template_id'));
    }

    /**
     * Draft list.
     *
     * @param int $page
     * @param int $page_size
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDrafts(int $page = 1, int $page_size = 10)
    {
        return $this->httpGet('/rest/2.0/smartapp/template/gettemplatedraftlist');
    }

    /**
     * @param int $draft_id
     * @param string $user_desc
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addTemplate(int $draft_id, string $user_desc)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/template/addtotemplate', compact('draft_id', 'user_desc'));
    }
}