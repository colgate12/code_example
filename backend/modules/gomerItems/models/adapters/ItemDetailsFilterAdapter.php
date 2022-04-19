<?php

namespace restapp\modules\gomerItems\models\adapters;

use restapp\modules\gomerItems\adapters\BaseAdapter;
use \yii\rest\Serializer;
use restapp\modules\gomerItems\models\factories\ItemDetailsFilterFactory;
use yii\httpclient\Response;

/**
 * Class ItemDetailsFilterAdapter
 * @package restapp\modules\gomerItems\models\adapters
 */
final class ItemDetailsFilterAdapter extends BaseAdapter
{
    /**
     * ModerationRequestFiltersAdapter constructor.
     */
    public function __construct()
    {
        $factory = new ItemDetailsFilterFactory();
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
     */
    public function adaptResponse(Response $response, bool $validate = false): array
    {
        return [];
    }
}