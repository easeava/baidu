<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP\Authorizer\Bear\OAuth;

use EaseAva\Socialite\Contracts\BaiduComponentInterface;
use EaseBaidu\Service\BearTP\Application;

class ComponentDelegate implements BaiduComponentInterface
{

    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Return the Bear tp component client id.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->app['config']['client_id'];
    }

    /**
     * Return the Bear tp component access token string.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->app['access_token']->getToken()['tp_access_token'];
    }
}
