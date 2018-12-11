<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Bear\Broadcasting;

use EaseBaidu\Kernel\BaseClient;
use EaseBaidu\Kernel\Contracts\MessageInterface;
use EaseBaidu\Kernel\Exceptions\RuntimeException;
use EaseBaidu\Kernel\Message\Image;
use EaseBaidu\Kernel\Message\Media;
use EaseBaidu\Kernel\Message\Text;
use EaseBaidu\Kernel\Support\Arr;

class Client extends BaseClient
{
    /**
     * Send a message.
     *
     * @param array $message
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(array $message)
    {
        if (empty($message['filter']) && empty($message['touser'])) {
            throw new RuntimeException('The message reception object is not specified'));
        }

        return $this->httpPostJson('/rest/2.0/cambrian/message/sendall', $message);
    }

    /**
     * @param MessageInterface $message
     * @param null $reception
     * @param array $attributes
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessage(MessageInterface $message, $reception = null, array $attributes = [])
    {
        $message = (new MessageBuilder())->message($message)->with($attributes)->toAll();

        if (is_array($reception)) {
            $message->toUsers($reception);
        }

        return $this->send();
    }

    /**
     * Send a text message.
     *
     * @param string $message
     * @param null $reception
     * @param array $attributes
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendText(string $message, $reception = null, array $attributes = [])
    {
        return $this->sendMessage(new Text($message), $reception, $attributes);
    }

    /**
     * Send a news message.
     *
     * @param string $mediaID
     * @param null $reception
     * @param array $attributes
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendNews(string $mediaID, $reception = null, array $attributes = [])
    {
        return $this->sendMessage(new Media($mediaID, 'mpnews'), $reception, $attributes);
    }

    /**
     * Send a voice message.
     *
     * @param string $mediaID
     * @param null $reception
     * @param array $attributes
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendVoice(string $mediaID, $reception = null, array $attributes = [])
    {
        return $this->sendMessage(new Media($mediaID, 'voice'), $reception, $attributes);
    }

    /**
     * Send a image message.
     *
     * @param string $mediaID
     * @param null $reception
     * @param array $attributes
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendImage(string $mediaID, $reception = null, array $attributes = [])
    {
        return $this->sendMessage(new Image($mediaID), $reception, $attributes);
    }

    /**
     * Send a video message.
     *
     * @param string $mediaID
     * @param null $reception
     * @param array $attributes
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendVideo(string $mediaID, $reception = null, array $attributes = [])
    {
        return $this->sendMessage(new Media($mediaID, 'video'), $reception, $attributes);
    }
}