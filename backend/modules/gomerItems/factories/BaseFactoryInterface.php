<?php

namespace restapp\modules\gomerItems\factories;

use restapp\models\forms\BaseFormInterface;

/**
 * Interface BaseFactoryInterface
 * @package restapp\models\factories
 */
interface BaseFactoryInterface
{
    /**
     * @return BaseFormInterface
     */
    public function make(): BaseFormInterface;

    /**
     * @param array $config
     * @param bool $validate
     * @param string $scenario
     * @return BaseFormInterface
     */
    public function getModel(
        array $config = [],
        bool $validate = false,
        string $scenario = 'default'
    ): BaseFormInterface;
}