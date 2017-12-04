<?php

namespace Damon\YouzanPay;

use Monolog\Logger;
use Pimple\Container;
use Damon\YouzanPay\Core\Token;
use Doctrine\Common\Cache\Cache;
use Monolog\Handler\NullHandler;
use Illuminate\Config\Repository;
use Damon\YouzanPay\QrCode\QrCode;
use Monolog\Handler\StreamHandler;
use Doctrine\Common\Cache\FilesystemCache;

class Application extends Container
{
    public function __construct(array $config)
    {
        parent::__construct();

        $this->registerConfig($config);

        $this->setCacheDriver(new FilesystemCache(sys_get_temp_dir()));

        $this->setLoggerDriver($this->monolog());

        $this->registerCoreService();

        $this->registerService();
    }

    /**
     * Register Config Repository
     *
     * @param  array  $config
     *
     * @return void
     */
    protected function registerConfig($config)
    {
        $this['config'] = function () use ($config) {
            return new Repository($config);
        };
    }

    /**
     * Set Cache driver
     *
     * @param Doctrine\Common\Cache\Cache $cache
     */
    protected function setCacheDriver(Cache $cache)
    {
        $this['cache'] = $cache;
    }

    /**
     * Monolog instance
     *
     * @return Monolog\Logger
     */
    protected function monolog()
    {
        $logger = new Logger('damon.youzan-pay');
        if ($this['config']['log']['debug']) {
            $logger->pushHandler(new NullHandler);
        } else {
            $logger->pushHandler(new StreamHandler($this['config']['log']['path']), Logger::DEBUG);
        }

        return $logger;
    }

    /**
     * Set Cache driver
     *
     * @param Doctrine\Common\Cache\Cache $logger
     */
    protected function setLoggerDriver($logger)
    {
        $this['logger'] = $logger;
    }

    /**
     * Register Core Services
     *
     * @return void
     */
    protected function registerCoreService()
    {
        $this['token'] = function () {
            return new Token($this['config']['client_id'],
                            $this['config']['client_secret'],
                            $this['config']['store_id'],
                            $this['cache']
                        );
        };
    }

    protected function registerService()
    {
        $this['qrcode'] = function () {
            return new QrCode($this);
        };
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return $this[$name];
    }
}
