<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CreateUser;
use App\Dto\User\CreateUserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\ProfileResource;

/**
 * Class RegistrationController
 * @package App\Http\Controllers\Api
 */
class RegistrationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  RegistrationRequest  $request
     * @param  CreateUser  $creator
     * @return ProfileResource
     */
    public function __invoke(RegistrationRequest $request, CreateUser $creator): ProfileResource
    {
        $user = $creator->create(new CreateUserDto($request->validated()));

        return new ProfileResource($user);
    }
}
