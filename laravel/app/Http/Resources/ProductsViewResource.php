<?php

namespace App\Http\Resources;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductsViewResource
 *
 *  @OA\Schema (
 *     schema="ProductsViewResource",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="quality", type="string"),
 *     @OA\Property(property="price", type="string"),
 *     @OA\Property(property="image", type="string"),
 *     @OA\Property(
 *         property="images",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ImageMediaResource")
 *     ),
 *     @OA\Property(property="category", type="object", ref="#/components/schemas/ProductsCategoriesResource"),
 *     @OA\Property(
 *         property="sizes",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ProductsSizesResource")
 *     ),
 * )
 *
 * @property Products $resource
 * @package App\Http\Resources
 */
class ProductsViewResource extends JsonResource
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
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'quality' => $this->resource->quality,
            'price' => $this->resource->price,
            'image' => $this->resource->getFirstMediaUrl('image'),
            'images' => ImageMediaResource::collection($this->resource->getMedia('images')),
            'category' => $this->resource->category ? new ProductsCategoriesResource($this->resource->category) : null,
            'sizes' => ProductsSizesResource::collection($this->resource->sizes)
        ];
    }
}
