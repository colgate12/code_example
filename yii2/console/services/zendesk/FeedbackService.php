<?php
namespace marketplace\common\services\zendesk;

use marketplace\common\models\Feedback;
use marketplace\common\components\MarketException;
use yii\helpers\ArrayHelper;
use marketplace\common\services\zendesk\repository\FeedbackRepository;

/**
 * Class FeedbackService
 */
class FeedbackService
{
    /**
     * @var FeedbackRepository
     */
    public $repository;

    /**
     * @param FeedbackRepository $repository
     */
    public function __construct(FeedbackRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $data
     * @return Feedback
     * @throws MarketException
     */
    public function createZendeskTicket(array $data): Feedback
    {
        $feedbackThemeId = ArrayHelper::getValue($data, 'theme_id');
        $email = ArrayHelper::getValue($data, 'email');
        if (empty($feedbackThemeId) || empty($email)) {
            throw new MarketException(MarketException::ERROR_NOT_FIND_PARAMS);
        }

        $feedbackTheme = $this->repository->getFeedbackThemeByZendeskKey($feedbackThemeId);
        $seller = $this->repository->getFeedbackSeller($email);
        if (!$feedbackTheme || !$seller) {
            throw new MarketException(MarketException::ERROR_ENTITY_NOT_FOUND);
        }

        $feedback = new Feedback();
        $feedback->setScenario(Feedback::SCENARIO_CREATE_TICKET_ZENDESK);
        $feedback->setAttributes([
            'ticket_id' => ArrayHelper::getValue($data, 'zendesk_ticket_id'),
            'market_id' => $seller->market_id,
            'seller_id' => $seller->id,
            'theme_id' => $feedbackTheme->id,
            'text' => ArrayHelper::getValue($data, 'description'),
            'status' => ArrayHelper::getValue($data, 'status'),
        ]);
        $feedback->getInstancesZendesk($data['files'] ?? []);

        $feedback->save();
        return $feedback;
    }

}
