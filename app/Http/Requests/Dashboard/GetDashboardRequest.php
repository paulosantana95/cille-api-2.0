<?php

namespace App\Http\Requests\Dashboard;

use App\Http\Requests\Request;

class GetDashboardRequest extends Request
{
  public function authorize(): bool
  {
    return auth('sanctum')->check();
  }

  public function rules(): array
  {
    return [
      'time_range_field' => ['required', 'string'],
      'time_from' => ['required', 'string'],
      'time_to' => ['required', 'string'],
      'page_size' => ['required', 'string']
    ];
  }

  protected function prepareForValidation()
  {
    $this->merge([
      'cursor' => ''
    ]);
  }
}
