<?php

namespace App\Traits\Rest;

use F9Web\ApiResponseHelpers;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

trait ResponseHelpers
{
  use ApiResponseHelpers;

  public function respondWithSuccess($contents = null, array $header = null): JsonResponse
  {
    if (!$contents) {
      return $this->apiResponse([]);
    }
    if ($contents instanceof ResourceCollection) {
      $contents = $contents->resource;
    }
    if ($contents instanceof Paginator || $contents instanceof Collection) {
      return $this->apiResponse([
        'items' => method_exists($contents, 'items') ? $contents->items() : $contents->toArray(),
        'meta' => [
          'header' => $header,
          'paginate' => [
            'current_page' => method_exists($contents, 'perPage') ? $contents->currentPage() : 1,
            'per_page' => method_exists($contents, 'perPage') ? $contents->perPage() : $contents->count(),
            'totals' => method_exists($contents, 'total') ? $contents->total() : $contents->count(),
            'has_more' => method_exists($contents, 'hasMorePages') ? $contents->hasMorePages() : null,
          ],
        ],
      ]);
    }
    $contents = $this->morphToArray($contents) ?? null;

    $data = true === $contents ? $this->_api_helpers_defaultSuccessData : $contents;

    return $this->apiResponse($data);
  }

  public function fail(?Exception $e = null): JsonResponse
  {
    switch ($e) {
      case !$e:
        return $this->apiResponse(['message' => __('messages.error.unavailable')], 500);
      case $e instanceof ModelNotFoundException:
        $response = $this->respondNotFound(__('messages.error.notfound'));
        break;
      case $e instanceof HttpException:
        $response = $this->respondError($e->getMessage());
        break;
      case $e->getCode() >= 400 && $e->getCode() < 500:
        $response = $this->apiResponse(['message' => $e->getMessage()], $e->getCode());
        break;
      default:
        Log::error("{$e->getMessage()} on file {$e->getFile()} on line {$e->getLine()}");
        return $this->apiResponse(['message' => $e->getMessage()], 500);
    }
    return $response;
  }
}
