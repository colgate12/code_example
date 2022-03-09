<?php
namespace restapp\modules\zendesk\forms;

use marketplace\common\models\FeedbackTheme;
use restapp\models\Seller;
use yii\base\Model;
use restapp\modules\zendesk\models\Feedback;

/**
 * Class UpdateTicketStatusForm
 *
 * @package restapp\modules\zendesk\forms
 */
class CreateTicketForm extends Model
{
    protected const REGEX_FIND_URL_FILES = "/https:\/\/[0-9a-zA-Z.\-]{1,}\/attachments\/token\/[0-9a-zA-Z]{1,}\/\?name=/u";
    protected const REGEX_FIND_EXTENSION_FILES = "/\.[a-zA-Z]{3,4}\s-\s/u";

    public $zendesk_ticket_id;
    public $status;
    public $description;
    public $theme_id;
    public $email;
    public $files;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['zendesk_ticket_id', 'status', 'email'], 'required'],
            [['status', 'theme_id'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['files'], 'safe'],
            [['zendesk_ticket_id'], 'integer'],
            [
                ['theme_id'], 'exist', 'skipOnError' => true,
                'targetClass' => FeedbackTheme::class, 'targetAttribute' => ['theme_id' => 'zen_desk_key']
            ],
            [
                ['email'], 'exist', 'skipOnError' => true,
                'targetClass' => Seller::class, 'targetAttribute' => ['email' => 'email']
            ],
            ['status', 'compare', 'compareValue' => Feedback::STATUS_NEW],
            [['description'], 'filter', 'filter' => function ($value) {
                return trim(htmlentities(strip_tags($value)));
            },
                'skipOnEmpty' => true,
            ]
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        //парсим ссылки на файлы и расширения файлов отдельно с текста тикета
        preg_match_all(self::REGEX_FIND_URL_FILES, $this->description, $files);
        preg_match_all(self::REGEX_FIND_EXTENSION_FILES, $this->description, $extensions);

        $this->addFilesExtensions($files[0] ?: [], $extensions[0] ?: []);
        return parent::beforeValidate();
    }

    /**
     * @param array $files
     * @param array $extensions
     */
    private function addFilesExtensions(array $files, array $extensions)
    {
        foreach ($files as $key => $file) {
            if (isset($extensions[$key])) {
                $this->files[] = $file . str_replace([' ', '-'],'', $extensions[$key]);
            }
        }
    }
}