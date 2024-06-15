<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class ResetPasswordRequest extends Request
{

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'email' => ['required', 'email'],
      'recovery_token' => ['required', 'string'],
      'password' => ['required', 'confirmed'],
    ];
  }
}
