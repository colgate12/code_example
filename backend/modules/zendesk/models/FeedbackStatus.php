<?php

namespace restapp\modules\zendesk\models;

use yii\base\Model;

/**
 * Class FeedbackStatus
 */
final class FeedbackStatus extends Model
{
    public $external_id = null;

    public $parent_id = null;

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['external_id', 'parent_id'], 'string'],
            [['external_id', 'parent_id'], 'default', 'value' => null],
        ];
    }

    public function unsetAttributes(): void
    {
        foreach ($this->getAttributes() as $key => $value) {
            $this->$key = null;
        }
    }

    /**
     * @return string
     */
    public function getFeedbackClass(): string
    {
        return $this->parent_id === null
            ? Feedback::class
            : FeedbackComment::class;
    }
}