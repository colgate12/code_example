<?php

namespace restapp\modules\zendesk\models;

use restapp\models\Seller;

/**
 * Class Author
 * @package restapp\modules\zendesk\models
 */
final class Author extends Seller
{
    /**
     * @return string[]
     */
    public function fields(): array
    {
        return [
            'external_id' => function() {
                return (string) $this->id;
            },
            'name' => 'fio',
            'locale' => 'lang',
//            'image_url' => ''
        ];
    }
}