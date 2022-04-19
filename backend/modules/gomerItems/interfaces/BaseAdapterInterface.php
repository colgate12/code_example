<?php

namespace restapp\modules\gomerItems\interfaces;

use marketplace\common\interfaces\forms\BaseFormInterface;
use yii\httpclient\Response;

/**
 * Interface BaseAdapterInterface
 * @package restapp\models\adapters
 */
interface BaseAdapterInterface
{
    /**
     * @param array $params
     * @param array $map
     * @return array
     */
    public function adaptParams(array $params, array $map): array;

    /**
     * @return array
     */
    public function responseMap(): array;

    /**
     * @return array
     */
    public function requestMap(): array;

    /**
     * @return array
     */
    public function errorCodeMap(): array;

    /**
     * @param array $params
     * @param bool $validate
     * @return array
     */
    public function adaptRequest(array $params, bool $validate = true): array;


    /**
     * @param Response $response
     * @param bool $validate
     * @return array
     */
    public function adaptResponse(Response $response, bool $validate = false): array;
}