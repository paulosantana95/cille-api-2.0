<?php

namespace App\Services\Shopee\Actions\Payments;

use App\Services\Shopee\ShopeeRequestClient;
use Illuminate\Http\JsonResponse;

class DetailEscrow
{
  public static function execute($orderId): object
  {
    $client = new ShopeeRequestClient();
    $response = $client->get(
      'payment/get_escrow_detail',
      auth('sanctum')->user()->access_token,
      auth('sanctum')->user()->shopee_id,
      [
        "partner_id" => (int)env('SHOPEE_PARTNER_ID'),
        'order_sn' => $orderId,
      ]
    );
    return json_decode($response->body())->response->order_income;
  }
}
