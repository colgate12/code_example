<?php

namespace restapp\modules\zendesk\models;

use DateTime;
use marketplace\common\helpers\FileHelper;
use marketplace\common\models\FeedbackComments;
use restapp\modules\zendesk\ConstantInterface;
use restapp\modules\zendesk\models\query\FeedbackCommentQuery;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * Class FeedbackComment
 * @package restapp\modules\zendesk\models
 */
class FeedbackComment extends FeedbackComments implements ConstantInterface
{
    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'external_id' => function () {
                return $this->zendesk_request_id;
            },
            'parent_id' => 'parentId',
            'message' => 'comment',
            'created_at' => 'dateString',
            'file_urls' => 'filesList',
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
                'directory' => 'feedback-comments',
                'access-token' => getenv('ZENDESK_AUTH_TOKEN')
            ], 'https');
        }, $files);
    }

    /**
     * @return string
     */
    public function getDateString(): string
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $this->created_at)->format(DateTime::RFC3339);
    }

    /**
     * @return FeedbackCommentQuery
     */
    public static function find(): FeedbackCommentQuery
    {
        return new FeedbackCommentQuery(self::class);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'seller_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPostedFeedback(): ActiveQuery
    {
        return $this->hasOne(Feedback::class, ['id' => 'feedback_id'])
            ->andWhere([Feedback::tableName() . '.sent_to_zendesk' => self::_SENT_TO_ZENDESK])
            ->andWhere(['not', [Feedback::tableName() . '.status' => Feedback::STATUS_CLOSED]])
            ->andWhere(['is_show_sellers' => true]);
    }

    /**
     * @param array $files
     * @return bool
     * @throws InvalidConfigException
     */
    public function getInstancesZendesk(array $files): bool
    {
        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $name = uniqid() . ".$extension";
            $filePath = "/tmp/$name";

            file_put_contents($filePath, file_get_contents($file));
            $upFile = FileHelper::uploadFile($name, $filePath);
            array_push($this->tmpFiles, $upFile);
        }

        return true;
    }

    /**
     * @return string
     */
    public function getParentId(): string
    {
        $lastCommentRequestId = self::find()
            ->select(['zendesk_request_id'])
            ->where(['feedback_id' => $this->feedback_id])
            ->andWhere(['not', ['id' => $this->id]])
            ->andWhere(['sent_to_zendesk' => Feedback::SENT_TO_ZENDESK])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->scalar();

        return (string)($lastCommentRequestId ?: $this->feedback_id);
    }
}