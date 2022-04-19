<?php

namespace restapp\modules\gomerItems\models;

use restapp\models\forms\BaseFormInterface;
use restapp\modules\gomerItems\interfaces\ConstantInterface;
use Yii;

/**
 * @property int $market_id
 * @property int $sync_source_id
 * @property int $rz_item_id
 */
final class ItemDetailsFilterForm extends BaseModel implements BaseFormInterface, ConstantInterface
{
    public $market_id;

    public $sync_source_id;

    public $rz_item_id;

    public $item_id;

    public function init()
    {
        $this->market_id = Yii::$app->user->identity->market->owox_id_history;
        parent::init();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['rz_item_id'], 'required', 'when' => function () {
                return empty($this->item_id);
            }],
            [['item_id'], 'required', 'when' => function () {
                return empty($this->rz_item_id);
            }],
            [['sync_source_id', 'rz_item_id'], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'market_id',
            'sync_source_id',
            'rz_item_id',
            'item_id',
        ];
    }
}