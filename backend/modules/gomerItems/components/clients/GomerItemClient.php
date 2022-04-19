<?php

namespace restapp\modules\gomerItems\components\clients;

use marketplace\common\models\log\LogApiGomer;
use restapp\components\api\BaseClient;
use restapp\components\api\configs\BaseConfig;
use restapp\components\loggedExternalServices\ResponseLogTrait;
use restapp\components\MarketException;
use Yii;
use yii\httpclient\Exception;
use yii\httpclient\Request;
use yii\httpclient\Response;

/**
 * Class GomerItemClient
 */
final class GomerItemClient extends BaseClient
{
    use ResponseLogTrait;

    private const ITEM_DETAILS_URL = 'items/details';

    public function __construct(BaseConfig $clientConfiguration)
    {
        $this->setUserName($clientConfiguration->login);
        $this->setPassword($clientConfiguration->password);
        $this->setBaseUrl($clientConfiguration->baseUrl);
        $this->setTransport($clientConfiguration->transport);

        if (isset(Yii::$app->params['api-timeout']['items-gomer-update'])) {
            $this->requestConfig['options']['timeout'] = Yii::$app->params['api-timeout']['items-gomer-update'];
        }

        parent::__construct([]);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $headers = [
            'Authorization' => 'Basic ' . base64_encode($this->userName . ":" . $this->password)
        ];

        return array_merge($headers, parent::getHeaders());
    }

    /**
     * @param Request $request
     * @param Response $response
     * @throws Exception
     */
    protected function log(Request $request, Response $response): void
    {
        $this->logBadResponse($request, $response);

        $log = new LogApiGomer();
        $log->setAttributes([
            'request' => $request->getContent(),
            'response' => $response->getContent(),
            'response_code' => $response->getStatusCode(),
            'entity' => substr($request->url, 0, strpos($request->url, '/')),
            'url' => $request->getFullUrl(),
            'request_type' => strtolower($request->getMethod()),
            'exec_time' => $this->endTime - $this->startTime,
        ], false);

        $log->save();
    }

    /**
     * @param array $params
     * @return Response
     * @throws Exception
     * @throws MarketException
     */
    public function getItemDetails(array $params): Response
    {
        $result = $this->get(self::ITEM_DETAILS_URL, $params, $this->getHeaders())->send();
        $this->checkResponseErrors($result);
        return $result;
    }

}