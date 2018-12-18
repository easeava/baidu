<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace  EaseBaidu\Service\SmartTP\Server;

use EaseBaidu\Kernel\Exceptions\BadRequestException;
use EaseBaidu\Service\SmartTP\Server\Handlers\Authorized;
use EaseBaidu\Service\SmartTP\Server\Handlers\Unauthorized;
use EaseBaidu\Service\SmartTP\Server\Handlers\Updateauthorized;
use EaseBaidu\Service\SmartTP\Server\Handlers\VerifyTicket;
use Symfony\Component\HttpFoundation\Response;

class Guard extends \EaseBaidu\Kernel\Guard
{
    const EVENT_SMARTTP_VERIFY_TICKET = 'ticket';

    /**
     * @return Response
     * @throws \EaseBaidu\Kernel\Exceptions\BadRequestException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function resolve(): Response
    {
        $this->registerHandlers();

        $message = $this->getMessage();

        if (isset($message['MsgType'])) {
            $this->dispatch($message['MsgType'], $message);
        }

        return new Response(static::SUCCESS_EMPTY_RESPONSE);
    }

    /**
     * @return array|\EaseBaidu\Kernel\Http\Response|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws BadRequestException
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     */
    public function getMessage()
    {
        $message = $this->parseMessage($this->app['request']->getContent(false));

        if (! is_array($message) || empty($message)) {
            throw new BadRequestException('No message received.');
        }

        if (! empty($message['Encrypt'])) {
            $message = $this->decryptMessage($message);

            $dataSet = json_decode($message, true);

            $this->app['logger']->info('Decrypt tp message:', $dataSet);

            if ($dataSet && (JSON_ERROR_NONE === json_last_error())) {
                return $dataSet;
            }
        }

        return $this->detectAndCastResponseToType($message, $this->app->config->get('response_type'));
    }

    /**
     * Register event handlers.
     *
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function registerHandlers()
    {
        $this->on(self::EVENT_SMARTTP_VERIFY_TICKET, VerifyTicket::class);
    }

    protected function decryptMessage(array $message)
    {
        return $message = $this->app['encryptor']->decrypt(
            $message['Encrypt'],
            $message['MsgSignature'],
            $message['Nonce'],
            $message['TimeStamp']
        );
    }
}