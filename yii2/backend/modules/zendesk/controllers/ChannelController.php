<?php

namespace restapp\modules\zendesk\controllers;

use restapp\modules\zendesk\behaviors\ZendeskQueryParamsAuth;
use restapp\modules\zendesk\forms\AdminUiForm;
use restapp\modules\zendesk\forms\SaveCommentForm;
use restapp\modules\zendesk\models\ZendeskVirtualUser;
use restapp\modules\zendesk\providers\ManifestProvider;
use restapp\modules\zendesk\services\ChannelService;
use restapp\modules\zendesk\services\FeedbackService;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\helpers\FileHelper;
use yii\web\Response;

/**
 * Class ChannelController
 * @package restapp\modules\zendesk\controllers
 */
class ChannelController extends BaseZendeskController
{
    public $modelClass = false;

    /**
     * @var ChannelService
     */
    protected $service;

    /**
     * @param string $id
     * @param Module $module
     * @param ChannelService $service
     * @param array $config
     */
    public function __construct(
        string $id,
        Module $module,
        ChannelService $service,
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
     * @return array[]
     * @throws \restapp\components\MarketException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPull(): array
    {
        /** @var FeedbackService $service */
        $service = Yii::createObject(FeedbackService::class);
        return $service->getFeedbacksWithComments();
    }

    /**
     * @param string $file
     * @param string $directory
     * @return null
     */
    public function actionGetStorageFile(string $file, string $directory = 'feedback')
    {
        $filePath = Yii::$app->storage->getTmpFile($directory . '/' . $file);

        if ($filePath) {
            Yii::$app->response->sendFile($filePath, $file);
            FileHelper::unlink($filePath);
        }

        return null;
    }

    /**
     * @return string|void
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAdminUi()
    {
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_HTML;

        /** @var AdminUiForm $form */
        $form = Yii::createObject(AdminUiForm::class);
        $form->load(Yii::$app->request->bodyParams, '');

        if ($form->validate()) {
            return $this->render('admin_ui', [
                'model' => $form
            ]);
        }
    }

    /**
     * @return array
     * @throws \restapp\components\MarketException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionChannelback(): array
    {
        /** @var SaveCommentForm $form */
        $form = Yii::createObject(SaveCommentForm::class);
        $form->load(Yii::$app->request->bodyParams, '');

        return $this->service->saveComment($form);
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCallback()
    {
        $this->service->parseCallback(Yii::$app->request->bodyParams);
    }

    /**
     * @return array
     */
    public function actionGetManifest(): array
    {
        return ManifestProvider::getParams();
    }
}
