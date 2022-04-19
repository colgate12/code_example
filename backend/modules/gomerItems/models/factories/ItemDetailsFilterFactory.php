<?php

namespace restapp\modules\gomerItems\models\factories;

use restapp\models\factories\BaseFactory;
use restapp\models\forms\BaseFormInterface;
use restapp\modules\gomerItems\models\ItemDetailsFilterForm;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class ItemDetailsFilterFactory
 * @package restapp\modules\gomerItems\models\factories
 */
final class ItemDetailsFilterFactory extends BaseFactory
{
    /**
     * @return BaseFormInterface
     * @throws InvalidConfigException
     */
    public function make(): BaseFormInterface
    {
        return Yii::createObject(ItemDetailsFilterForm::class);
    }
}