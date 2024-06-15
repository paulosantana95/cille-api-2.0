<?php

namespace App\Models\User;

use App\Enums\UserRoleEnum;
use App\Models\Attachment\Attachment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'cellphone',
    'company',
    'shopee_id',
    'role_id',
    'cnpj',
    'status',
    'birth_date',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
      'role_id' => UserRoleEnum::class,
      'birth_date' => 'date'
    ];
  }

  public function setAccessTokenAttribute(string $value): void
  {
    $this->authorizations()->updateOrCreate(
      [
        'name' => 'access_token',
        'authorizable_type' => User::class,
        'authorizable_id' => $this->id,
      ],
      [
        'name' => 'access_token',
        'expires_at' => now()->addMinutes(235),
        'token' => $value
      ]
    );
  }

  public function setPasswordAttribute(string $value): void
  {
    $this->attributes['password'] = bcrypt($value);
  }

  public function setRefreshTokenAttribute(string $value): void
  {
    $this->authorizations()->updateOrCreate(
      [
        'name' => 'refresh_token',
        'authorizable_type' => User::class,
        'authorizable_id' => $this->id,
      ],
      [
        'name' => 'refresh_token',
        'expires_at' => now()->addDays(29)->addHours(23),
        'token' => $value
      ]
    );
  }

  public function getAccessTokenAttribute(): string
  {
    return $this->authorizations->where('name', 'access_token')->first()->token;
  }

  public function getRefreshTokenAttribute(): string
  {
    return $this->authorizations->where('name', 'refresh_token')->first()->token;
  }

  public function authorizations(): MorphMany
  {
    return $this->morphMany(UserAuthorization::class, 'authorizable');
  }

  public function avatar(): MorphOne
  {
    return $this->morphOne(Attachment::class, 'attachable');
  }

  public function recovery_solicitation(): HasOne
  {
    return $this->hasOne(PasswordReset::class, 'email', 'email');
  }

  public function getRoleAttribute(): string
  {
    return UserRoleEnum::fromValue($this->role_id);
  }

  public function getStatusNameAttribute(): string
  {
    return $this->status ? 'active' : 'inactive';
  }

  public function getPasswordRecoveryTokenAttribute(): string
  {
    return Str::random(8);
  }
}
