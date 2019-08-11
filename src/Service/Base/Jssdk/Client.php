<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Base\Jssdk;

use EaseBaidu\Kernel\Exceptions\RuntimeException;
use EaseBaidu\Kernel\BaseClient;
use EaseBaidu\Kernel\Support\Str;
use EaseBaidu\Kernel\Traits\InteractsWithCache;
use EaseBaidu\Kernel\Support;

class Client extends BaseClient
{
    use InteractsWithCache;

    /**
     * Current url.
     *
     * @var string
     */
    protected $url;

    /**
     * Build script.
     *
     * @return string
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function buildScript()
    {
        $config = $this->configSignature();

        return '<script src="https://xiongzhang.baidu.com/sdk/c.js?appid='.$config['appid'].'&timestamp='.$config['timestamp'].'&nonce_str='.$config['nonce_str'].'&signature='.$config['signature'].'&url='.$config['url'].'"></script>';
    }

    /**
     * Build signature.
     *
     * @param string|null $url
     * @param string|null $nonce
     * @param null $timestamp
     * @return array
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function configSignature(string $url = null, string $nonce = null, $timestamp = null)
    {
        $url = $url ?: $this->getUrl();
        $nonce = $nonce ?: Str::quickRandom(10);
        $timestamp = $timestamp ?: time();
        $ticket = $this->getTicket(true)['ticket'];

        return [
            'appid' => $this->getAppId(),
            'nonce_str' => $nonce,
            'timestamp' => $timestamp,
            'url' => $url,
            'signature' => $this->getTicketSignature($ticket, $nonce, $timestamp, $url),
        ];

    }

    /**
     * Get js ticket.
     *
     * @param bool $refresh
     * @return array|\EaseBaidu\Kernel\Http\Response|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface|null
     * @throws RuntimeException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTicket(bool $refresh = false)
    {
        $cacheKey = sprintf('easeava.basic.jssdk.ticket.%s', $this->getAppId());

        if (! $refresh && $this->getCache()->has($cacheKey)) {
            return $this->getCache()->get($cacheKey);
        }

        $result = $this->castResponseToType($this->requestRaw('/rest/2.0/cambrian/jssdk/getticket'), 'array');

        $ok = $this->getCache()->set($cacheKey, $result, $result['expires_in'] - 500);

        if (! $ok) {
            throw new RuntimeException('Failed to cache jssdk ticket.');
        }

        return $result;
    }

    /**
     * Sign the params.
     *
     * @param $ticket
     * @param $nonce
     * @param $timestamp
     * @param $url
     * @return string
     */
    public function getTicketSignature($ticket, $nonce, $timestamp, $url)
    {
        return sha1(sprintf('jsapi_ticket=%s&nonce_str=%s&timestamp=%s&url=%s', $ticket, $nonce, $timestamp, $url));
    }

    /**
     * Set url.
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        if ($this->url) {
            return $this->url;
        }

        return Support\current_url();
    }

    /**
     * @return string
     */
    protected function getAppId()
    {
        return $this->app['config']->get('app_id');
    }
}
