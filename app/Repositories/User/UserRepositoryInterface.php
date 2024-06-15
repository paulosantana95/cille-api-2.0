<?php

namespace App\Repositories\User;

use App\Http\Requests\Request;
use App\Models\Attachment\Attachment;
use App\Models\User\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
  public function setTokens(string $accessToken, string $refreshToken): void;

  public function setUser(User $user): self;

  public function updateAvatar(array $fileData, User $user): Model;

  public function list(Request $request, ?int $id): Paginator;
}
