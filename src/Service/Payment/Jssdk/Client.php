<?php

/**
 * EaseBaidu - A PHP Framework For Baidu SDK
 *
 * @author   Easeava <tthd@163.com>
 */

namespace EaseBaidu\Service\Payment\Jssdk;

use EaseBaidu\Kernel\Container;
use EaseBaidu\Kernel\BaseClient;
use EaseBaidu\Kernel\Contracts\AccessTokenInterface;

/**
 * Class Client.
 *
 * @author Easeava <tthd@163.com>
 */
class Client extends BaseClient
{

    /**
     * BaseClient constructor.
     *
     * @param Container $app
     * @param AccessTokenInterface|null $accessToken
     */
    public function __construct(Container $app, AccessTokenInterface $accessToken = null)
    {
        $this->app = $app;
    }

     /**
      * orderInfo for payment
      *
      * @param integer $totalAmount
      * @param string $tpOrderId
      * @param string $deadTitle
      * @param array $bizInfo
      * @return array
      */
    public function sdkConfig(int $totalAmount, string $tpOrderId, string $deadTitle, array $bizInfo= null): array
    {
        $params = [
            'appKey' => $this->app['config']['appKey'],
            'deadId' => $this->app['config']['deadId'],
            'tpOrderId' => $tpOrderId,
            'totalAmount' => $totalAmount
        ];
        $sign = generate_sign_with_rsa($params,$this->app['config']['privateKey']);
        return array_merge($params, [
            'dealTitle'       => $deadTitle,
            'signFieldsRange' => 1,
            'rsaSign'         => $sign,
            'bizInfo'         => $bizInfo == null ? '' :json_encode($bizInfo),
        ]);
    }
}
