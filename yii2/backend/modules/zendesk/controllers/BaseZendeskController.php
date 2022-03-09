<?php

namespace restapp\modules\zendesk\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;
use yii\rest\ActiveController;
use yii\web\Response;

class BaseZendeskController extends ActiveController
{
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response = $event->sender;
            if (!$response->isSuccessful) {
                $response->statusCode = 400;
            }
            $this->saveLog($response);
        });
    }

    /**
     * @param $response
     * @throws InvalidConfigException
     */
    public function saveLog($response)
    {
        $headers = Yii::$app->request->getHeaders()->toArray();
        $requestBody = VarDumper::dumpAsString(Yii::$app->request->getBodyParams());
        $responseData = VarDumper::dumpAsString($response->data);

        Yii::info([
            'origin' => Yii::$app->request->getOrigin(),
            'headers' => $headers,
            'requestMethod' => Yii::$app->request->getMethod(),
            'request' => Yii::$app->request->getUrl(),
            'requestBody' => $requestBody,
            'responseCode' => $response->statusCode,
            'response' => $responseData,
        ], 'zendesk_log');
    }

}