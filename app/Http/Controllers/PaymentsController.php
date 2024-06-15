<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payments\GetPaymentsRequest;
use App\Services\Shopee\Actions\Payments\ListPayments;
use Illuminate\Http\JsonResponse;

class PaymentsController extends Controller
{

  public function getPayments(GetPaymentsRequest $request): JsonResponse
  {
    return response()->json(ListPayments::execute($request));
  }
}
