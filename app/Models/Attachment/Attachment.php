<?php

namespace App\Models\Attachment;

use App\Enums\AttachmentTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
  protected $fillable = [
    'attachable_type',
    'attachable_id',
    'description',
    'extension',
    'name',
    'original_name',
    'path',
    'type_id',
  ];

  protected function casts(): array
  {
    return [
      'type_id' => AttachmentTypeEnum::class
    ];
  }

}
