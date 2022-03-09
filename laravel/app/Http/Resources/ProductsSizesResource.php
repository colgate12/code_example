<?php

namespace App\Http\Resources;

use App\Models\ProductsSizes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductsSizesResource
 *
 * @OA\Schema (
 *     schema="ProductsSizesResource",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 * )
 *
 * @property ProductsSizes $resource
 * @package App\Http\Resources
 */
class ProductsSizesResource extends JsonResource
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
        ];
    }
}
