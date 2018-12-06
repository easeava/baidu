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

class Text extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = 'text';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = ['content'];

    /**
     * Text constructor.
     *
     * @param string $content
     */
    public function __construct(string $content)
    {
        parent::__construct(compact('content'));
    }

    /**
     * @return array
     */
    public function toXmlArray()
    {
        return [
            'Content' => $this->get('content'),
        ];
    }
}