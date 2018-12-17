<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP;

use EaseBaidu\Kernel\Container;

class Application extends Container
{
    protected $providers = [
        Base\ServiceProvider::class,
        Auth\ServiceProvider::class,
        Server\ServiceProvider::class,
    ];

    public function smart(string $client_id, string $refresh_token)
    {

    }

    /**
     * @param string $callbackUrl
     * @return string
     */
    public function getPreAuthorizationUrl(string $callbackUrl)
    {
        $params = [
            'client_id' => $this['config']['app_key'],
            'pre_auth_code' => $this->getPreAuthorizationCode()['pre_auth_code'],
            'redirect_uri' => $callbackUrl,
        ];

        return 'https://smartprogram.baidu.com/mappconsole/tp/authorization?' . http_build_query($params);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->base->$name(...$arguments);
    }
}