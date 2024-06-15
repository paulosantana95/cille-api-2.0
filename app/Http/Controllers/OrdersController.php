<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\GetOrdersRequest;
use App\Services\Shopee\Actions\Orders\ListOrders;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class OrdersController extends Controller
{
  public function getOrders(GetOrdersRequest $request): JsonResponse
  {
    try {
      return response()->json(ListOrders::execute($request));
    } catch(\Exception $e) {
      return $this->fail($e);
    }
  }
}
