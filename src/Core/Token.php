<?php

namespace Damon\YouzanPay\Core;

use Zttp\Zttp;

class Token
{
    const TOKEN_CACHE_KEY = 'damon.youzan.pay.core.token.';

    const API_TOKEN_GATEWAY = 'https://open.youzan.com/oauth/token';

    const GRANT_TYPE = 'silent';

    protected $clientId;

    protected $clientSecret;

    protected $storeId;

    protected $cache;

    public function __construct($clientId, $clientSecret, $storeId, $cache)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->storeId = $storeId;
        $this->cache = $cache;
    }

    public function getToken($forceRefresh = false)
    {
        $cacheKey = $this->getCacheKey();
        $cached = $this->cache->fetch($cacheKey);

        if (! $cached || $forceRefresh) {
            $token = $this->getTokenFromServer();

            $this->cache->save($cacheKey, $token['access_token'], $token['expires_in'] - 1500);

            return $token['access_token'];
        }

        return $cached;
    }

    protected function getTokenFromServer()
    {
        $response = Zttp::post(self::API_TOKEN_GATEWAY, [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => self::GRANT_TYPE,
                'kdt_id' => $this->storeId
            ]);

        return $response->json();
    }

    protected function getCacheKey()
    {
        return self::TOKEN_CACHE_KEY . $this->clientId;
    }
}
