<?php

namespace restapp\modules\gomerItems\models\factories;

use restapp\modules\gomerItems\factories\BaseFactory;
use restapp\models\forms\BaseFormInterface;
use restapp\modules\gomerItems\models\ItemDetailsForm;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class ItemDetailsFactory
 * @package restapp\modules\gomerItems\models\factories
 */
final class ItemDetailsFactory extends BaseFactory
{

    /**
     * @return BaseFormInterface
     * @throws InvalidConfigException
     */
    public function make(): BaseFormInterface
    {
        return Yii::createObject(ItemDetailsForm::class);
    }
}