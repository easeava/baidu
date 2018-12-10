<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Base\Media;

use EaseBaidu\Kernel\BaseClient;
use EaseBaidu\Kernel\Exceptions\InvalidArgumentException;
use EaseBaidu\Kernel\Http\StreameResponse;

class Client extends BaseClient
{
    /**
     * @var string
     */
    protected $baseUri = 'https://openapi.baidu.com/rest/2.0/cambrian';
    /**
     * Allow media type.
     *
     * @var array
     */
    protected $allowTypes = ['image', 'voice', 'video', 'thumb'];

    /**
     * Upload temporary material.
     *
     * @param string $path
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload(string $path)
    {
        if (! file_exists($path) || ! is_readable($path)) {
            throw new InvalidArgumentException(sprintf("File does not exist, or the file is unreadable: '%s'", $path));
        }

        return $this->httpUpload('/media/upload', ['media' => $path]);
    }

    /**
     * Fetch media from Server.
     *
     * @param string $mediaid
     * @return array|\EaseBaidu\Kernel\Http\Response|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $mediaid)
    {
        $response = $this->requestRaw('/media/get', 'GET', [
            'query' => compact('mediaid'),
        ]);

        if (false !== stripos($response->getHeaderLine('Content-disposition'), 'attachment')) {
            return StreameResponse::buildFromPsrResponse($response);
        }

        return $this->castResponseToType($response, $this->app['config']->get('response_type'));
    }
}