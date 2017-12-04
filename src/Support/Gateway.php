<?php

namespace Damon\YouzanPay\Support;

use Zttp\Zttp;
use Damon\YouzanPay\Application;

abstract class Gateway
{
    const API_GATEWAY = 'https://open.youzan.com/api/oauthentry/%s/%s/%s?access_token=%s';

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    protected function request(array $parameters = [])
    {
        $response = Zttp::get($this->getFullGateway(), $parameters);

        return $response->json();
    }

    protected function getFullGateway()
    {
        return sprintf(self::API_GATEWAY, $this->gateway(), $this->version(), $this->method(), $this->app->token->getToken(true));
    }

    protected function version()
    {
        return '3.0.0';
    }

    abstract protected function gateway();

    abstract protected function method();
}
