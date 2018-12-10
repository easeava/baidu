<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Bear\User;

use EaseBaidu\Kernel\BaseClient;

class TagClient extends BaseClient
{
    /**
     * Create tag.
     *
     * @param string $name
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(string $name)
    {
        $params = [
            'tag' => [
                'name' => $name,
            ],
        ];
        return $this->httpPostJson('/rest/2.0/cambrian/tags/create', []);
    }

    /**
     * List all tag.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list()
    {
        return $this->httpGet('/rest/2.0/cambrian/tags/get');
    }

    /**
     * Update a tag name.
     *
     * @param int $tagID
     * @param string $name
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(int $tagID, string $name)
    {
        $params = [
            'tag' => [
                'id' => $tagID,
                'name' => $name,
            ]
        ];

        return $this->httpPostJson('/rest/2.0/cambrian/tags/update', $params);
    }

    /**
     * Delete tag.
     *
     * @param int $tagID
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(int $tagID)
    {
        $params = [
            'tag' => [
                'id' => $tagID,
            ],
        ];

        return $this->httpPostJson('/rest/2.0/cambrian/tags/delete', $params);
    }

    /**
     * Get tags with user.
     *
     * @param int $openid
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userTags(int $openid)
    {
        $params = [
            'openid' => $openid,
        ];

        return $this->httpPostJson('/rest/2.0/cambrian/tags/getidlist', $params);
    }

    /**
     * Get users from tag.
     * @param int $tagID
     * @param int $pn
     * @param int $ps
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function usersOfTag(int $tagID, int $pn = 1, int $ps = 10)
    {
        return $this->httpPostJson('/rest/2.0/cambrian/tag/get', [
            'tagid' => $tagID,
            'pn' => $pn,
            'ps' => $ps,
        ]);
    }

    /**
     * Batch tag users.
     *
     * @param array $openids
     * @param int $tagID
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function tagUsers(array $openids, int $tagID)
    {
        $params = [
            'openid_list' => $openids,
            'tagid' => $tagID,
        ];

        return $this->httpPostJson('/rest/2.0/cambrian/tags/batchtagging', $params);
    }

    /**
     * Untag users from a tag.
     *
     * @param array $openids
     * @param int $tagID
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function untagUsers(array $openids, int $tagID)
    {
        $params = [
            'openid_list' => $openids,
            'tagid' => $tagID,
        ];

        return $this->httpPostJson('/rest/2.0/cambrian/tags/batchuntagging', $params);
    }
}