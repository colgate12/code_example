<?php

namespace console\controllers;

use marketplace\common\components\apiZendesk\ZendeskApiService;
use marketplace\common\components\MarketException;
use marketplace\common\models\Feedback;
use marketplace\common\models\log\LogError;
use marketplace\common\services\zendesk\FeedbackService;
use Throwable;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class ZendeskController
 * @package console\controllers
 */
class ZendeskController extends BasicCronController
{
    /**
     * @var ZendeskApiService
     */
    private $zendeskApiService;

    /**
     * @var FeedbackService
     */
    private $feedbackService;

    public function __construct(
        $id,
        $module,
        ZendeskApiService $zendeskApiService,
        FeedbackService $feedbackService,
        $config = []
    ) {
        $this->zendeskApiService = $zendeskApiService;
        $this->feedbackService = $feedbackService;
        parent::__construct($id, $module, $config);
    }

    /**
     * To run by current day: ./yii zendesk/save-tickets
     * To run by all time:  ./yii zendesk/save-tickets all
     *
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionSaveTickets(?string $all = null)
    {
        $this->stdout("\nStart: " . date("Y-m-d H:m:s") . "\n");

        try {
            $query = $all !== 'all' ? 'created:' . date('Y-m-d') : null;
            $tickets = $this->zendeskApiService->searchTicket($query);
            $this->parseTickets($tickets);
        } catch (Throwable $e) {
            $this->logException($e);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("\nFinish: " . date("Y-m-d H:m:s") . "\n");
        return ExitCode::OK;
    }

    /**
     * @param $tickets
     * @throws \marketplace\common\components\apiZendesk\ZendeskException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    private function parseTickets($tickets)
    {
        $results = ArrayHelper::getValue($tickets, 'results', []);
        $nextPage = ArrayHelper::getValue($tickets, 'next_page');

        foreach ($results as $item) {
            $ticketId = ArrayHelper::getValue($item, 'id');
            if (!empty($ticketId)) {
                $feedback = Feedback::findOne(['ticket_id' => $ticketId]);
                if (!$feedback) {
                    $attributes = $this->prepareAttributesForSave($item);

                    try {
                        $feedback = $this->feedbackService->createZendeskTicket($attributes);
                        if ($feedback->hasErrors()) {
                            $this->stdout("Save ticket error id= $ticketId\n");
                            LogError::insertError(['message' => $feedback->getErrors(), 'type' => 'zendesk_create_ticket_error']);
                        } else {
                            $this->stdout("Save ticket id = $ticketId\n");
                        }
                    } catch (MarketException $e) {
                        $this->logException($e);
                    }
                }
            }
        }

        if (!empty($nextPage)) {
            $tickets = $this->zendeskApiService->nextPage($nextPage);
            $this->parseTickets($tickets);
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    private function prepareAttributesForSave(array $data)
    {
        $customFields = ArrayHelper::getValue($data, 'custom_fields');

        return [
            'zendesk_ticket_id' => ArrayHelper::getValue($data, 'id'),
            'email' => $this->getCustomFieldById($customFields, Yii::$app->params['zendesk_email_id']),
            'theme_id' => $this->getCustomFieldById($customFields, Yii::$app->params['zendesk_theme_id']),
            'description' => ArrayHelper::getValue($data, 'description'),
            'status' => ArrayHelper::getValue($data, 'status'),
        ];
    }

    /**
     * @param array $fields
     * @param int $id
     * @return string|null
     * @throws \Exception
     */
    private function getCustomFieldById(array $fields, int $id): ?string
    {
        foreach ($fields as $field) {
            $fieldId = (int)ArrayHelper::getValue($field, 'id');
            if ($fieldId === $id) {
                return (string)ArrayHelper::getValue($field, 'value');
            }
        }

        return null;
    }
}
