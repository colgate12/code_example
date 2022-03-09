<?php

namespace marketplace\common\components\apiZendesk;

use Throwable;
use Exception;

/**
 * Class ZendeskException
 *
 * @package marketplace\common\components\apiZendesk
 */
class ZendeskException extends Exception
{
    /**
     * ZendeskException constructor.
     * @param array $data
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(array $data = [], int $code = 0, Throwable $previous = null)
    {
        parent::__construct(print_r($data, true), $code, $previous);
    }
}