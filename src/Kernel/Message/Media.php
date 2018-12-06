<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Kernel\Message;

use EaseBaidu\Kernel\Contracts\MediaInterface;
use Illuminate\Support\Str;

class Media extends Message implements MediaInterface
{

    public function __construct($mediaID, $type = null, array $attributes = [])
    {
        parent::__construct(array_merge(['media_id' => $mediaID], $attributes));

        ! empty($type) && $this->setType($type);
    }

    /**
     * @return string
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     */
    public function getMediaID(): string
    {
        $this->checkRequiredAttributes();

        return $this->get('media_id');
    }

    /**
     * @return array|void
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     */
    public function toXmlArray()
    {
        return [
            Str::studly($this->getType()) => [
                'MediaId' => $this->getMediaID(),
            ],
        ];
    }
}