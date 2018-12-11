<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Bear\CustomerService;

use EaseBaidu\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * Send a message.
     *
     * @param array $message
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(array $message)
    {
        return $this->httpPostJson('/rest/2.0/cambrian/message/custom_send', $message);
    }

    /**
     * Get message builder.
     *
     * @param $message
     * @return Messenger
     */
    public function message($message)
    {
        $messageBuilder = new Messenger($this);

        return $messageBuilder->message($message);
    }
}