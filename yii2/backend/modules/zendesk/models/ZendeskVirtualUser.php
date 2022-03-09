<?php

namespace restapp\modules\zendesk\models;

use yii\web\IdentityInterface;

/**
 * Class ZenDeskVirtualUser
 * @package restapp\modules\zendesk\models
 */
final class ZendeskVirtualUser implements IdentityInterface
{
    /**
     * @param $token
     * @return $this|null
     */
    public function loginByAccessToken($token): ?ZenDeskVirtualUser
    {
        return $this->login($token) ? $this : null;
    }

    /**
     * @param string|null $token
     * @return bool
     */
    private function login(?string $token): bool
    {
        return $token == getenv('ZENDESK_AUTH_TOKEN');
    }

    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}