<?php

namespace restapp\modules\zendesk\models\query;

use restapp\modules\zendesk\models\FeedbackComment;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class FeedbackCommentQuery
 * @package restapp\modules\zendesk\models
 */
final class FeedbackCommentQuery extends ActiveQuery
{
    /**
     * @param int|null $status
     * @param int|null $limit
     * @return array|ActiveRecord[]
     */
    public function getFeedbackCommentsForZendesk(?int $status, ?int $limit): array
    {
        return $this->joinWith('postedFeedback')
            ->andWhere([FeedbackComment::tableName() . '.sent_to_zendesk' => $status])
            ->andWhere(['not', [FeedbackComment::tableName() . '.seller_id' => null]])
            ->limit($limit)
            ->all();
    }
}