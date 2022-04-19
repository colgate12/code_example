<?php

namespace restapp\modules\zendesk;

/**
 * Interface ConstantInterface
 * @package restapp\modules\zendesk
 */
interface ConstantInterface
{
    public const ZENDESK_FEEDBACKS_LIMIT = 100;
    public const ZENDESK_FEEDBACK_COMMENTS_LIMIT = 100;

    public const _NOT_SENT_TO_ZENDESK = 0;
    public const _SENT_TO_ZENDESK = 1;
    public const _OLD_TICKET_NOT_SENT_TO_ZENDESK = 2;

    public const MANIFEST_PARAM_NAME = 'MkRest';
    public const MANIFEST_PARAM_ID = 'rz-mk-rest-integration';
    public const MANIFEST_PARAM_VERSION = 'v1.0.0';
    public const MANIFEST_PARAM_AUTHOR = 'Rozetka';
    public const MANIFEST_PARAM_CLIENT_ID = 'mk-rest';
}