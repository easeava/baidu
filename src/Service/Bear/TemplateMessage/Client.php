<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Bear\TemplateMessage;

use EaseBaidu\Kernel\BaseClient;
use EaseBaidu\Kernel\Exceptions\InvalidArgumentException;

class Client extends BaseClient
{
    protected $message = [
        'touser' => '',
        'template_id' => '',
        'url' => '',
        'data' => [],
    ];

    protected $required = [
        'touser',
        'template_id',
        'url',
        'data',
    ];

    /**
     * Get all private templates.
     * Get the list of templates that have been added.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPrivateTemplates()
    {
        return $this->httpGet('/rest/2.0/cambrian/template/get_all_private_template');
    }

    /**
     * Get all sys templates.
     *
     * @param string $kw
     * @param int $pn
     * @param int $ps
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSysTemplates(string $kw, int $pn = 1, int $ps = 10)
    {
        return $this->httpGet('/rest/2.0/cambrian/template/sys_list', compact('kw', 'pn', 'ps'));
    }

    /**
     * Add template.
     *
     * @param string $id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addTemplate(string $id)
    {
        return $this->httpGet('/rest/2.0/cambrian/template/add', [
            'system_template_id' => $id,
        ]);
    }

    /**
     * Delete private template.
     *
     * @param string $template_id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deletePrivateTemplate(string $template_id)
    {
        return $this->httpPostJson('/rest/2.0/cambrian/template/del_private_template', compact('template_id'));
    }

    /**
     * Send template message.
     *
     * @param array $message
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     */
    public function send(array $message = [])
    {
        $params = $this->formatMessage($message);

        $this->restoreMessage();

        return $this->httpPostJson('/rest/2.0/cambrian/template/send', $params);
    }

    /**
     * @param array $message
     * @return array
     * @throws InvalidArgumentException
     */
    protected function formatMessage(array $message)
    {
        $params = array_merge($this->message, $message);

        foreach ($params as $key => $value) {
            if (in_array($key, $this->required, true) && empty($value) && empty($this->message[$key])) {
                throw new InvalidArgumentException(sprintf('Attribute "%s" can not be empty!', $key));
            }

            $params[$key] = empty($value) ? $this->message[$key] : $value;
        }

        $params['data'] = $this->formatData($params['data'] ?? []);
        return $params;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function formatData(array $data)
    {
        $format = [];

        foreach ($data as $key => $item) {
            if (is_array($item)) {
                if (isset($item['value'])) {
                    $format[$key] = $item;

                    continue;
                }

                if (count($item) >= 2) {
                    $item = [
                        'value' => $item[0],
                        'color' => $item[1],
                    ];
                }
            } else {
                $item = [
                    'value' => strval($item)
                ];
            }

            $format[$key] = $item;
        }

        return $format;
    }

    /**
     * Set default message.
     *
     * @throws \ReflectionException
     */
    public function restoreMessage()
    {
        $this->message = (new \ReflectionClass(__CLASS__))->getDefaultProperties()['message'];
    }
}