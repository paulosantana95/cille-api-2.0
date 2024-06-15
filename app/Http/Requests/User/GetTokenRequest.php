<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class GetTokenRequest extends Request
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
