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

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class Config
 * @package EaseBaidu\Kernel
 */
class Config extends Collection
{
    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    public function has($key)
    {
        return ! is_null(Arr::get($this->items, $key));
    }

    public function offsetExists($key)
    {
        return $this->has($key);
    }

    public function offsetGet($key)
    {
        return $this->offsetExists($key) ? $this->get($key) : null;
    }
}