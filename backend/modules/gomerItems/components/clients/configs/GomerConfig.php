<?php

namespace restapp\modules\gomerItems\components\clients\configs;

use restapp\components\api\configs\BaseConfig;

/**
 * Class GomerConfig
 * @package restapp\modules\gomerItems\components\clients\configs
 */
final class GomerConfig extends BaseConfig
{
    public function __construct()
    {
        parent::__construct(
            getenv('GOMER_API_V2_CONNECT_USERNAME'),
            getenv('GOMER_API_V2_CONNECT_PASSWORD'),
            getenv('GOMER_API_V2_CONNECT_URL'),
            'yii\httpclient\CurlTransport'
        );
    }
}