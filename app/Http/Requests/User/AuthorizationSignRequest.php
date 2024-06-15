<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class AuthorizationSignRequest extends Request
{
  public function authorize(): bool
  {
    return auth('sanctum')->check();
  }

  public function rules(): array
  {
    return [
      'shop_id' => ['required', 'string'],
    ];
  }
}
