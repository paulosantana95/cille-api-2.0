<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class RefreshTokenRequest extends Request
{

  public function authorize(): bool
  {
    return auth('sanctum')->check();
  }

  public function rules(): array
  {
    return [];
  }
}
