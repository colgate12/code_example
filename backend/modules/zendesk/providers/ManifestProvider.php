<?php

namespace restapp\modules\zendesk\providers;

use restapp\modules\zendesk\ConstantInterface;
use Yii;

/**
 * Class ManifestProvider
 * @package restapp\modules\zendesk\providers
 */
class ManifestProvider implements ConstantInterface
{
    /**
     * @return array
     */
    public static function getParams(): array
    {
        $host = Yii::$app->params['zendesk_host'];
        $token = getenv('ZENDESK_AUTH_TOKEN');

        return [
            'name' => self::MANIFEST_PARAM_NAME,
            'id' => self::MANIFEST_PARAM_ID,
            'version' => self::MANIFEST_PARAM_VERSION,
            'author' => self::MANIFEST_PARAM_AUTHOR,
            'push_client_id' => self::MANIFEST_PARAM_CLIENT_ID,
            'channelback_files' => true,
            'create_followup_tickets' => true,
            'urls' => [
                'admin_ui' => "{$host}/zendesk/channel/admin-ui?access-token={$token}",
                'pull_url' => "{$host}/zendesk/channel/pull?access-token={$token}",
                'channelback_url' => "{$host}/zendesk/channel/channelback?access-token={$token}",
                'clickthrough_url' => "",
                'event_callback_url' => "{$host}/zendesk/channel/callback?access-token={$token}",
            ]
        ];
    }
}
