<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\GetDashboardRequest;
use App\Services\Shopee\Actions\Dashboard\GetDashboard;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
  public function getDashboard(GetDashboardRequest $request): JsonResponse
  {
    return response()->json(GetDashboard::execute($request));
  }
}
