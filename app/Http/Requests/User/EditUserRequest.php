<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class EditUserRequest extends Request
{

  public function authorize(): bool
  {
    return belongsToRole('admin') || isMe($this->route('id'));
  }

  public function rules(): array
  {
    return [
      'name' => ['string'],
      'email' => ['email'],
      'cellphone' => ['string'],
      'company' => ['string'],
      'shopee_id' => ['string'],
      'status' => ['string'],
      'cnpj' => ['string'],
      'birth_date' => ['date'],
    ];
  }
}
