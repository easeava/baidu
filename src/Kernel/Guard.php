<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Kernel;

use EaseBaidu\Kernel\Contracts\MessageInterface;
use EaseBaidu\Kernel\Exceptions\BadRequestException;
use EaseBaidu\Kernel\Exceptions\InvalidArgumentException;
use EaseBaidu\Kernel\Message\Message;
use EaseBaidu\Kernel\Message\News;
use EaseBaidu\Kernel\Message\NewsItem;
use EaseBaidu\Kernel\Message\Raw;
use EaseBaidu\Kernel\Message\Text;
use EaseBaidu\Kernel\Support\XML;
use EaseBaidu\Kernel\Traits\Observable;
use EaseBaidu\Kernel\Traits\ResponseCastable;
use Symfony\Component\HttpFoundation\Response;

class Guard
{
    use Observable, ResponseCastable;

    /**
     * @var Container
     */
    protected $app;

    /**
     * @var bool
     */
    protected $alwaysValidate = false;

    /**
     * Empty string.
     *
     * @var string
     */
    const SUCCESS_EMPTY_RESPONSE = 'success';

    /**
     * @var array
     */
    const MESSAGE_TYPE_MAPPING = [
        'text' => Message::TEXT,
        'image' => Message::IMAGE,
        'event' => Message::EVENT,
    ];

    /**
     * Guard constructor.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Handle and return response.
     *
     * @return Response
     * @throws BadRequestException
     * @throws InvalidArgumentException
     */
    public function serve()
    {
        $this->app['logger']->debug('Request received:', [
            'method' => $this->app['request']->getMethod(),
            'uri' => $this->app['request']->getUri(),
            'content-type' => $this->app['request']->getContentType(),
            'content' => $this->app['request']->getContent(),
        ]);

        $response = $this->validate()->resolve();

        $this->app['logger']->debug('Server response created:', [
            'content' => $response->getContent()
        ]);

        return $response;
    }

    /**
     * Handle and return response.
     *
     * @return $this
     * @throws BadRequestException
     */
    public function validate()
    {
        if (! $this->alwaysValidate && ! $this->isSafeMode()) {
            return $this;
        }

        if ($this->app['request']->get('signature') !== $this->signature([
                $this->getToken(),
                $this->app['request']->get('timestamp'),
                $this->app['request']->get('nonce'),
            ])) {
            throw new BadRequestException('Invalid request signature.', 400);
        }

        return $this;
    }

    /**
     * Force validate request.
     *
     * @return $this
     */
    public function forceValidate()
    {
        $this->alwaysValidate = true;

        return $this;
    }

    /**
     * Resolve server request and return the response.
     *
     * @return Response
     * @throws BadRequestException
     * @throws InvalidArgumentException
     */
    protected function resolve(): Response
    {
        $result = $this->handleRequest();

        if ($this->shouldReturnRawResponse()) {
            return new Response($result['response']);
        }

        return new Response(
            $this->buildResponse($result['to'], $result['from'], $result['response']),
            200,
            ['Content-Type' => 'application/xml']
        );
    }

    /**
     * @param string $to
     * @param string $from
     * @param $message
     * @return mixed|string
     * @throws InvalidArgumentException
     */
    public function buildResponse(string $to, string $from, $message)
    {
        if (empty($message) || self::SUCCESS_EMPTY_RESPONSE === $message) {
            return self::SUCCESS_EMPTY_RESPONSE;
        }

        if ($message instanceof Raw) {
            return $message->get('content', self::SUCCESS_EMPTY_RESPONSE);
        }

        if (is_string($message) || is_numeric($message)) {
            $message = new Text((string) $message);
        }

        if (is_array($message) && reset($message) instanceof NewsItem) {
            $message = new News($message);
        }

        if (! ($message instanceof Message)) {
            throw new InvalidArgumentException(sprintf('Invalid Messages type "%s".', gettype($message)));
        }

        return $this->buildReply($to, $from, $message);
    }

    /**
     * Build reply XML.
     *
     * @param string $to
     * @param string $from
     * @param MessageInterface $message
     * @return string
     */
    protected function buildReply(string $to, string $from, MessageInterface $message)
    {
        $prepends = [
            'ToUserName' => $to,
            'FromUserName' => $from,
            'CreateTime' => time(),
            'MsgType' => $message->getType(),
        ];

        $this->app['logger']->debug('Reply Data:', $prepends);

        $response = $message->transformToXml($prepends);

        if ($this->isSafeMode()) {
            $this->app['logger']->debug('Messages safe mode is enabled.');
            $response = $this->app['encryptor']->encrypt($response);
        }

        return $response;
    }

    /**
     * @return array|Http\Response|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws BadRequestException
     * @throws Exceptions\InvalidArgumentException
     */
    public function getMessage()
    {
        $message = $this->parseMessage($this->app['request']->getContent(false));

        if (! is_array($message) || empty($message)) {
            throw new BadRequestException('No message received.');
        }

        if ($this->isSafeMode() && ! empty($message['Encrypt'])) {
            $message = $this->decryptMessage($message);

            $dataSet = json_decode($message, true);

            if ($dataSet && (JSON_ERROR_NONE === json_last_error())) {
                return $dataSet;
            }

            $message = XML::parse($message);
        }

        return $this->detectAndCastResponseToType($message, $this->app->config->get('response_type'));
    }

    /**
     * Parse message array from raw php input.
     *
     * @param $content
     * @return array
     * @throws BadRequestException
     */
    protected function parseMessage($content)
    {
        try {
            if (0  === stripos($content, '<')) {
                $content = XML::parse($content);
            } else {
                $dataSet = json_decode($content, true);
                if ($dataSet && (JSON_ERROR_NONE === json_last_error())) {
                    $content = $dataSet;
                }
            }

            return (array) $content;
        } catch (\Exception $e) {
            throw new BadRequestException(sprintf('Invalid message content:(%s) %s', $e->getCode(), $e->getMessage()), $e->getMessage());
        }
    }

    /**
     * Handle request.
     *
     * @return array
     * @throws BadRequestException
     * @throws InvalidArgumentException
     */
    protected function handleRequest()
    {
        $castedMessage = $this->getMessage();

        $messageArray = $this->detectAndCastResponseToType($castedMessage, 'array');

        $response = $this->dispatch(self::MESSAGE_TYPE_MAPPING[$messageArray['MsgType'] ?? $messageArray['msg_type'] ?? 'text'], $castedMessage);

        return [
            'to' => $messageArray['FromUserName'] ?? '',
            'from' => $messageArray['ToUserName'] ?? '',
            'response' => $response,
        ];
    }

    /**
     * @param array $params
     * @return string
     */
    protected function signature(array $params)
    {
        sort($params, SORT_STRING);

        return sha1(implode($params));
    }

    /**
     * @return mixed
     */
    protected function getToken()
    {
        return $this->app['config']['token'];
    }

    /**
     * @return bool
     */
    protected function isSafeMode()
    {
        return $this->app['request']->get('signature') && 'aes' === $this->app['request']->get('encrypt_type');
    }

    /**
     * @return bool
     */
    protected function shouldReturnRawResponse(): bool
    {
        return false;
    }

    /**
     * @param array $message
     * @return mixed
     */
    protected function decryptMessage(array $message)
    {
        return $message = $this->app['encryptor']->decrypt(
            $message['Encrypt'],
            $this->app['request']->get('msg_signature'),
            $this->app['request']->get('nonce'),
            $this->app['request']->get('timestamp')
        );
    }
}