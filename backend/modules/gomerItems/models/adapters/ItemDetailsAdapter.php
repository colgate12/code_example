<?php

namespace restapp\modules\gomerItems\models\adapters;

use restapp\components\MarketException;
use restapp\components\Serializer;
use restapp\modules\gomerItems\adapters\BaseAdapter;
use restapp\modules\gomerItems\models\factories\ItemDetailsFactory;
use restapp\modules\gomerItems\models\factories\MetaFactory;
use yii\httpclient\Response;

/**
 * Class ItemDetailsAdapter
 * @package restapp\modules\gomerItems\models\adapters
 */
final class ItemDetailsAdapter extends BaseAdapter
{

    /**
     * ModerationRequestFiltersAdapter constructor.
     */
    public function __construct()
    {
        $factory = new ItemDetailsFactory();
        $serializer = new Serializer();
        parent::__construct($factory, $serializer, []);
    }

    /**
     * @return string[]
     */
    public function responseMap(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function requestMap(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function errorCodeMap(): array
    {
        return [];
    }

    /**
     * @param Response $response
     * @param bool $validate
     * @return array
     * @throws MarketException
     */
    public function adaptResponse(Response $response, bool $validate = false): array
    {
        $result = [];
        $data = $response->getData();
        $result['item'] = $this->setSources($data['items'] ?? []);

        return $result;
    }

    /**
     * @param array $sources
     * @return array
     */
    private function setSources(array $sources): array
    {
        $result = [];

        foreach ($sources as $source) {
            $adaptData = $this->adaptParams($source, $this->responseMap());
            $result[] = $this->_serializer->serialize($this->_factory->getModel($adaptData, true));
        }

        return $result;
    }
}