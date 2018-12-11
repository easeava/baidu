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

class Article extends Message
{
    /**
     * @var string
     */
    protected $type = 'mapnews';

    /**
     * @var array
     */
    protected $properties = [
        'content_source_url',
        'title',
        'thumb_media_id',
        'author',
        'digest',
        'content',
    ];

    /**
     * @var array
     */
    protected $required = [
        'content_source_url',
        'title',
        'thumb_media_id',
    ];
}