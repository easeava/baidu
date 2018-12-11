<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Bear\Material;

use EaseBaidu\Kernel\BaseClient;
use EaseBaidu\Kernel\Exceptions\InvalidArgumentException;
use EaseBaidu\Kernel\Http\StreameResponse;
use EaseBaidu\Kernel\Message\Article;

class Client extends BaseClient
{
    /**
     * @var array
     */
    protected $allowTypes = ['image', 'voice', 'news_image'];

    /**
     * Upload articles.
     *
     * @param $articles
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadArticle($articles)
    {
        if ($articles instanceof Article || ! empty($articles['title'])) {
            $articles = [
                $articles
            ];
        }

        $params = [
            'articles' => array_map(function ($article) {
                if ($article instanceof Article) {
                    return $article->transformForJsonRequestWithoutType();
                }

                return $article;
            }, $articles),
        ];

        return $this->httpPostJson('/rest/2.0/cambrian/material/add_news', $params);
    }

    /**
     * Update article.
     *
     * @param string $mediaID
     * @param $article
     * @param int $index
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateArticle(string $mediaID, $article, int $index = 0)
    {
        if ($article instanceof Article) {
            $article = $article->transformForJsonRequestWithoutType();
        }

        $params = [
            'media_id' => $mediaID,
            'index' => $index,
            'articles' => isset($article['title']) ? $article : (isset($article[$index]) ? $article[$index] : []),
        ];

        return $this->httpPostJson('/rest/2.0/cambrian/material/update_news', $params);
    }

    /**
     * Upload image for article.
     *
     * @param string $path
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadArticleImage(string $path)
    {
        return $this->upload('news_image', $path);
    }

    /**
     * Upload image.
     *
     * @param string $path
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadImage(string $path)
    {
        $this->upload('image', $path);
    }

    /**
     * Upload voice.
     * @param string $path
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadVoice(string $path)
    {
        return $this->upload('voice', $path);
    }

    /**
     * @param string $type
     * @param string $path
     * @param array $form
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload(string $type, string $path, array $form = [])
    {
        if (! file_exists($path) && ! is_readable($path)) {
            throw new InvalidArgumentException(sprintf('File does not exist, or the file is unreadable: "%s"', $path));
        }

        $form['type'] = $type;

        return $this->httpUpload($this->getApiByType($type), ['media' => $path], $form);
    }

    /**
     * Get stats of materials.
     *
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function stats()
    {
        return $this->httpGet('/rest/2.0/cambrian/material/get_materialcount');
    }

    /**
     * Fetch material.
     *
     * @param string $mediaID
     * @return array|\EaseBaidu\Kernel\Http\Response|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $mediaID)
    {
        $response = $this->requestRaw('/rest/2.0/cambrian/material/get_material', 'GET', [
            'media_id' => $mediaID,
        ]);

        if (false !== stripos($response->getHeaderLine('Content-disposition'), 'attachment')) {
            return StreameResponse::buildFromPsrResponse($response);
        }

        return $this->castResponseToType($response, $this->app['config']->get('response_type'));
    }

    /**
     * Delete material by media ID.
     *
     * @param string $mediaID
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $mediaID)
    {
        return $this->httpGet('/rest/2.0/cambrian/material/del_material', [
            'media_id' => $mediaID,
        ]);
    }

    /**
     * @param string $type
     * @param int $offset
     * @param int $count
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list(string $type, int $offset = 0, int $count = 20)
    {
        return $this->httpPostJson('/rest/2.0/cambrian/material/batchget_material', compact('type', 'offset', 'count'));
    }

    /**
     * @param string $type
     * @return string
     */
    public function getApiByType(string $type)
    {
        switch ($type) {
            case 'news_image':
                return '/rest/2.0/cambrian/media/uploadimg';
            default:
                return '/rest/2.0/cambrian/media/add_material';
        }
    }
}