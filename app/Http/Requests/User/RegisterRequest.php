<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class RegisterRequest extends Request
{

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => ['required', 'string'],
      'email' => ['required', 'email', 'unique:users'],
      'cellphone' => ['required'],
      'company' => ['required'],
      'shopee_id' => ['required', 'string'],
      'cnpj' => ['required', 'string'],
      'birth_date' => ['required', 'date'],
      'password' => ['required', 'confirmed'],
    ];
  }
}
