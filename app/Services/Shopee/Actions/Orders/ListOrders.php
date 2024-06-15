<?php

namespace App\Services\Shopee\Actions\Orders;

use App\Http\Requests\Request;
use App\Services\Shopee\ShopeeRequestClient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ListOrders
{
  public static function listIds(Request $request): object
  {
    $client = new ShopeeRequestClient();
    $response = $client->get(
      'order/get_order_list',
      auth('sanctum')->user()->access_token,
      auth('sanctum')->user()->shopee_id,
      [
        "partner_id" => (int)env('SHOPEE_PARTNER_ID'),
        'time_range_field' => $request->time_range_field,
        'time_from' => $request->time_from,
        'time_to' => $request->time_to,
        'page_size' => $request->page_size,
        'cursor' => $request->cursor,
      ]
    );

    return json_decode($response->body())->response;
  }

  public static function execute(Request $request): Collection
  {
    //    TODO: change orders and graphic type to Collection
    $orders = [];
    $graphic = [];

    $data = self::listIds($request);
    $more = $data->more;
    $next_cursor = $data->next_cursor;

    foreach ($data->order_list as $order) {
      $detail = DetailOrder::execute(
        $order->order_sn,
        'buyer_user_id,buyer_username,estimated_shipping_fee,recipient_address,actual_shipping_fee,goods_to_declare,note,note_update_time,item_list,pay_time,dropshipper,dropshipper_phone,split_up,buyer_cancel_reason,cancel_by,cancel_reason,actual_shipping_fee_confirmed,buyer_cpf_id,fulfillment_flag,pickup_done_time,package_list,shipping_carrier,payment_method,total_amount,buyer_username,invoice_data,no_plastic_packing,order_chargeable_weight_gram,edt,return_due_date'
      );
      array_push($orders, [
        'id' => $order->order_sn,
        'buyer_username' => $detail->buyer_username ?? null,
        'total_amount' => $detail->total_amount ?? null,
        'item_list' => $detail->item_list ?? null,
        'order_status' => $detail->order_status ?? null,
        'buyer_user_id' => $detail->buyer_user_id ?? null,
        'payment_method' => $detail->payment_method ?? null,
        'create_time' => $detail->create_time ?? null,
        'logistic' => $detail->package_list[0]->logistics_status ?? null
      ]);
      $create_time = $detail->create_time;
      $create_time_column = array_column($graphic, 'create_time');
      if (in_array($create_time, $create_time_column)) {
        $index = array_search($create_time, $create_time_column);
        $graphic[$index]['count']++;
      } else {
        array_push($graphic, [
          'create_time' => $create_time,
          'date' => Carbon::createFromTimestamp($detail->create_time)->format('d/m/Y'),
          'count' => 1
        ]);
      }
    }

    return collect([
      'more' => $more,
      'next_cursor' => $next_cursor,
      'orders' => $orders,
      'graphic' => [
        'days' => $graphic,
        'months' => [],
        'years' => []
      ]
    ]);
  }
}
