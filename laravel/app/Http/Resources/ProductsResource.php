<?php

namespace App\Http\Resources;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductsResource
 *
 * @OA\Schema (
 *     schema="ProductsResource",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="category_id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="image", type="string"),
 *     @OA\Property(property="quality", type="string"),
 *     @OA\Property(property="price", type="string"),
 * )
 *
 * @property Products $resource
 * @package App\Http\Resources
 */
class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'category_id' => $this->resource->category_id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'image' => $this->resource->getFirstMediaUrl('image'),
            'quality' => $this->resource->quality,
            'price' => $this->resource->price,
        ];
    }
}
