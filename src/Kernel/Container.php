<?php

/**
 * EaseBaidu - A PHP Framework For Baidu SDK
 *
 * @package  Easeava/baidu
 * @author   Easeava <tthd@163.com>
 */

namespace EaseBaidu\Kernel;

use EaseBaidu\Kernel\Providers\ConfigServiceProvider;
use EaseBaidu\Kernel\Providers\HttpClientServiceProviders;
use EaseBaidu\Kernel\Providers\LogServiceProvider;
use EaseBaidu\Kernel\Providers\RequestServiceProvider;
use Pimple\Container as ServiceContainer;

class Container extends ServiceContainer
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var array
     */
    protected $default = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * Container constructor.
     *
     * @param array $config
     * @param array $prepends
     * @param null $id
     */
    public function __construct(array $config = [], $prepends = [], $id = null)
    {
        $this->registerProviders($this->getProviders());
        parent::__construct($prepends);

        $this->id = $id;
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getProviders(): array
    {
        return array_merge([
            ConfigServiceProvider::class,
            RequestServiceProvider::class,
            HttpClientServiceProviders::class,
            LogServiceProvider::class,
        ], $this->providers);
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        $base = [
            // http://docs.guzzlephp.org/en/stable/request-options.html
            'http' => [
                'timeout' => 30.0,
                'base_uri' => 'https://api.weixin.qq.com/',
            ],
        ];

        return array_merge_recursive($base, $this->default, $this->config);
    }

    /**
     * @param $id
     * @param $value
     */
    public function rebind($id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * @param $id
     * @param $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}
