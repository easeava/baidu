<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Bear\HomePage;

use EaseBaidu\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * Create custorm tab.
     *
     * @param array $tabs
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(array $tabs)
    {
        return $this->httpGet('/rest/2.0/cambrian/homepage/addtab', $tabs);
    }

    /**
     * Update custorm tab.
     *
     * @param array $tabs
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(array $tabs)
    {
        return $this->httpGet('/rest/2.0/cambrian/homepage/edittab', $tabs);
    }

    /**
     * Delete custorm tab.
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete()
    {
        return $this->httpGet('/rest/2.0/cambrian/homepage/deletetab');
    }

    /**
     * Get custorm tabinfo.
     *
     * @param int $page
     * @param int $pagesize
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function tabinfo(int $page = 1, int $pagesize = 0)
    {
        return $this->httpGet('/rest/2.0/cambrian/homepage/gettabinfo', compact('page', 'pagesize'));
    }

    /**
     * Update tabinfo.
     * @param array $tabs
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateTabinfo(array $tabs)
    {
        return $this->httpPostJson('/rest/2.0/cambrian/homepage/edititem', $tabs);
    }

    /**
     * Create article.
     *
     * @param array $article
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createArticle(array $article)
    {
        return $this->httpPostJson('/rest/2.0/cambrian/homepage/additem', $article);
    }

    /**
     * Update article.
     *
     * @param int $itemID
     * @param array $article
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateArticle(int $itemID, array $article)
    {
        $params = array_merge($article, [
            'item_id' => $itemID,
        ]);

        return $this->httpPostJson('/rest/2.0/cambrian/homepage/updateitem', $params);
    }

    /**
     * Delete articles.
     *
     * @param array $itemids
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteArticles(array $itemids)
    {
        return $this->httpGet('/rest/2.0/cambrian/homepage/deleteitems', [
            'item_ids' => $itemids,
        ]);
    }

    /**
     * Upload stuff.
     *
     * @param string $path
     * @param int $type
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadStuff(string $path, int $type)
    {
        return $this->httpUpload('/rest/2.0/cambrian/home/uploadstuff', [
            'file' => $path,
        ], [], [
            'type' => $type
        ]);
    }

    /**
     * Upload Base64 stuff.
     * @param string $file
     * @param int $type
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadMaterial(string $file, int $type)
    {
        return $this->request('/rest/2.0/cambrian/homepage/uploadmaterial', 'POST', [
            'form_params' => [
                'file' => $file,
                'type' => $type,
                'connect_timeout' => 30,
                'timeout' => 30,
                'read_timeout' => 30,
            ],
        ]);
    }

    /**
     * Get operation status.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function operationStatus()
    {
        return $this->httpGet('/rest/2.0/cambrian/home/operationstatus');
    }

    /**
     * Delete operation status.
     *
     * @param int $status
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setoperationStatus(int $status)
    {
        return $this->httpGet('/rest/2.0/cambrian/home/setoperationstatus', compact('status'));
    }

    /**
     * Get openoperation.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function openoperation()
    {
        return $this->httpGet('/rest/2.0/cambrian/home/openoperation');
    }

    /**
     * Add openoperation.
     *
     * @param string $title
     * @param string $url
     * @param string $tag
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addOpenoperation(string $title, string $url, string $tag)
    {
        return $this->httpPostJson('/rest/2.0/cambrian/home/addopenoperation', compact('title', 'url', 'tag'));
    }

    /**
     * Update openoperation.
     *
     * @param int $id
     * @param array $openoperation
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateOpenoperation(int $id, array $openoperation)
    {
        $params = array_merge([
            'id' => $id,
        ], $openoperation);

        return $this->httpPostJson('/rest/2.0/cambrian/home/updateopenoperation', $params);
    }

    /**
     * Get home background.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function background()
    {
        return $this->httpGet('/rest/2.0/cambrian/home/background');
    }

    /**
     * Delete home background.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteBackground()
    {
        return $this->httpGet('/rest/2.0/cambrian/home/deletebackground');
    }

    /**
     * Publish home background.
     *
     * @param int $stuff_id
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function publishBackground(int $stuff_id)
    {
        return $this->httpGet('/rest/2.0/cambrian/home/publishbackground', compact('stuff_id'));
    }
}