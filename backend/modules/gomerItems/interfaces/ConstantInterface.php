<?php

namespace restapp\modules\gomerItems\interfaces;

/**
 * Interface ModerationRequestConstantInterface
 * @package restapp\modules\moderationRequest\models
 */
interface ConstantInterface
{
    public const CACHE_TOKEN_DURATION = 60 * 60;
    public const DEFAULT_LANGUAGE = 'uk';
    public const DEFAULT_TOKEN_DURATION = 1;
    public const MODERATION_REQUEST_CACHE_KEY_PREFIX = 'SOURCE_PREFIX_';
    public const SOURCE_LIST_DEFAULT_PER_PAGE = 100;
    public const DEFAULT_PER_GE = 20;

    //validation
    public const ZERO_VALUE = 0;
    public const DEFAULT_PER_PAGE = 20;
    public const MIN_INT = 1;
    public const MAX_INT = 2147483647;

    //statuses
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_BLOCKED = 2;

    //filter Scenarios
    public const SOURCE_LIST_FILTER_SCENARIO = 'source_list_filter_scenario';
    public const GET_LIST_STATUS_FILTER_VALUE = [
        self::STATUS_ACTIVE,
        self::STATUS_BLOCKED
    ];

    public const DEFAULT_SCENARIO = 'default';
    public const BIND_CATEGORY_SCENARIO = 'bind_category_scenario';
    public const UNLINK_CATEGORY_SCENARIO = 'unlink_category_scenario';

    //availability
    public const AVAILABILITY_IN_PRICE = 1; //Наличие в прайсе
    public const AVAILABILITY = 2; //По наличию
    public const AVAILABILITY_LEFTOVERS = 3; //По остаткам
    public const AVAILABILITY_API = 4; //По API
    public const AVAILABILITY_REST = 3;

    public const AVAILABILITY_MAP = [
        self::AVAILABILITY_IN_PRICE => self::AVAILABILITY_REST,
        self::AVAILABILITY => self::AVAILABILITY_REST,
        self::AVAILABILITY_LEFTOVERS => self::AVAILABILITY_REST
    ];

    //tabs
    public const ITEMS_ON_SALE_TAB = 'items_on_sale_tab';
    public const ITEMS_NOT_ON_SALE_TAB = 'items_not_on_sale_tab';
    public const ITEMS_HIDDEN = 'items_hidden';
    public const ITEMS_ARCHIVE = 'items_archive';
    public const ITEMS_NEW = 'items_new';
    public const ITEMS_ALL = 'items_all_tab';
    public const ITEMS_ON_MODERATION = 'items_on_moderation';
    public const ITEMS_ON_QUARANTINE = 'items_quarantine';

    //available
    public const IN_STOCK = 1;
    public const NOT_AVAILABLE = 0;
    public const REMOVED = 2;

    //upload statuses
    public const UPLOAD_STATUS_ACTIVE = 2;
    public const UPLOAD_STATUS_HIDDEN = 11;
    public const UPLOAD_STATUS_NOT_PASSED_MODERATION = 9;
    public const UPLOAD_STATUS_ARCHIVE = 15;
    public const UPLOAD_STATUS_NEW = 0;
    public const UPLOAD_STATUS_MODERATION = 1;
    public const UPLOAD_STATUS_ERRORS = 5;
    public const UPLOAD_STATUS_READY = 8;
    public const UPLOAD_STATUS_WAIT_SELLER_VERIFICATION = 10;
    public const UPLOAD_STATUS_WAIT_FOR_DOWNLOADING_PHOTOS = 12;
    public const UPLOAD_STATUS_WAIT_FOR_GROUPING = 13;
    public const UPLOAD_STATUS_CONFIRMATION_REQUIRING = 14;

    public const ACCESS_TOKEN_PARAM_NAME = 'access-token';
}