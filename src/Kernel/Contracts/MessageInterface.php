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

interface MessageInterface
{
    /**
     * @return string
     */
    public function getType(): string ;

    /**
     * @return array
     */
    public function transformForJsonRequest(): array ;

    /**
     * @return string
     */
    public function transformToXml(): string ;
}