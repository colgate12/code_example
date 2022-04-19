<?php
namespace restapp\modules\zendesk\forms;

use yii\base\Model;

/**
 * Class AdminUiForm
 *
 * @package restapp\modules\zendesk\forms
 */
class AdminUiForm extends Model
{
    public $name;
    public $metadata;
    public $state;
    public $return_url;
    public $instance_push_id;
    public $zendesk_access_token;
    public $subdomain;
    public $locale;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['return_url', 'instance_push_id', 'zendesk_access_token'], 'required'],
            ['metadata', 'string'],
            [
                [
                    'name',
                    'state',
                    'return_url',
                    'instance_push_id',
                    'zendesk_access_token',
                    'subdomain',
                    'locale'
                ], 'string', 'max' => 500],
        ];
    }
}