<?php

namespace App\Services\Storage\User;

use App\Models\Attachment\Attachment;
use App\Models\User\User;
use Illuminate\Http\UploadedFile;

interface UserFileInterface
{
  public function uploadAvatar(?UploadedFile $file, User $user): ?Attachment;

  public function changeAvatar(?UploadedFile $file, User $user): ?Attachment;

  public function getAvatar(User $user, int $width = null, int $height = null);
}
