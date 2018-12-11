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

use EaseBaidu\Kernel\Exceptions\RuntimeException;
use EaseBaidu\Kernel\Message\Message;
use EaseBaidu\Kernel\Message\Raw;
use EaseBaidu\Kernel\Message\Text;

class Messenger
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Messenger constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Set message to send.
     *
     * @param $message
     * @return $this
     */
    public function message($message)
    {
        if (is_string($message)) {
            $message = new Text($message);
        }

        $this->message = $message;

        return $this;
    }

    /**
     * Set target user open id.
     *
     * @param string $openid
     * @return $this
     */
    public function to(string $openid)
    {
        $this->to = $openid;

        return $this;
    }

    /**
     * Send the message.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     */
    public function send()
    {
        if (empty($this->message)) {
            throw new RuntimeException('No message to send.');
        }

        if ($this->message instanceof Raw) {
            $message = json_decode($this->message->get('content'), true);
        } else {
            $prepends = [
                'touser' => $this->to,
            ];

            $message = $this->message->transformForJsonRequest($prepends);
        }

        return $this->client->send($message);
    }

    /**
     * Return property.
     *
     * @param $property
     * @return |null
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return null;
    }
}