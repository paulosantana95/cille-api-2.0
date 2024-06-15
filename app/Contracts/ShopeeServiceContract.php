<?php

namespace App\Contracts;

interface ShopeeServiceContract
{
  public function refreshToken(): void;

  public function getToken(string $code): void;

  public static function generateSign($path, ?string $access_token = null, ?int $shop_id = null): array;

  public function authorizationSign(string $shop_id): array;
}
