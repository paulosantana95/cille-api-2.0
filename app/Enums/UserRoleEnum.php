<?php

namespace App\Enums;

enum UserRoleEnum: int
{
  case ADMIN = 1;
  case SELLER = 2;

  public static function fromValue($role_id): string
  {
    return match ($role_id) {
      self::ADMIN => 'admin',
      self::SELLER => 'seller',
    };
  }
}
