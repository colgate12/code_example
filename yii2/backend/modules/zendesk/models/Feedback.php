<?php

namespace restapp\modules\zendesk\models;

use DateTime;
use yii\helpers\Url;
use restapp\models\Feedback as BaseFeedback;
use restapp\modules\zendesk\ConstantInterface;
use restapp\modules\zendesk\models\query\FeedbackQuery;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * Class Feedback
 * @package restapp\modules\zendesk\models
 * @property Author $author
 */
class Feedback extends BaseFeedback implements ConstantInterface
{
    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'external_id' => function() {
                return (string) $this->id;
            },
            'message' => 'text',
            'created_at' => 'dateString',
            'file_urls' => 'filesList',
            'fields' => 'extraFields',
            'author'
        ];
    }

    /**
     * @return array
     */
    public function getFilesList(): array
    {
        $files =  $this->files ? explode(',', $this->files) : [];
        return array_map(function (string $file) {
            return Url::to([
                '/zendesk/channel/get-storage-file',
                'file' => $file,
                'access-token' => getenv('ZENDESK_AUTH_TOKEN')
            ], 'https');
        }, $files);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'seller_id']);
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function getDateString(): string
    {
        return DateTime::createFromFormat(
            'Y-m-d H:i:s',
            date('Y-m-d H:i:s', $this->created_at)
        )->format(DateTime::RFC3339);
    }

    /**
     * @return FeedbackQuery
     */
    public static function find(): FeedbackQuery
    {
        return new FeedbackQuery(self::class);
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return '№' . $this->id . ' Продавец ' . $this->market->title . '(' . $this->market_id . ')' . ' задал вопрос по теме ' . $this->theme->title_lang_ru;
    }

    /**
     * @return array[]
     */
    public function getExtraFields(): array
    {
        return [
            [
                'id' => Yii::$app->params['zendesk_market_id_id'],
                'value' => $this->market_id,
            ],
            [
                'id' => Yii::$app->params['zendesk_seller_id_id'],
                'value' => $this->seller_id,
            ],
            [
                'id' => Yii::$app->params['zendesk_market_name_id'],
                'value' => $this->market->title,
            ],
            [
                'id' => Yii::$app->params['zendesk_owox_id_id'],
                'value' => $this->market->owox_id_history,
            ],
            [
                'id' => Yii::$app->params['zendesk_email_id'],
                'value' => $this->seller->email,
            ],
            [
                'id' => 'tags',
                'value' => [
                    Yii::$app->params['zendesk_tag'],
                    $this->theme->zen_desk_key,
                    !empty($this->theme->parent) ? $this->theme->parent->zen_desk_key : null,
                    !empty($this->market->curator) ? $this->market->curator->login : null
                ],
            ],
            [
                'id' => 'status',
                'value' => $this->status,
            ],
            [
                'id' => 'subject',
                'value' => $this->getSubject(),
            ],
        ];
    }
}


