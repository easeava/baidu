<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP\Server;

use EaseBaidu\Service\BearTP\Server\Handlers\Authorized;
use EaseBaidu\Service\BearTP\Server\Handlers\Unauthorized;
use EaseBaidu\Service\BearTP\Server\Handlers\UpdateAuthorized;
use EaseBaidu\Service\BearTP\Server\Handlers\VerifyTicket;
use Symfony\Component\HttpFoundation\Response;

class Guard extends \EaseBaidu\Kernel\Guard
{
    const EVENT_AUTHORIZED = 'authorized';
    const EVENT_UNAUTHORIZED = 'unauthorized';
    const EVENT_UPDATE_AUTHORIZED = 'updateauthorized';
    const EVENT_TP_VERIFY_TICKET = 'tp_verify_ticket';

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

        if (isset($message['InfoType'])) {
            $this->app['logger']->info('Dispatch '.$message['InfoType'].': ', $message);
            $this->dispatch($message['InfoType'], $message);
        }

        return new Response(static::SUCCESS_EMPTY_RESPONSE);
    }

    /**
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function registerHandlers()
    {
        $this->on(self::EVENT_AUTHORIZED, Authorized::class);
        $this->on(self::EVENT_UNAUTHORIZED, Unauthorized::class);
        $this->on(self::EVENT_UPDATE_AUTHORIZED, UpdateAuthorized::class);
        $this->on(self::EVENT_TP_VERIFY_TICKET, VerifyTicket::class);
    }
}