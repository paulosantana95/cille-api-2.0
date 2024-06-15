<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class GenerateSignRequest extends Request
{

  public function authorize(): bool
  {
    return auth('sanctum')->check();
  }

  public function rules(): array
  {
    return [
      'access_token' => ['required', 'string'],
      'shop_id' => ['required', 'string'],
      'path' => ['required', 'string'],
    ];
  }
}
