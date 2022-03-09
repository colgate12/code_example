<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsSearchRequest;
use App\Http\Resources\ProductsResource;
use App\Services\ProductsService;

class ProductsController extends Controller
{
    /**
     * @var ProductsService
     */
    private $service;

    /**
     * ProductsController constructor.
     *
     * @param ProductsService $service
     */
    public function __construct(ProductsService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Поиск товаров",
     *     description="Поиск товаров",
     *     operationId="productsSearch",
     *     tags={"products"},
     *     @OA\Parameter(
     *         name="order_by",
     *         description="Типы сортировок: date_asc,date_desc,price_asc,price_desc",
     *         in="query",
     *         example="date_asc"
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         description="ID категории",
     *         in="query",
     *         example="1"
     *     ),
     *     @OA\Parameter(
     *         name="quality",
     *         description="Качество: 0(среднее) или 1(высокое)",
     *         in="query",
     *         example="1"
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         description="offset",
     *         in="query",
     *         example="0"
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         description="limit",
     *         in="query",
     *         example="100"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(ref="#/components/schemas/ProductsResource")
     *     )
     * )
     *
     * @param ProductsSearchRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(ProductsSearchRequest $request)
    {
        return $this->service->search($request);
    }


    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Просмотр товара",
     *     description="Просмотр товара",
     *     operationId="productsView",
     *     tags={"products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID товара"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(ref="#/components/schemas/ProductsViewResource")
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not found"
     *     ),
     * )
     *
     * @param int $id
     * @return \App\Http\Resources\ProductsViewResource
     */
    public function view(int $id)
    {
        return $this->service->getById($id);
    }
}
