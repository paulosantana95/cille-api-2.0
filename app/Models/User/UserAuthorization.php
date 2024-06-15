<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserAuthorization extends Model
{
  protected $fillable = [
    'name',
    'expires_at',
    'token',
    'authorizable_type',
    'authorizable_id',
  ];

  public function authorizable(): MorphTo
  {
    return $this->morphTo();
  }
}
