<?php

namespace App\Services\Shopee\Actions\Dashboard;

use App\Http\Requests\Request;
use App\Services\Shopee\Actions\Orders\ListOrders;
use Illuminate\Support\Collection;
use App\Services\Shopee\Actions\Orders\DetailOrder;
use App\Services\Shopee\Actions\Payments\DetailEscrow;

class GetDashboard
{
  public static function execute(Request $request): Collection
  {
//    TODO: change orders type to Collection
    $orders = [];
    $totalCompleted = 0;
    $totalProcessed = 0;
    $totalShipped = 0;
    $paidOrders = 0;
    $unpaidOrders = 0;

    $order_list = ListOrders::listIds($request)->order_list;

    foreach ($order_list as $order) {
      $detail = DetailOrder::execute(
        $order->order_sn,
        'buyer_user_id,buyer_username,estimated_shipping_fee,recipient_address,actual_shipping_fee,goods_to_declare,note,note_update_time,item_list,pay_time,dropshipper,dropshipper_phone,split_up,buyer_cancel_reason,cancel_by,cancel_reason,actual_shipping_fee_confirmed,buyer_cpf_id,fulfillment_flag,pickup_done_time,package_list,shipping_carrier,payment_method,total_amount,buyer_username,invoice_data,no_plastic_packing,order_chargeable_weight_gram,edt,return_due_date'
      );
      $paymentOrderDetail = DetailEscrow::execute($order->order_sn);
      if (count($orders) < 5) {
        array_push($orders, [
          'id' => $order->order_sn,
          'buyer_username' => $detail->buyer_username,
          'total_amount' => $detail->total_amount,
          'item_list' => $detail->item_list,
          'order_status' => $detail->order_status,
          'buyer_user_id' => $detail->buyer_user_id,
          'payment_method' => $detail->payment_method
        ]);
      }
      if ($detail->order_status == 'PROCESSED') {
        $totalProcessed += $detail->total_amount;
      } else {
        if ($detail->order_status == 'SHIPPED') {
          $totalShipped += $paymentOrderDetail->escrow_amount;
        } else {
          if ($detail->order_status == 'COMPLETED') {
            $totalCompleted += $detail->total_amount;
          }
        }
      }

      if ($detail->order_status == 'COMPLETED') {
        $paidOrders++;
      } else {
        $unpaidOrders++;
      }
    }

    return collect([
      'orders' => $orders,
      'infos' => [
        'total_completed' => $totalCompleted,
        'total_shipped' => $totalShipped,
        'total_processed' => $totalProcessed,
        'sales_received' => [
          'days' => [],
          'months' => [],
          'years' => []
        ],
        'commissions' => [
          'days' => [],
          'months' => [],
          'years' => []
        ],
        'order_paid_unpaid' => [
          'days' => [
            'paid' => $paidOrders,
            'unpaid' => $unpaidOrders,
          ],
          'months' => [],
          'years' => []
        ],
        'revenue_after_last_month' => [
          'actual_total' => 0,
          'percentage' => 0,
          'months' => []
        ]
      ]
    ]);
  }
}
