<?php

if (!function_exists('belongsToRole')) {
  /**
   * @param string|array $role
   *
   * @return bool
   */
  function belongsToRole($role): bool
  {
    if (!auth('sanctum')->check()) {
      return false;
    }

    $roles = is_string($role) ? [$role] : $role;
    return is_int(array_search(auth('sanctum')->user()->role, $roles));
  }
}

if (!function_exists('isMe')) {
  /**
   * @param int $userId
   *
   * @return bool
   */
  function isMe(int $userId): bool
  {
    if (!auth('sanctum')->check()) {
      return false;
    }

    return auth('sanctum')->user()->id === $userId;
  }
}
