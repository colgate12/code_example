<?php

namespace marketplace\common\components\apiZendesk;

use marketplace\common\library\ZenDeskApi;
use yii\httpclient\Client;
use yii\httpclient\Response;

/**
 * Class ZendeskApiAdapter
 *
 * @package marketplace\common\components\apiZendesk
 */
class ZendeskApiAdapter extends ZenDeskApi
{
    const POST = 'POST';
    const GET  = 'GET';
    const PUT  = 'PUT';

    /**
     * @var string
     */
    private $url;

    /**
     * @var string (POST|GET)
     */
    private $method;

    /**
     * @var array
     */
    private $data = [];

    /**
     * ZendeskApiAdapter constructor.
     *
     * @param string $url
     * @param string $method
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct(string $url, string $method = self::GET)
    {
        $this->url         = $url;
        $this->method      = $method;
        parent::__construct();
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return Response
     * @throws ZendeskException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function sendRequest(): Response
    {
        $response = $this->createRequest()
            ->setMethod($this->method)
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($this->baseUrl . $this->url)
            ->setData($this->data)
            ->addHeaders(['Content-Type' => 'application/json'])
            ->send();
        if (!$response->getIsOk()) {
            throw new ZendeskException($response->getData());
        }

        return $response;
    }
}