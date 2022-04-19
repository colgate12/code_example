<?php
namespace restapp\modules\zendesk\forms;

use yii\base\Model;
use restapp\modules\zendesk\models\Feedback;

/**
 * Class SaveCommentForm
 *
 * @package restapp\modules\zendesk\forms
 */
class SaveCommentForm extends Model
{
    public $thread_id;
    public $request_unique_identifier;
    public $message;
    public $file_urls;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['thread_id', 'request_unique_identifier', 'message'], 'required'],
            ['file_urls', 'each', 'rule' => ['url']],
            [['thread_id'], 'integer'],
            [
                ['thread_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Feedback::class, 'targetAttribute' => ['thread_id' => 'id']
            ],
        ];
    }
}