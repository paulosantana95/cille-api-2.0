<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class DeleteUserRequest extends Request
{

  public function authorize(): bool
  {
    return belongsToRole('admin');
  }

  public function rules(): array
  {
    return [];
  }
}
