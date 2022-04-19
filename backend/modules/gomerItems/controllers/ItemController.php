<?php

namespace restapp\modules\gomerItems\controllers;

use yii\rest\ActiveController;
use restapp\helpers\ArrayHelper;
use restapp\modules\gomerItems\components\errors\GomerItemBaseException;
use restapp\modules\gomerItems\services\ItemService;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;

/**
 * @property ItemService $_service
 */
class ItemController extends ActiveController
{
    public $modelClass = false;
    /**
     * @var ItemService
     */
    private $_service;

    public function __construct($id, Module $module, ItemService $service, $config = [])
    {
        $this->_service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'details'
                        ],
                        'allow' => true,
                        'permissions' => ['items_api']
                    ],
                    [
                        'actions' => [
                            'options'
                        ],
                        'allow' => true
                    ]
                ],
                'denyCallback' => function () {
                    throw new GomerItemBaseException(GomerItemBaseException::ERROR_ACCESS_DENIED);
                }
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions['options'] = ['class' => 'yii\rest\OptionsAction'];

        return $actions;
    }

    /**
     * @return array|null
     * @throws InvalidConfigException
     */
    public function actionDetails()
    {
        try {
            $result = $this->_service->getDetails(Yii::$app->request->get());
            return empty($result) ? null : $result;
        } catch (InvalidConfigException $e) {
            throw new GomerItemBaseException(GomerItemBaseException::ERROR_SERVER);
        }
    }
}