<?php

/*
 * This file is part of the EaseBaidu.
 *
 * (c) Easeava <tthd@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EaseBaidu\Service\Payment\Notify;

use Closure;
use EaseBaidu\Kernel\Exceptions\Exception;
use EaseBaidu\Kernel\Support;
use EaseBaidu\Kernel\Support\XML;
use EaseBaidu\Kernel\Exceptions\BaiduInvalidSignException;
use Symfony\Component\HttpFoundation\Response;

abstract class Handler
{
    const SUCCESS = 0;
    const FAIL = -1;

    /**
     * @var \EaseBaidu\Service\Payment\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $fail;

    /**
     * @var array|null
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Check sign.
     * If failed, throws an exception.
     *
     * @var bool
     */
    protected $check = true;

    /**
     * Respond with sign.
     *
     * @var bool
     */
    protected $sign = false;

    /**
     * @param \EaseBaidu\Service\Payment\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Handle incoming notify.
     *
     * @param \Closure $closure
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    abstract public function handle(Closure $closure);

    /**
     * @param array $data
     */
    public function data(string $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $message
     */
    public function fail(string $message)
    {
        $this->fail = $message;
    }

    /**
     * @param array $attributes
     * @param bool  $sign
     *
     * @return $this
     */
    public function respondWith(array $attributes, bool $sign = false)
    {
        $this->attributes = $attributes;
        $this->sign = $sign;

        return $this;
    }

    /**
     * Build xml and return the response to WeChat.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse(): Response
    {
        $base = [
            'errno' => is_null($this->fail) ? static::SUCCESS : static::FAIL,
            'msg'   => $this->fail,
            'data'  => $this->data,
        ];

        $attributes = array_merge($base, $this->attributes);

        if ($this->sign) {
            $attributes['sign'] = generate_sign($attributes, $this->app->getKey());
        }

        return new Response(json_encode($attributes));
    }

    /**
     * Return the notify message from request.
     *
     * @return array
     *
     * @throws \EaseBaidu\Kernel\Exceptions\Exception
     */
    public function getMessage(): array
    {
        if (!empty($this->message)) {
            return $this->message;
        }
        try {
            parse_str(strval($this->app['request']->getContent()), $message);
        } catch (\Throwable $e) {
            throw new Exception('Invalid request data: '.$e->getMessage(), 400);
        }

        if (!is_array($message) || empty($message)) {
            throw new Exception('Invalid request data.', 400);
        }

        if ($this->check) {
            $this->validate($message);
        }

        return $this->message = $message;
    }

    /**
     * Decrypt message.
     *
     * @param string $key
     *
     * @return string|null
     *
     * @throws \EaseBaidu\Kernel\Exceptions\Exception
     */
    public function decryptMessage(string $key)
    {
        $message = $this->getMessage();
        if (empty($message[$key])) {
            return null;
        }

        return Support\AES::decrypt(
            base64_decode($message[$key], true), md5($this->app['config']->key), '', OPENSSL_RAW_DATA, 'AES-256-ECB'
        );
    }

    /**
     * Validate the request params.
     *
     * @param array $message
     *
     * @throws \EaseBaidu\Kernel\Exceptions\BaiduInvalidSignException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     */
    protected function validate(array $message)
    {
        if (!validate_sign_with_rsa($message, $this->app['config']['publicKey'])) {
            throw new BaiduInvalidSignException();
        }
        return true;
    }

    /**
     * @param mixed $result
     */
    protected function strict($result)
    {
        if (true !== $result && is_null($this->fail)) {
            $this->fail(strval($result));
        }
    }
}
