<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 *
 * @OA\Info(
 *     version="1.0.0",
 *     title="Buyer China"
 * )
 *
 * @OA\Server(
 *     description="Описание",
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Demo API server"
 * )
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
