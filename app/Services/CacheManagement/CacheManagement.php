<?php

namespace App\Services\CacheManagement;

class CacheManagement
{
  protected $user_id;
  protected $redis;
  protected $prefix;

  public function __construct()
  {

    //TODO: Fix user authentication id
    if (auth('sanctum')->check()) {
      $this->user_id = auth('sanctum')->user()->id;
    }
    $this->redis = app()->make('redis');
    $this->prefix = "user_{$this->user_id}_";
  }

  public function set($key, $value): void
  {
    $this->redis->set($this->prefix . $key, $value);
  }

  public function get($key)
  {
    return $this->redis->get($this->prefix . $key);
  }

  public function del($key): void
  {
    $this->redis->del($this->prefix . $key);
  }
}
