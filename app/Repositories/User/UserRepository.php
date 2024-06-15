<?php

namespace App\Repositories\User;

use App\Enums\AttachmentTypeEnum;
use App\Http\Requests\Request;
use App\Models\Attachment\Attachment;
use App\Models\User\User;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{

  private User $user;

  public function setTokens(string $accessToken, string $refreshToken): void
  {
    try {
      DB::beginTransaction();
      $this->user->access_token = $accessToken;
      $this->user->refresh_token = $refreshToken;
      $this->user->save();
      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  // TODO: matar e tranferir pra setTokens()
  public function setUser(User $user): self
  {
    $this->user = $user;
    return $this;
  }

  public function updateAvatar(array $fileData, User $user): Model
  {
    return $user->avatar()->create([
      ...$fileData,
      'description' => 'User avatar.',
      'type_id' => AttachmentTypeEnum::AVATAR,
    ]);
  }

  public function list(Request $request, ?int $id): Paginator
  {
    $currentPage = (int)$request->input('current_page', 1);
    $order = $request->input('order', 'ASC');
    $orderBy = $request->input('order_by', 'id');
    $perPage = (int)$request->input('per_page', 20);

    return User::query()
      ->when(
        $id,
        fn(Builder $query) => $query->where('id', $id),
        fn(Builder $query) => $query->where('role_id', 2)
      )
      ->orderBy($orderBy, $order)
      ->simplePaginate($perPage, ['*'], 'page', $currentPage);
  }
}
