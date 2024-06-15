<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateAvatarRequest extends Request
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return isMe($this->route('id'));
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      'avatar' => [
        'required',
        'image',
        'mimes:jpeg,jpg,png',
        'max:1024',
      ]
    ];
  }
}
