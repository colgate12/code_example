<?php

namespace restapp\modules\zendesk\controllers;

use restapp\modules\zendesk\behaviors\ZendeskQueryParamsAuth;
use restapp\modules\zendesk\forms\CreateTicketForm;
use restapp\modules\zendesk\forms\UpdateTicketStatusForm;
use restapp\modules\zendesk\models\FeedbackStatus;
use restapp\modules\zendesk\models\ZendeskVirtualUser;
use restapp\modules\zendesk\services\FeedbackService;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\base\Module;
use restapp\components\MarketException;

/**
 * Class FeedbackController
 * @package restapp\modules\zendesk\controllers
 */
class FeedbackController extends BaseZendeskController
{
    public $modelClass = false;

    /**
     * @var FeedbackService
     */
    protected $service;

    /**
     * FeedbackController constructor.
     * @param string $id
     * @param Module $module
     * @param FeedbackService $service
     * @param array $config
     */
    public function __construct(
        string $id,
        Module $module,
        FeedbackService $service,
        array $config = []
    ) {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function behaviors(): array
    {
        $behaviors  = parent::behaviors();

        $behaviors['authenticator']['class'] = ZendeskQueryParamsAuth::class;
        $behaviors['authenticator']['user'] = Yii::createObject(ZendeskVirtualUser::class);

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['view'], $actions['index']);

        return $actions;
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function actionFeedbacks(): array
    {
        return $this->service->getFeedbacksWithComments();
    }

    /**
     * Приклади очікуваних вхідних даних:
     *
     * @example Feedback
     *  {
     *      "external_id": 2,
     *      "parent_id": null
     *  }
     *
     * @example FeedbackComment
     *  {
     *      "external_id": 1,
     *      "parent_id": 2
     *  }
     *
     * @throws InvalidConfigException
     */
    public function actionStatus()
    {
        /** @var FeedbackStatus $dto */
        $dto = Yii::createObject(FeedbackStatus::class);
        $dto->setAttributes(Yii::$app->request->getBodyParams());

        if ($this->service->updateFeedbackStatus($dto)) {
            Yii::$app->response->setStatusCode(200);
            return;
        }

        Yii::$app->response->setStatusCode(400);
    }

    /**
     * @throws InvalidConfigException
     * @throws MarketException
     */
    public function actionUpdateTicketStatus()
    {
        /** @var UpdateTicketStatusForm $form */
        $form = Yii::createObject(UpdateTicketStatusForm::class);
        $form->load(Yii::$app->request->bodyParams, '');

        $this->service->updateTicketStatus($form);
    }

    /**
     * @throws InvalidConfigException
     * @throws MarketException
     */
    public function actionCreateTicket()
    {
        /** @var CreateTicketForm $form */
        $form = Yii::createObject(CreateTicketForm::class);
        $form->load(Yii::$app->request->bodyParams, '');

        $this->service->createTicket($form);
    }
}