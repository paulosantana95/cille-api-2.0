<?php

namespace App\Services\Storage;

use App\Models\Attachment\Attachment;
use Exception;
use Illuminate\{Http\Response, Http\UploadedFile};
use Illuminate\Support\{Facades\Storage, Str};
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class StorageBase
{
  /**
   * @return string
   */
  public static function getDisk(): string
  {
    return config('filesystems.default');
  }

  /**
   * @param UploadedFile $file
   * @param string $dir
   * @param string|null $name
   * @param string|null $originalName
   * @return array
   */
  public static function upload(
    UploadedFile $file,
    string $dir,
    string $name = null,
    string $originalName = null,
  ): array {
    $uploadName = $name ?? Str::random(24) . ".{$file->guessExtension()}";
    $disk = self::getDisk();
    $path = Storage::disk($disk)->putFileAs(config("filesystems.disks.$disk.folder") . $dir, $file, $uploadName);

    return [
      'extension' => $file->guessExtension(),
      'name' => $uploadName,
      'original_name' => $originalName ?? $file->getClientOriginalName(),
      'path' => $path,
    ];
  }

  /**
   * @param string $path
   * @return StreamedResponse
   */
  public static function download(string $path): StreamedResponse
  {
    return Storage::disk(self::getDisk())->download($path);
  }

  /**
   * @param $path
   * @return int
   */
  public static function getSize($path): int
  {
    return Storage::disk(self::getDisk())->size($path);
  }

  /**
   * @param string $path
   * @return bool
   */
  public static function exists(string $path): bool
  {
    return Storage::disk(self::getDisk())->exists($path);
  }

  /**
   * @param string $path
   * @return bool
   */
  public static function delete(string $path): bool
  {
    if ($attachment = Attachment::query()->firstWhere('path', $path)) {
      $attachment->delete();
    }
    if (!self::exists($path)) {
      return false;
    }
    return Storage::disk(self::getDisk())->delete($path);
  }

  public static function view(
    Attachment $attachment = null,
    int $width = null,
    int $height = null,
  ) {
    $img = (new ImageManager(new Driver()))->read(self::render($attachment->path));
    return $img->scale($width ?? $img->width(), $height ?? $img->height())
      ->toJpeg()->toDataUri();
  }

  public static function render(string $path): string
  {
    return Storage::disk(self::getDisk())->get($path);
  }
}
