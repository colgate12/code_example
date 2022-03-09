<?php

namespace App\Http\Resources;

use App\Models\ProductsCategories;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductsCategoriesResource
 *
 * @OA\Schema (
 *     schema="ProductsCategoriesResource",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 * )
 *
 * @property ProductsCategories $resource
 * @package App\Http\Resources
 */
class ProductsCategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
        ];
    }
}
