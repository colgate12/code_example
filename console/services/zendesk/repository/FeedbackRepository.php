<?php
namespace marketplace\common\services\zendesk\repository;

use marketplace\common\models\FeedbackTheme;
use marketplace\common\models\Seller;

/**
 * Class FeedbackRepository
 *
 * @package marketplace\common\services\zendesk\repository
 */
class FeedbackRepository
{
    /**
     * @param string $feedbackThemeId
     * @return FeedbackTheme|null
     */
    public function getFeedbackThemeByZendeskKey(string $feedbackThemeId): ?FeedbackTheme
    {
        return FeedbackTheme::findOne(['zen_desk_key' => $feedbackThemeId]);
    }

    /**
     * @param string $email
     * @return Seller|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getFeedbackSeller(string $email): ?Seller
    {
        return Seller::findOne(['email' => $email]);
    }
}