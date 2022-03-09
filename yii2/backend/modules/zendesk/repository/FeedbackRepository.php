<?php
namespace restapp\modules\zendesk\repository;

use restapp\components\MarketException;
use restapp\models\log\LogError;
use restapp\modules\zendesk\ConstantInterface;
use restapp\modules\zendesk\forms\SaveCommentForm;
use restapp\modules\zendesk\models\Feedback;
use restapp\modules\zendesk\models\FeedbackComment;
use yii\db\ActiveRecord;
use marketplace\common\services\zendesk\repository\FeedbackRepository as CommonFeedbackRepository;

/**
 * Class FeedbackRepository
 *
 * @package restapp\modules\zendesk\repository
 */
class FeedbackRepository extends CommonFeedbackRepository implements ConstantInterface
{
    /**
     * @return array|ActiveRecord[]
     */
    public function getFeedbacksForZendesk(): array
    {
        return Feedback::find()
            ->getFeedbacksForZendesk(self::_NOT_SENT_TO_ZENDESK, self::ZENDESK_FEEDBACKS_LIMIT);
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getFeedbackCommentsForZendesk(): array
    {
        return FeedbackComment::find()
            ->getFeedbackCommentsForZendesk(self::_NOT_SENT_TO_ZENDESK, self::ZENDESK_FEEDBACK_COMMENTS_LIMIT);
    }

    /**
     * @param int $ticketId
     * @return Feedback|null
     */
    public function getFeedbackByTicketId(int $ticketId): ?Feedback
    {
        return Feedback::findOne(['ticket_id' => $ticketId]);
    }

    /**
     * @param int $id
     * @return Feedback|null
     */
    public function getFeedbackById(int $id): ?Feedback
    {
        return Feedback::findOne(['id' => $id]);
    }

    /**
     * @param string $zendeskRequestId
     * @return FeedbackComment|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getFeedbackCommentByZendeskRequestId(string $zendeskRequestId): ?FeedbackComment
    {
        return FeedbackComment::findOne(['zendesk_request_id' => $zendeskRequestId]);
    }

    /**
     * @return array
     */
    public function getTicketsForPull(): array
    {
        return Feedback::find()
            ->andWhere(['not', ['ticket_id' => null]])
            ->andWhere(['not', ['seller_id' => null]])
            ->andWhere(['or',
                ['sent_to_zendesk' => Feedback::NOT_SENT_TO_ZENDESK],
                ['sent_to_zendesk' => null]
            ])
            ->all();
    }

    /**
     * @param SaveCommentForm $form
     * @return FeedbackComment
     * @throws MarketException
     */
    public function saveComment(SaveCommentForm $form)
    {
        $comment = new FeedbackComment();
        $comment->setScenario(FeedbackComment::SCENARIO_NEW_FROM_ZENDESK);
        $comment->setAttributes([
            'feedback_id' => $form->thread_id,
            'comment' => $form->message,
            'zendesk_request_id' => $form->request_unique_identifier,
        ]);
        $comment->getInstancesZendesk($form->file_urls ?: []);

        if ($comment->save() === false) {
            LogError::insertValidateError($comment);
            throw new MarketException(MarketException::ERROR_SAVE_MODEL, $comment->getErrors());
        }

        return $comment;
    }
}