<?php

namespace restapp\modules\zendesk;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

/**
 * Class ZendeskModule
 * @package restapp\modules\zendesk
 */
class ZendeskModule extends Module implements BootstrapInterface
{
    public $controllerNamespace = 'restapp\modules\zendesk\controllers';

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