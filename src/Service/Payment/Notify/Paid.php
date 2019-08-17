<?php

/*
 * This file is part of the EaseBaidu.
 *
 * (c) Easeava <tthd@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EaseBaidu\Service\Payment\Notify;

use Closure;

class Paid extends Handler
{

    /**
     * @var array|null
     */
    protected $data = [
        'isConsumed' => 2,
    ];

    /**
     * @param \Closure $closure
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function handle(Closure $closure)
    {
        $this->strict(
            \call_user_func($closure, $this->getMessage(), [$this, 'data'],[$this, 'fail'])
        );

        return $this->toResponse();
    }
}
