<?php

namespace Damon\YouzanPay\Trade;

use Damon\YouzanPay\Support\Gateway;

class Trade extends Gateway
{
    const TRADE_WAIT_BUYER_PAY = 'WAIT_BUYER_PAY';

    const TRADE_SUCCESS = 'TRADE_SUCCESS';

    const TRADE_CLOSED = 'TRADE_CLOSED';

    /**
     * @var array
     */
    protected $fields = [
        'qr_id',
        'status'
    ];

    /**
     * Response
     *
     * @var array
     */
    protected $response;

    protected function gateway()
    {
        return 'youzan.trade';
    }

    protected function get(array $parameters = [])
    {
        return $this->setMethod('get')->request($this->mergeParameters($parameters));
    }

    /**
     * Append default parameters
     *
     * @param  array  $parameters
     *
     * @return array
     */
    protected function mergeParameters(array $parameters)
    {
        return array_merge([
            'fields' => $this->fields,
            'with_childs' => false
        ], $parameters);
    }

    /**
     * Get value from request
     *
     * @param  string  $name
     *
     * @return mixed
     */
    protected function request($name)
    {
        $source = json_decode(file_get_contents('php://input'), true);

        return array_get($source, $name);
    }

    /**
     * Get data
     *
     * @return array
     */
    protected function getData()
    {
        $response = $this->get([
            'tid' => $this->request('tid')
        ]);

        return $response['response']['trade'];
    }

    /**
     * Is wait pay
     *
     * @return boolean
     */
    public function isWaitPay()
    {
        return array_get($this->getData(), 'status') === self::TRADE_WAIT_BUYER_PAY;
    }

     /**
     * Is successfully
     *
     * @return boolean
     */
    public function isSuccessfully()
    {
        return array_get($this->getData(), 'status') === self::TRADE_SUCCESS;
    }

     /**
     * Is closed.
     *
     * @return boolean
     */
    public function isClosed()
    {
        return array_get($this->getData(), 'status') === self::TRADE_CLOSED;
    }
}
