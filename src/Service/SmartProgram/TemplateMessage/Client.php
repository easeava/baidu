<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartProgram\TemplateMessage;

use EaseBaidu\Service\Bear\TemplateMessage\Client as BaseClient;

class Client extends BaseClient
{
    /**
     * Define send api url.
     *
     * @var string
     */
    const API_SEND = '/rest/2.0/smartapp/template/send';

    /**
     * @var array
     */
    protected $message = [
        'template_id' => '',
        'data' => [],
        'scene_id' => '',
        'scene_type' => '',
    ];

    /**
     * @var array
     */
    protected $required = [
        'template_id',
        'data',
        'scene_id',
        'scene_type',
    ];

    /**
     * Get template library list.
     *
     * @param int $offset
     * @param int $count
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list(int $offset, int $count)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/template/librarylist', compact('offset', 'count'));
    }

    /**
     * Get template library by id.
     *
     * @param string $id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $id)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/template/libraryget', compact('id'));
    }

    /**
     * Add template.
     *
     * @param string $id
     * @param array $keyword
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function add (string $id, array $keyword)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/template/templateadd', [
            'id' => $id,
            'keyword_id_list' => $keyword,
        ]);
    }

    /**
     * Delete template.
     *
     * @param string $templete_id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $templete_id)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/template/templatedel', compact('templete_id'));
    }

    /**
     * Get template list.
     *
     * @param int $offset
     * @param int $count
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTemplates(int $offset, int $count)
    {
        return $this->httpPostJson('/rest/2.0/smartapp/template/templatelist', compact('offset', 'count'));
    }
}
