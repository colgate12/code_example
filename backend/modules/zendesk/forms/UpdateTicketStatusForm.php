<?php
namespace restapp\modules\zendesk\forms;

use yii\base\Model;
use restapp\modules\zendesk\models\Feedback;

/**
 * Class UpdateTicketStatusForm
 *
 * @package restapp\modules\zendesk\forms
 */
class UpdateTicketStatusForm extends Model
{
    public $zendesk_ticket_id;
    public $market_id;
    public $status;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['zendesk_ticket_id', 'market_id', 'status'], 'required'],
            [['status'], 'string', 'max' => 255],
            [['zendesk_ticket_id', 'market_id'], 'integer'],
            [
                ['zendesk_ticket_id', 'market_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Feedback::class, 'targetAttribute' => ['zendesk_ticket_id' => 'ticket_id', 'market_id' => 'market_id']
            ],
            [
                'status',
                'in',
                'range' => [
                    Feedback::STATUS_OPEN,
                    Feedback::STATUS_WAITING,
                    Feedback::STATUS_ON_HOLD,
                    Feedback::STATUS_DONE,
                    Feedback::STATUS_CLOSED,
                ]
            ],
        ];
    }
}