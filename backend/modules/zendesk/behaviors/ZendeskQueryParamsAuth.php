<?php

namespace restapp\modules\zendesk\behaviors;

use restapp\modules\zendesk\models\ZendeskVirtualUser;
use yii\filters\auth\QueryParamAuth;
use yii\web\IdentityInterface;
use yii\web\Request;
use yii\web\Response;

/**
 * Class ZendeskQueryParamsAuth
 * @package restapp\modules\zendesk\behaviors
 */
final class ZendeskQueryParamsAuth extends QueryParamAuth
{
    /**
     * @param ZendeskVirtualUser $user
     * @param Request $request
     * @param Response $response
     * @return IdentityInterface|null
     */
    public function authenticate($user, $request, $response): ?IdentityInterface
    {
        $accessToken = $request->get($this->tokenParam);

        return $user->loginByAccessToken($accessToken);
    }
}