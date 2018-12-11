<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace  EaseBaidu\Service\Bear\DataCube;

use EaseBaidu\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * User analysis data.
     *
     * @param string $date
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userSummary(string $date)
    {
        return $this->query('/rest/2.0/cambrian/datacube/getusersummary', $date);
    }

    /**
     * Home Statistical Data Query.
     *
     * @param string $date
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function homePagesSummary(string $date)
    {
        return $this->query('/rest/2.0/cambrian/datacube/gethomepagesummary', $date);
    }

    /**
     * Query.
     *
     * @param string $api
     * @param string $date
     * @param array $ext
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function query(string $api, string $date, array $ext = [])
    {
        $params = array_merge([
            'date' => $date
        ], $ext);

        return $this->httpPostJson($api, $params);
    }
}