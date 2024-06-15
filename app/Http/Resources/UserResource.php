<?php

namespace App\Http\Resources;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    /** @var User $this */
    return [
      'id' => $this->id,
      'name' => $this->name,
      'email' => $this->email,
      'cellphone' => $this->cellphone,
      'company' => $this->company,
      'role' => $this->role,
      'cnpj' => $this->cnpj,
      'status' => $this->status_name,
      'birth_date' => $this->birth_date->toDateString(),
      'avatar' => route('user.avatar', [
        'id' => $this->id,
        optional(optional($this->avatar)->updated_at)->timestamp,
      ])
    ];
  }
}
