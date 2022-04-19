<?php

namespace restapp\modules\gomerItems;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

/**
 * Class ItemModule
 * @package restapp\modules\gomerItems
 */
class GomerItemModule extends Module implements BootstrapInterface
{
    public $controllerNamespace = 'restapp\modules\gomerItems\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $rules = require(__DIR__ . '/url-rules.php');
        Yii::$app->getUrlManager()->addRules($rules);
    }
}