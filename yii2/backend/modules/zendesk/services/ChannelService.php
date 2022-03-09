<?php

namespace restapp\modules\zendesk\services;

use restapp\components\loggedExternalServices\LoggedExternalServiceConstants;
use restapp\components\MarketException;
use restapp\helpers\ArrayHelper;
use restapp\helpers\LogHelper;
use restapp\models\Feedback;
use restapp\models\log\LogError;
use marketplace\common\components\apiZendesk\ZendeskApiService;
use restapp\modules\zendesk\forms\SaveCommentForm;
use restapp\modules\zendesk\models\FeedbackComment;
use restapp\modules\zendesk\repository\FeedbackRepository;
use Yii;

/**
 * Class ChannelService
 * @package restapp\modules\zendesk\services
 */
final class ChannelService
{
    private const TYPE_CREATE_RESOURCES = 'resources_created_from_external_ids';
    private const TYPE_PULL = 'pull_request';
    private const TYPE_CREATE_TICKET = 'comment_on_new_ticket';
    private const TYPE_CREATE_COMMENT = 'comment_on_existing_ticket';
    private const TYPE_COMMENT_ON_CLOSED_TICKET = 'comment_on_follow_up_ticket';

    /**
     * @var FeedbackRepository
     */
    private $repository;

    /**
     * @param FeedbackRepository $repository
     */
    public function __construct(FeedbackRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $data
     * @throws \Throwable
     */
    public function parseCallback(array $data): void
    {
        $type = ArrayHelper::getValue($data, 'events.0.type_id');

        switch ($type) {
            case self::TYPE_PULL:
                $this->logPullError($data);
                break;
            case self::TYPE_CREATE_RESOURCES:
                $this->updateTicketsOrComments($data);
                break;
        }
    }

    /**
     * @param SaveCommentForm $form
     * @return string[]
     * @throws MarketException
     * @throws \yii\base\InvalidConfigException
     */
    public function saveComment(SaveCommentForm $form): array
    {
        if (!$form->validate()) {
            throw new MarketException(MarketException::ERROR_CHECK_CORRECTNESS_DATA, $form->getErrors());
        }

        $comment = $this->repository->getFeedbackCommentByZendeskRequestId($form->request_unique_identifier);
        $comment = $comment ?: $this->repository->saveComment($form);

        return [
            'external_id' => $comment->zendesk_request_id
        ];
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function logPullError(array $data): void
    {
        if (isset($data['events'][0]['error'])) {
            LogError::insertError(
                [
                    'type' => 'zendesk_pull_error',
                    'message' => $data['events'][0]['error']
                ]
            );
        }
    }

    /**
     * @param array $data
     * @throws \Throwable
     */
    private function updateTicketsOrComments(array $data): void
    {
        $tickets = ArrayHelper::getValue($data, 'events.0.data.resource_events');

        foreach ($tickets as $ticket) {
            $id = $ticket['external_id'] ?? 0;
            $ticketId = (int) ($ticket['ticket_id'] ?? 0);
            $oldTicketId = (int) ($ticket['follow_up_ticket_id'] ?? 0);
            $resourceType = ArrayHelper::getValue($ticket, 'type_id');

            switch ($resourceType) {
                case self::TYPE_CREATE_COMMENT:
                    $this->updateComment($id);
                    break;
                case self::TYPE_COMMENT_ON_CLOSED_TICKET:
                    $this->updateClosedTicket($id, $ticketId, $oldTicketId);
                    break;
                case self::TYPE_CREATE_TICKET:
                    $this->updateTicket((int)$id, $ticketId);
                    break;
            }
        }
    }

    /**
     * @param string $uuid
     * @throws \Exception
     */
    private function updateComment(string $uuid)
    {
        $feedbackComment = $this->repository->getFeedbackCommentByZendeskRequestId($uuid);

        if ($feedbackComment) {
            $feedbackComment->setScenario(FeedbackComment::SCENARIO_UPDATE_SENT_TO_ZENDESK);

            if ($feedbackComment->save() === false) {
                LogError::insertValidateError($feedbackComment);
            }
        }
    }

    /**
     * @param int $id
     * @param int $ticketId
     * @throws \Throwable
     */
    private function updateTicket(int $id, int $ticketId)
    {
        $feedback = $this->repository->getFeedbackById($id);

        if ($feedback) {
            try {
                if ($feedback->ticket_id) {
                    //меняем статус старого тикета на закрытый в zendesk
                    /** @var ZendeskApiService $form */
                    $apiService = Yii::createObject(ZendeskApiService::class);
                    $apiService->changeTicketStatus($feedback->ticket_id, Feedback::STATUS_CLOSED);
                }

                //проверяем есть ли уже тикет, который задублировался по тригеру и удаляем его
                $feedbackFromTriger = $this->repository->getFeedbackByTicketId($ticketId);
                if ($feedbackFromTriger) {
                    $feedbackFromTriger->delete();
                }

                //обновляем id старого тикета на новый
                $feedback->setScenario(Feedback::SCENARIO_UPDATE_TICKET_ID_ZENDESK);
                $feedback->setAttribute('ticket_id', $ticketId);

                if ($feedback->save() === false) {
                    LogError::insertValidateError($feedback);
                }
            } catch (\Throwable $e) {
                LogError::insertException($e);
            }
        }
    }

    /**
     * @param string $uuid
     * @param int $newTicketId
     * @param int $oldTicketId
     * @throws \Exception
     */
    private function updateClosedTicket(string $uuid, int $newTicketId, int $oldTicketId)
    {
        $feedback = $this->repository->getFeedbackByTicketId($oldTicketId);

        if ($feedback) {
            //обновляем id старого тикета на новый
            $feedback->setScenario(Feedback::SCENARIO_UPDATE_TICKET_ID_ZENDESK);
            $feedback->setAttribute('ticket_id', $newTicketId);

            if ($feedback->save() === false) {
                LogError::insertValidateError($feedback);
            }
        }

        $this->updateComment($uuid);
    }
}