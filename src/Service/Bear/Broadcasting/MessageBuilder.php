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

use EaseBaidu\Kernel\Contracts\MessageInterface;
use EaseBaidu\Kernel\Exceptions\RuntimeException;

class MessageBuilder
{
    /**
     * @var array
     */
    protected $to = [];

    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param MessageInterface $message
     * @return $this
     */
    public function message(MessageInterface $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set target user or group.
     *
     * @param array $to
     * @return $this
     */
    public function to(array $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return $this
     */
    public function toAll()
    {
        $this->to([
            'filter' => [
                'is_to_all' => true,
            ]
        ]);

        return $this;
    }

    /**
     * @param array $openids
     * @return $this
     */
    public function toUsers(array $openids)
    {
        $this->to([
            'touser' => $openids
        ]);

        return $this;
    }

    /**
     * @param array $attribues
     * @return $this
     */
    public function with(array $attribues)
    {
        $this->attributes = $attribues;

        return $this;
    }

    /**
     * Build message.
     *
     * @param array $prepends
     * @return array
     * @throws RuntimeException
     */
    public function build(array $prepends = [])
    {
        if (empty($this->message)) {
            throw new RuntimeException('No message content to send.');
        }

        $content = $this->message->transformForJsonRequest();

        if (empty($prepends)) {
            $prepends = $this->to;
        }

        $message = array_merge($prepends, $content, $this->attributes);

        return $message;
    }


}