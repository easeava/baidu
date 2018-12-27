<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Authorizer\SmartProgram\Media;

use EaseBaidu\Kernel\BaseClient;

/**
 * Class Client
 * @see https://smartprogram.baidu.com/docs/develop/third/upload/
 *
 * @package EaseBaidu\Service\SmartTP\Authorizer\SmartProgram\Media
 */
class Client extends BaseClient
{
    /**
     * Upload image.
     *
     * @param array $files
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadImage(array $files)
    {
        return $this->httpUpload('/file/2.0/smartapp/upload/image', $files);
    }
}