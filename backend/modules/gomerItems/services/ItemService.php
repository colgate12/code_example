<?php

namespace restapp\modules\gomerItems\services;

use restapp\components\MarketException;
use restapp\modules\gomerItems\components\clients\configs\GomerConfig;
use restapp\modules\gomerItems\components\clients\GomerItemClient;
use restapp\modules\gomerItems\components\errors\GomerApiValidationException;
use restapp\modules\gomerItems\interfaces\ConstantInterface;
use restapp\modules\gomerItems\models\adapters\ItemDetailsAdapter;
use restapp\modules\gomerItems\models\adapters\ItemDetailsFilterAdapter;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

/**
 * Class ItemService
 * @package restapp\modules\gomerItems\services
 */
final class ItemService implements ConstantInterface
{
    /**
     * @param array $params
     * @return array
     * @throws Exception
     * @throws MarketException
     */
    public function getDetails(array $params): array
    {
        $requestAdapter = new ItemDetailsFilterAdapter();
        $responseAdapter = new ItemDetailsAdapter();

        $clientConfig = new GomerConfig();
        $client = new GomerItemClient($clientConfig);

        $response = $client->getItemDetails($requestAdapter->adaptRequest($params));
        return $responseAdapter->adaptResponse($response);
    }
}