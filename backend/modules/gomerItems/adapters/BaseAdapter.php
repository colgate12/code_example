<?php

namespace restapp\modules\gomerItems\adapters;

use restapp\components\Serializer;
use restapp\modules\gomerItems\factories\BaseFactoryInterface;
use restapp\modules\gomerItems\interfaces\BaseAdapterInterface;
use yii\httpclient\Response;
use yii\base\BaseObject;

/**
 * Class BaseAdapter
 * @package restapp\models\adapters
 * @property BaseFactoryInterface $_factory
 * @property Serializer $_serializer
 * @property string $_scenario
 */
abstract class BaseAdapter extends BaseObject implements BaseAdapterInterface
{
    protected $_scenario = 'default';
    
    protected $_factory;
    
    protected $_serializer;

    /**
     * BaseAdapter constructor.
     * @param BaseFactoryInterface $factory
     * @param Serializer $serializer
     * @param array $config
     */
    public function __construct(
        BaseFactoryInterface $factory,
        Serializer $serializer,
        array $config = []
    )
    {
        $this->_factory = $factory;
        $this->_serializer = $serializer;
        parent::__construct($config);
    }

    /**
     * @param mixed $scenario
     */
    public function setScenario($scenario): void
    {
        $this->_scenario = $scenario;
    }

    /**
     * @return string
     */
    public function getScenario(): string
    {
        return $this->_scenario;
    }

    /**
     * @param array $params
     * @param array $map
     * @return array
     */
    public function adaptParams(array $params, array $map): array
    {
        $result = $params;

        if ($map) {
            $result = [];

            foreach ($params as $oldAttrName => $oldAttrValue) {
                if (isset($oldAttrValue)) {
                    $resultAttr = $map[$oldAttrName] ?? $oldAttrName;
                    if (is_array($resultAttr)) {
                        $result[key($resultAttr)] = $resultAttr[$oldAttrName]
                            ? $this->adaptParamValue($oldAttrValue, $resultAttr[$oldAttrName])
                            : $oldAttrValue;
                    } else {
                        $result[$resultAttr] = $oldAttrValue;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param string $oldAttrValue
     * @param array $resultAttr
     * @return string
     */
    public function adaptParamValue(string $oldAttrValue, array $resultAttr): string
    {
        $result = [];
        $oldAttrValue = explode(',', $oldAttrValue);

        foreach ($oldAttrValue as $oldValue) {
            $result[] = $resultAttr[$oldValue] ?? $oldValue;
        }

        return implode(', ', $result);
    }

    /**
     * Мапим поля внешнего API на поля системы
     * @return array
     */
    abstract public function responseMap(): array;

    /**
     * Мапим входящие параметры на параметры внешнего API
     * @return array
     */
    abstract public function requestMap(): array;

    /**
     * @return array
     */
    abstract public function errorCodeMap(): array;

    /**
     * @param array $params
     * @param bool $validate
     * @return array
     */
    public function adaptRequest(array $params, bool $validate = true): array
    {
        $params = $this->_serializer->serialize($this->_factory->getModel($params, $validate, $this->_scenario));
        $map = $this->requestMap();

        return $map ? $this->adaptParams($params, $map) : $params;
    }

    /**
     * @param Response $response
     * @param bool $validate
     * @return array
     */
    abstract public function adaptResponse(Response $response, bool $validate = false): array;
}