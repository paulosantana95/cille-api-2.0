<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DefaultAdminRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Http\Requests\User\SaveCodeRequest;
use App\Http\Requests\User\UpdateAvatarRequest;
use App\Http\Resources\UserResource;
use App\Models\User\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Storage\User\UserFileInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class UserController extends Controller
{

  protected UserFileInterface $fileManager;
  protected UserRepositoryInterface $repository;

  public function __construct(UserFileInterface $fileManager, UserRepositoryInterface $repository)
  {
    $this->fileManager = $fileManager;
    $this->repository = $repository;
  }

  /**
   * @return JsonResponse
   */
  public function me(): JsonResponse
  {
    $user = auth('sanctum')->user();
    return $this->respondWithSuccess(UserResource::make($user));
  }

  /**
   * @param DefaultAdminRequest $request
   * @param int|null $id
   * @return JsonResponse
   */
  public function getUsers(DefaultAdminRequest $request, ?int $id = null): JsonResponse
  {
    return $this->respondWithSuccess(UserResource::collection($this->repository->list($request, $id)));
  }


  /**
   * @param EditUserRequest $request
   * @param int $id
   * @return JsonResponse
   */
  public function editUser(EditUserRequest $request, int $id): JsonResponse
  {
    $user = User::query()->findOrFail($id);
    $user->update($request->validated());
    return $this->respondOk('Success');
  }

  public function updateAvatar(UpdateAvatarRequest $request, int $id): JsonResponse
  {
    /** @var User $user */
    $user = User::query()->findOrFail($id);
    $this->fileManager->changeAvatar($request->file('avatar'), $user);
    return $this->respondCreated();
  }

  public function getAvatar(int $id): Response
  {
    /** @var User $user */
    $user = User::query()->findOrFail($id);
    return $this->fileManager->getAvatar($user);
  }
}
