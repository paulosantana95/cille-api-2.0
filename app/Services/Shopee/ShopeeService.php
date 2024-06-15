<?php

namespace App\Services\Shopee;

use App\Contracts\ShopeeServiceContract;
use App\Models\User\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\CacheManagement\CacheManagement;

class ShopeeService implements ShopeeServiceContract
{
  public UserRepositoryInterface $userRepository;
  public CacheManagement $cacheManagement;

  public function __construct(UserRepositoryInterface $userRepository, CacheManagement $cacheManagement)
  {
    $this->userRepository = $userRepository;
    $this->cacheManagement = $cacheManagement;
  }

  public static function generateSign($path, ?string $access_token = null, ?int $shop_id = null): array
  {
    $partnerId = env('SHOPEE_PARTNER_ID');
    $partnerKey = env('SHOPEE_PARTNER_KEY');
    $timest = time();

    // TODO: separar o if-else abaixo de forma mais logica usando o else apenas para endpoints publicos (talvez se o sprintf aceitar arrays como argumento...)
    if ($access_token) {
      $baseString = sprintf("%s%s%s%s%s", $partnerId, "/api/v2/$path", $timest, $access_token, intval($shop_id));
    } else {
      $baseString = sprintf("%s%s%s", $partnerId, "/api/v2/$path", $timest);
    }
    $sign = hash_hmac('sha256', $baseString, $partnerKey);

    return [
      'sign' => $sign,
      'timestamp' => $timest
    ];
  }

  public function getToken(string $code): void
  {
    /** @var User $user */
    $user = User::query()->findOrFail(auth('sanctum')->user()->id);
    $client = new ShopeeRequestClient();
    $response = json_decode(
      $client->post(
        'auth/token/get',
        null,
        null,
        [
          "partner_id" => (int)env('SHOPEE_PARTNER_ID'),
        ],
        [
          "code" => $code,
          "shop_id" => (int)$user->shopee_id,
          "partner_id" => (int)env('SHOPEE_PARTNER_ID'),
        ]
      )
    );
    $this->userRepository->setUser($user)->setTokens($response->access_token, $response->refresh_token);
  }

  public function refreshToken(): void
  {
    /** @var User $user */
    $user = User::query()->findOrFail(auth('sanctum')->user()->id);
    $client = new ShopeeRequestClient();
    $response = json_decode(
      $client->post(
        'auth/access_token/get',
        null,
        null,
        [
          "partner_id" => (int)env('SHOPEE_PARTNER_ID'),
        ],
        [
          "refresh_token" => $user->refresh_token,
          "shop_id" => (int)$user->shopee_id,
          "partner_id" => (int)env('SHOPEE_PARTNER_ID'),
        ]
      )
    );
    $this->userRepository->setUser($user)->setTokens($response->access_token, $response->refresh_token);
  }

  public function authorizationSign(string $shop_id): array
  {
    // $partnerId = 1096339;
    // $partnerKey = "7a52486
    // Development - (Cille - urbanzonejeans@gmail.com)
    // $partnerId = 1096339;
    // $partnerKey = "7a52486d75445779465a766f734f4c7378654c656b754f6e6256686850686a6a";
    // $host="https://partner.test-stable.shopeemobile.com";

    // Production - (Cille - urbanzonejeans@gmail.com)
    $partnerId = env('SHOPEE_PARTNER_ID');
    $partnerKey = env('SHOPEE_PARTNER_KEY');
    $host = env('SHOPEE_BASE_URL');

    $path = "/api/v2/shop/auth_partner";
    // $redirectUrl = $request->header('Origin')."/dashboard";
    $redirectUrl = "https://app.cille.io/dashboard";

    $timest = time();
    $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
    $sign = hash_hmac('sha256', $baseString, $partnerKey);
    $url = sprintf(
      "%s%s?partner_id=%s&timestamp=%s&sign=%s&redirect=%s",
      $host,
      $path,
      $partnerId,
      $timest,
      $sign,
      $redirectUrl
    );

    return [
      'url' => $url,
      'sigh' => $sign,
      'timest' => $timest
    ];
  }
}
