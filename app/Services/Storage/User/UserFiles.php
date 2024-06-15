<?php

namespace App\Services\Storage\User;

use App\Models\Attachment\Attachment;
use App\Models\User\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Storage\StorageBase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Response;

class UserFiles extends StorageBase implements UserFileInterface
{
  public UserRepositoryInterface $userRepository;

  public function __construct(UserRepositoryInterface $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function uploadAvatar(?UploadedFile $file, User $user): ?Attachment
  {
    if (!$file) {
      return null;
    }
    $fileData = self::upload($file, "/user/$user->id/avatar");
    return $this->userRepository->updateAvatar($fileData, $user);
  }

  public function changeAvatar(?UploadedFile $file, User $user): ?Attachment
  {
    if (!$file) {
      return null;
    }
    if (self::hasFile($user->avatar->path ?? null)) {
      self::delete($user->avatar->path);
    }
    return $this->uploadAvatar($file, $user);
  }

  public function getAvatar(User $user, int $width = null, int $height = null)
  {
    $attachment = $user->avatar;
    $file = self::render($attachment->path);
    $type = $attachment->extension;

    return Response::make($file)->header("Content-Type", "image/$type");
  }

  private static function hasFile(?string $path): bool
  {
    return $path && self::exists($path);
  }
}
