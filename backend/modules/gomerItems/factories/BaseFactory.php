<?php

namespace restapp\modules\gomerItems\factories;

use restapp\components\MarketException;
use restapp\models\forms\BaseFormInterface;
use yii\base\BaseObject;
use Yii;

/**
 * Class BaseFactory
 */
abstract class BaseFactory extends BaseObject implements BaseFactoryInterface
{
    /**
     * @return BaseFormInterface
     */
    abstract public function make(): BaseFormInterface;

    /**
     * @param array $config
     * @param bool $validate
     * @param string $scenario
     * @return BaseFormInterface
     * @throws MarketException
     */
    public function getModel(
        array $config = [],
        bool $validate = false,
        string $scenario = 'default'
    ): BaseFormInterface {
        $object = $this->make();
        $object->setScenario($scenario);

        if ($config) {
            $object->setAttributes($config);
        }

        if ($validate && $object->validate() === false) {
            throw new MarketException(MarketException::ERROR_CHECK_CORRECTNESS_DATA, $object->getErrors());
        }

        return $object;
    }
}