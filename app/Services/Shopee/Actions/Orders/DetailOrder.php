<?php

namespace App\Services\Shopee\Actions\Orders;

use App\Services\Shopee\ShopeeRequestClient;

class DetailOrder
{
  public static function execute($orderId, string $fields = null): mixed
  {
    $client = new ShopeeRequestClient();
    $response = $client->get(
      'order/get_order_detail',
      auth('sanctum')->user()->access_token,
      auth('sanctum')->user()->shopee_id,
      [
        "partner_id" => (int)env('SHOPEE_PARTNER_ID'),
        'order_sn_list' => $orderId,
        'response_optional_fields' => $fields
      ]
    );
    $order = collect(json_decode($response->body())->response->order_list)->first();

    if (!$order) {
      throw new \Exception('Fail in order detailment.', 500);
    }
    return $order;
  }
}
