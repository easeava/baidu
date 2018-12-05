<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Kernel\Contracts;

use Psr\Http\Message\RequestInterface;

interface AccessTokenInterface
{
    /**
     * @return array
     */
    public function getToken(): array ;

    /**
     * @return AccessTokenInterface
     */
    public function refresh(): self ;

    /**
     * @param RequestInterface $request
     * @param array $requestOptions
     * @return RequestInterface
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface ;
}