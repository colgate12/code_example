<?php

namespace restapp\modules\gomerItems\models;

use Yii;
use yii\base\Model;

/**
 * Class BaseFormModel
 * @package restapp\models\forms
 *
 * @property bool $needStrip
 * @property array $except
 */
class BaseModel extends Model
{
    public const TYPE_JSON = ['json', 'jsonb'];

    /**
     * Максмальное числовое значение
     * @var int
     */
    public const COLUMN_TYPE_INTEGER = 2147483647;

    public const COLUMN_TYPE_MIN_DB_INTEGER = 1;

    public const COLUMN_TYPE_MIN_INTEGER = 0;

    /**
     * Флаг, помечающий нужна ли проверка всех аттрибутов модели на наличие тегов
     * @var bool
     */
    public $needStrip = true;

    /**
     * Массив аттрибутов-исключений из проверки на наличие тегов
     * @var array
     */
    public $except = [];


    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $this->stripTags();
        return parent::beforeValidate();
    }

    /**
     * Валидатор тегов в аттрибутах модели.
     * @return void
     */
    private function stripTags()
    {
        if ($this->needStrip) {
            $this->strip($this->getAttributes());
        }
    }

    /**
     * @param array $attributes
     * @param null $attrName
     */
    private function strip(array $attributes, $attrName = null)
    {
        foreach ($attributes as $attribute => $value) {
            if (!in_array($attribute, $this->except)) {
                if (is_array($value)) {
                    $this->strip($value, $attribute);
                } elseif (is_string($value) && ($value != strip_tags($value))) {
                    $this->addError(
                        $attrName ?? $attribute,
                        Yii::t(
                            'app/validation',
                            'attribute_have_unacceptable_tags',
                            ['attribute' => $attrName]
                        )
                    );
                }
            }
        }
    }

    /**
     * @return string[]
     */
    protected function mapAttributes(): array
    {
        return [];
    }

    /**
     * @param array|null $inputData
     * @param bool $reverseMap
     * @return array
     */
    public function getMappedAttributes(array $inputData = null, bool $reverseMap = false): array
    {
        $result = [];
        $inputData = $inputData ?? $this->getAttributes();
        $map = $reverseMap ? array_flip($this->mapAttributes()) : $this->mapAttributes();
        foreach ($inputData as $key => $value) {
            if (isset($map[$key])) {
                $result[$map[$key]] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array $inputData
     * @param bool $reverseMap
     */
    public function setMappedAttributes(array $inputData, bool $reverseMap = false)
    {
        $result = $this->getMappedAttributes($inputData, $reverseMap);
        $this->setAttributes($result);
    }

    /**
     * @param $attribute
     */
    public function isArrayValidation($attribute)
    {
        if (!is_array($this->$attribute)) {
            $this->addError(
                $attribute,
                Yii::t('app/validation', 'must_be_an_array', ['attribute' => $attribute])
            );
        }
    }

    public function clearAttributes(): void
    {
        foreach ($this->attributes as $attributeName => $attributeValue) {
            $this->$attributeName = null;
        }
    }
}
