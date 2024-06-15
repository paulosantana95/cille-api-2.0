<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class SaveCodeRequest extends Request
{

  public function authorize(): bool
  {
    return auth('sanctum')->check();
  }

  public function rules(): array
  {
    return [
      'code' => ['required', 'string'],
    ];
  }
}
