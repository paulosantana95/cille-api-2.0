<?php

namespace App\Services\Shopee;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use JsonException;
use Psr\Http\Message\ResponseInterface;

class ShopeeRequestClient
{
  protected string $baseUrl;
  protected array $options;

  public function __construct()
  {
    $this->baseUrl = env('SHOPEE_BASE_URL') . 'api/v2';
    $this->options = ['partner_id' => env('SHOPEE_PARTNER_ID')];
  }

  public function get(string $endpoint, ?string $access_token = null, ?string $shop_id = null, ?array $queries = [])
  {
    $sign = ShopeeService::generateSign($endpoint, $access_token, $shop_id);
    $this->options = compact('access_token', 'shop_id');

    return Http::withQueryParameters([...$queries, ...$this->options, ...$sign])
      ->get("$this->baseUrl/$endpoint");
  }

  public function post(
    string $endpoint,
    ?string $access_token = null,
    ?string $shop_id = null,
    ?array $queries = [],
    ?array $data = []
  ) {
    $sign = ShopeeService::generateSign($endpoint, $access_token, $shop_id);
    if($shop_id) {
      $this->options['shop_id'] = (int)$shop_id;
    }
    if($access_token) {
      $this->options['access_token'] = (int)$access_token;
    }

    return Http::withQueryParameters([...$queries, ...$this->options, ...$sign])
      ->post("$this->baseUrl/$endpoint", $data);
  }
}
