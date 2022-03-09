<?php
declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\ProductsSearchRequest;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\ProductsViewResource;
use App\Models\Products;
use App\Models\ProductsCategories;
use App\Repositories\ProductsRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;

final class ProductsService
{

    private $repository;

    /**
     * ProductsService constructor.
     *
     * @param \App\Repositories\ProductsRepository $repository
     */
    public function __construct(ProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ProductsSearchRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(ProductsSearchRequest $request): AnonymousResourceCollection
    {
        $query = $this->repository->getQueryAll();

        // category
        if (null !== $request->get('category_id')) {
            $category = ProductsCategories::whereId($request->get('category_id'))->first();

            // если у категории есть подкатегории - ищем и в них
            if ($category && count($category->childCategories)) {
                $childCatIds = [(int)$request->get('category_id')];
                foreach ($category->childCategories as $childCategory) {
                    $childCatIds[] = $childCategory->id;
                }
                $query->whereIn('category_id', $childCatIds);
            } else {
                $query->where(['category_id' => $request->get('category_id')]);
            }
        }

        //quality
        if (null !== $request->get('quality')) {
            $query->where(['quality' => $request->get('quality')]);
        }

        // order
        if ($request->get('order_by')) {
            $order_arr = explode('_', $request->get('order_by'));
            $order_elem = '';

            if ($order_arr[0] == 'date') {
                $order_elem = 'created_at';
            } else if ($order_arr[0] == 'price') {
                $order_elem = 'price';
            }

            $query->orderBy($order_elem, $order_arr[1]);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        /*******************************************/
        $total = $query->count();

        $data = $query
            ->limit($request->get('limit'))
            ->offset($request->get('offset'))
            ->get();

        $collection = ProductsResource::collection($data);

        $collection->with = [
            'meta' => [
                'total' => (int)$total,
                'limit' => (int)$request->get('limit', 50),
                'offset' => (int)$request->get('offset', 0),
            ],
        ];

        return $collection;
    }

    /**
     * @param int $id
     * @return ProductsViewResource
     */
    public function getById(int $id): ProductsViewResource
    {
        if (null === $product = $this->repository->getProductById($id)) {
            abort(404);
        }
        return new ProductsViewResource($product);
    }



    /**
     * @param string $attribute
     * @param string $value
     *
     * @return null|string
     */
    public static function slug(string $attribute, string $value): ?string
    {
        if ($value && true === \is_string($value)) {
            $acceptSlug = $slug = Str::slug($value);
            $i          = 0;

            while (Products::where($attribute, $acceptSlug)->withoutGlobalScopes()->exists()) {
                $acceptSlug = $slug . '-' . ++$i;
            }

            return $acceptSlug;
        }

        return null;
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function hiddenProduct($id, Request $request)
    {
        $product = Products::findOrFail($id);
        $product->hidden = $request->hidden == 'on' ? 1 : 0;
        $product->saveOrFail();

        return response()->json([
            'status'  => true,
            'message' => 'Товар успешно убран',
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function uploadsImage($id, Request $request)
    {
        $product = Products::findOrFail($id);
        $image = $request->file('image');
        if($image){
            $filename = sprintf('%s.%s', \Str::random(), $image->getClientOriginalExtension());
            $product->addMedia($image)->usingFileName($filename)->toMediaCollection('image');
        }

        $imageList = $request->file('imageList');
        if($imageList){
            foreach ($imageList as $image) {
                $filename = sprintf('%s.%s', \Str::random(), $image->getClientOriginalExtension());
                $product->addMedia($image)->usingFileName($filename)->toMediaCollection('images');
            }
        }
    }
}
