<?php

namespace restapp\modules\zendesk\services;

use marketplace\common\components\MarketException as CommonMarketException;
use restapp\helpers\LogHelper;
use restapp\modules\zendesk\ConstantInterface;
use restapp\modules\zendesk\forms\CreateTicketForm;
use restapp\modules\zendesk\forms\UpdateTicketStatusForm;
use restapp\modules\zendesk\models\Feedback;
use restapp\modules\zendesk\repository\FeedbackRepository;
use yii\base\InvalidConfigException;
use restapp\modules\zendesk\models\FeedbackStatus;
use restapp\components\MarketException;
use restapp\models\log\LogError;
use marketplace\common\services\zendesk\FeedbackService as FeedbackServiceCommon;

/**
 * Class FeedbackService
 * @package restapp\modules\zendesk\services
 */
final class FeedbackService extends FeedbackServiceCommon implements ConstantInterface
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
     * @return array[]
     * @throws InvalidConfigException
     */
    public function getFeedbacksWithComments(): array
    {
        try {
            $feedbacks = $this->repository->getFeedbacksForZendesk();
            $feedbackComments = $this->repository->getFeedbackCommentsForZendesk();
            $result = array_merge($feedbacks, $feedbackComments);
        } catch (\Exception $e) {
            $result = [];
            LogHelper::addErrorLog(
                json_encode(
                    [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]
                )
            );
        }

        return ['external_resources' => $result];
    }

    /**
     * @param FeedbackStatus $status
     * @return bool
     * @throws InvalidConfigException
     */
    public function updateFeedbackStatus(FeedbackStatus $status): bool
    {
        try {
            $feedback = $status->getFeedbackClass()::updateAll(
                ['sent_to_zendesk' => self::_SENT_TO_ZENDESK],
                ['id' => $status->external_id]
            );

            if (!$feedback) {
                LogHelper::addErrorLog(json_encode($status));
            }

            return $feedback;
        } catch (\Throwable $e) {
            LogHelper::addErrorLog(
                json_encode(
                    [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]
                )
            );
        }

        return false;
    }

    /**
     * @param UpdateTicketStatusForm $form
     * @throws MarketException
     */
    public function updateTicketStatus(UpdateTicketStatusForm $form)
    {
        if (!$form->validate()) {
            throw new MarketException(MarketException::ERROR_CHECK_CORRECTNESS_DATA, $form->getErrors());
        }

        $feedback = $this->repository->getFeedbackByTicketId((int)$form->zendesk_ticket_id);
        $feedback->setScenario(Feedback::SCENARIO_UPDATE_STATUS_ZENDESK);
        $feedback->setAttribute('status', $form->status);

        if ($feedback->save() === false) {
            LogError::insertValidateError($feedback);
            throw new MarketException(MarketException::ERROR_SAVE_MODEL, $feedback->getErrors());
        }
    }

    /**
     * @param CreateTicketForm $form
     * @throws InvalidConfigException
     * @throws MarketException
     * @throws CommonMarketException
     */
    public function createTicket(CreateTicketForm $form)
    {
        if (!$form->validate()) {
            LogHelper::addErrorLog(['details' => $form->getErrors()]);
            throw new MarketException(MarketException::ERROR_CHECK_CORRECTNESS_DATA, $form->getErrors());
        }

        /* @var $feedback Feedback */
        $feedback = $this->createZendeskTicket($form->attributes);

        if ($feedback->hasErrors()) {
            LogError::insertValidateError($feedback);
            throw new MarketException(MarketException::ERROR_SAVE_MODEL, $feedback->getErrors());
        }
    }
}