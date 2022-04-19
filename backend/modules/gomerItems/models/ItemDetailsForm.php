<?php

namespace restapp\modules\gomerItems\models;

use marketplace\common\models\ItemAttachments;
use restapp\modules\gomerItems\interfaces\ConstantInterface;
use restapp\models\forms\BaseFormInterface;
use Yii;

/**
 * @property int $id
 * @property int $sync_source_id
 * @property string $name
 * @property int $rz_status
 * @property string $state
 * @property int $price
 * @property int $rz_item_id
 * @property int $rz_sell_status
 * @property string $price_offer_id
 * @property int $price_old
 * @property int $price_promo
 * @property int $promo_reason
 * @property int $is_promo_sent
 * @property int $max_cart_quantity
 * @property int $min_cart_quantity
 * @property bool $duplicate_mark
 * @property int $is_blocked_by_stop_brands
 * @property int $is_blocked_by_stop_categories
 * @property int $is_blocked_by_stop_words
 * @property int $upload_status
 * @property int $available
 * @property string $vendor
 * @property int $sync_source_category_id
 * @property int $sync_source_vendors_id
 * @property int $price_category_id
 * @property int $sla_id
 * @property int $stock_quantity
 * @property string $rz_created_date
 * @property bool $is_ff
 * @property int $rz_group_id
 * @property string $error_log
 * @property array $category
 * @property string $producer
 * @property array $itemUa
 * @property array $syncSource
 * @property array $syncSourceAttributesValues
 * @property array $syncSourceCategory
 * @property int $error_type
 * @property array $syncSourceAttributes
 * @property array $price_category
 * @property string $article
 * @property string $EAN
 * @property string $delivery
 * @property string $description
 * @property string $descriptionUa
 * @property array $bpm_number
 */
final class ItemDetailsForm extends BaseModel implements BaseFormInterface, ConstantInterface
{
    public $id;
    public $sync_source_id;
    public $name;
    public $rz_status;
    public $state;
    public $price;
    public $rz_item_id;
    public $rz_sell_status;
    public $price_offer_id;
    public $price_old;
    public $price_promo;
    public $promo_reason;
    public $is_promo_sent;
    public $max_cart_quantity;
    public $min_cart_quantity;
    public $duplicate_mark;
    public $is_blocked_by_stop_brands;
    public $is_blocked_by_stop_categories;
    public $is_blocked_by_stop_words;
    public $upload_status;
    public $available;
    public $vendor;
    public $sync_source_category_id;
    public $sync_source_vendors_id;
    public $price_category_id;
    public $sla_id;
    public $stock_quantity;
    public $rz_created_date;
    public $is_ff;
    public $rz_group_id;
    public $category;
    public $producer;
    public $itemUa;
    public $syncSource;
    public $syncSourceAttributesValues;
    public $syncSourceCategory;
    public $error_type;
    public $syncSourceAttributes;
    public $price_category;
    public $article;
    public $EAN;
    public $delivery;
    public $description;
    public $descriptionUa;
    public $bpm_number;
    public $error_log;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                [
                    'id',
                    'sync_source_id',
                    'rz_status',
                    'price',
                    'rz_item_id',
                    'rz_sell_status',
                    'price_old',
                    'price_promo',
                    'is_promo_sent',
                    'max_cart_quantity',
                    'min_cart_quantity',
                    'is_blocked_by_stop_brands',
                    'is_blocked_by_stop_categories',
                    'is_blocked_by_stop_words',
                    'upload_status',
                    'available',
                    'sync_source_category_id',
                    'sync_source_vendors_id',
                    'price_category_id',
                    'sla_id',
                    'stock_quantity',
                    'rz_group_id',
                    'error_type',
                    ],
                'integer'
            ],
            [
                [
                    'name',
                    'state',
                    'vendor',
                    'rz_created_date',
                    'article',
                    'EAN',
                    'delivery',
                    'description',
                    'descriptionUa',
                    'error_log',
                    'price_offer_id',
                ],
                'string'
            ],
            [
                [
                    'duplicate_mark',
                    'is_ff',
                ],
                'boolean'
            ],
            [
                [
                    'category',
                    'syncSource',
                    'syncSourceAttributesValues',
                    'syncSourceCategory',
                    'syncSourceAttributes',
                    'price_category',
                    'bpm_number',
                    'producer',
                    'promo_reason',
                    'itemUa'
                ],
                'safe'
            ],
        ];
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id' => 'rz_item_id',
            'price_id' => 'id',
            'sync_source_id',
            'name',
            'rz_status',
            'state',
            'price',
            'rz_sell_status',
            'price_offer_id',
            'price_old',
            'price_promo',
            'promo_reason',
            'is_promo_sent',
            'max_cart_quantity',
            'min_cart_quantity',
            'duplicate_mark',
            'is_blocked_by_stop_brands',
            'is_blocked_by_stop_categories',
            'is_blocked_by_stop_words',
            'upload_status' => function () {
                return Yii::t('app/gomer_items_upload_status', $this->upload_status);
            },
            'available' => function () {
                return Yii::t('app/gomer_items_available', $this->available);
            },
            'vendor',
            'sync_source_category_id',
            'sync_source_vendors_id',
            'price_category_id',
            'sla_id',
            'stock_quantity',
            'rz_created_date',
            'is_ff',
            'rz_group_id',
            'category',
            'producer',
            'itemUa',
            'syncSource',
            'syncSourceAttributesValues',
            'syncSourceCategory',
            'error_type',
            'syncSourceAttributes',
            'price_category',
            'article',
            'EAN',
            'delivery',
            'description',
            'descriptionUa',
            'error_log',
            'bpm_number',
            'photo_preview' => function () {
                return $this->getItemPhoto();
            },
            'photo' => function () {
                return $this->getItemPhoto();
            },
        ];
    }

    /**
     * @return string|null
     */
    private function getItemPhoto(): ?string
    {
        return ItemAttachments::find()
            ->select(['url'])
            ->andWhere(['item_id' => $this->rz_item_id])
            ->limit(1)
            ->scalar();
    }
}