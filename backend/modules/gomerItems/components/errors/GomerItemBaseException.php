<?php

namespace restapp\modules\gomerItems\components\errors;

use restapp\components\MarketException;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class GomerItemException
 * @package restapp\modules\gomerItems\components\errors
 */
class GomerItemBaseException extends MarketException
{
    /**
     * BaseException constructor.
     * @param int $code
     * @param null $message
     */
    public function __construct($code = 0, $message = null)
    {
        $this->user = Yii::$app->user->id ?? '';
        $this->details = $message ? ArrayHelper::toArray($message) : [];
        $this->message = $this->prepareMessage($code);
        $this->code = $code;
    }

    /**
     * @param $message
     */
    public function setMessage($message): void
    {
        $this->message = ArrayHelper::toArray($message);
    }

    /**
     * @param $code
     * @return string
     */
    private function prepareMessage($code): string
    {
        if (!isset(self::$codeMessages[$code])) {
            return 'Error message not set for code: ' . $code;
        }

        return self::$codeMessages[$code];
    }
}