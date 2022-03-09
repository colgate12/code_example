<?php

namespace restapp\modules\zendesk\models\query;

use restapp\models\Feedback;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class FeedbackQuery
 * @package restapp\modules\zendesk\models
 */
final class FeedbackQuery extends ActiveQuery
{
    /**
     * @param int|null $status
     * @param int|null $limit
     * @return array|ActiveRecord[]
     */
    public function getFeedbacksForZendesk(?int $status, ?int $limit): array
    {
        return $this->andWhere(['not', ['seller_id' => null]])
            ->andWhere([
                'or',
                ['sent_to_zendesk' => $status],
                ['sent_to_zendesk' => null]
            ])
            ->andWhere(['not', ['status' => Feedback::STATUS_OLD]])
            ->andWhere(['is_show_sellers' => true])
            ->limit($limit)
            ->all();
    }
}