<?php

namespace App\Services\Shopee\Actions\Payments;

use App\Http\Requests\Request;
use App\Services\Shopee\ShopeeRequestClient;
use Illuminate\Support\Collection;
use App\Services\Shopee\Actions\Orders\DetailOrder;

class ListPayments
{
  public static function execute(Request $request): Collection
  {
    //    TODO: change payments type to Collection
    $payments = [];
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

    $data = json_decode($response);
    $more = $data->response->more;
    $next_cursor = $data->response->next_cursor;
    foreach ($data->response->order_list as $order) {
      $orderDetail = DetailOrder::execute(
        $order->order_sn,
        'buyer_user_id,buyer_username,estimated_shipping_fee,recipient_address,actual_shipping_fee,goods_to_declare,note,note_update_time,item_list,pay_time,dropshipper,dropshipper_phone,split_up,buyer_cancel_reason,cancel_by,cancel_reason,actual_shipping_fee_confirmed,buyer_cpf_id,fulfillment_flag,pickup_done_time,package_list,shipping_carrier,payment_method,total_amount,buyer_username,invoice_data,no_plastic_packing,order_chargeable_weight_gram,edt,return_due_date'
      );
      $paymentOrderDetail = DetailEscrow::execute($order->order_sn);
      array_push($payments, [
        'id' => $order->order_sn,
        'escrow_amount' => $paymentOrderDetail->escrow_amount,
        'commission_fee' => $paymentOrderDetail->commission_fee,
        'service_fee' => $paymentOrderDetail->service_fee,
        'buyer_username' => $orderDetail->buyer_username,
        'total_amount' => $orderDetail->total_amount,
        'order_status' => $orderDetail->order_status,
        'buyer_user_id' => $orderDetail->buyer_user_id,
        'payment_method' => $orderDetail->payment_method,
        'create_time' => $orderDetail->create_time
      ]);
    }

    return collect([
      'more' => $more,
      'next_cursor' => $next_cursor,
      'data' => $payments
    ]);
  }
}
