<?php

namespace App\Http\Controllers;

use Exception;
use App\Contracts\ShopeeServiceContract;
use App\Http\Requests\User\AuthorizationSignRequest;
use App\Http\Requests\User\GetTokenRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RecoverPasswordRequest;
use App\Http\Requests\User\RefreshTokenRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Models\User\PasswordReset;
use App\Models\User\User;
use App\Notifications\PasswordResetNotification;
use App\Notifications\RegisterFinishedNotification;
use App\Services\CacheManagement\CacheManagement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{

  protected ShopeeServiceContract $shopeeService;
  protected CacheManagement $cacheManagement;

  public function __construct(ShopeeServiceContract $shopeeService, CacheManagement $cacheManagement)
  {
    $this->shopeeService = $shopeeService;
    $this->cacheManagement = $cacheManagement;
  }

  public function refreshToken(RefreshTokenRequest $request): JsonResponse
  {
    try {
      $this->shopeeService->refreshToken();
      return response()->json('ok but no content');
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }

  public function getToken(GetTokenRequest $request): JsonResponse
  {
    try {
      $this->shopeeService->getToken($request->input('code'));
      return response()->json('ok but no content');
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }

  public function authorizationSign(AuthorizationSignRequest $request): JsonResponse
  {
    try {
      return $this->respondWithSuccess($this->shopeeService->authorizationSign($request->shop_id));
    } catch (\Exception $e) {
      return $this->fail($e);
    }
  }

  /**
   * @param RegisterRequest $request
   * @return JsonResponse
   */
  public function register(RegisterRequest $request): JsonResponse
  {
    try {
      DB::beginTransaction();
      /** @var User $user */
      $user = User::query()->create($request->validated());
      $user->notify(new RegisterFinishedNotification($user));
      DB::commit();

      return $this->respondOk('Registration Successfully');
    } catch (\Exception $e) {
      dd('teste');
      DB::rollBack();
      return $this->fail($e);
    }
  }

  /**
   * @param LoginRequest $request
   * @return JsonResponse
   */
  public function login(LoginRequest $request): JsonResponse
  {
    try {
      $user = User::where('email', $request->input('email'))->first();
      if (!$user || !Hash::check($request->input('password'), $user->password)) {
        throw new Exception('Invalid Credentials', 401);
      }
      $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
      return $this->respondWithSuccess(['access_token' => $token]);
    } catch (\Exception $e) {
      return $this->fail($e);
    }
  }

  /**
   * @return JsonResponse
   */
  public function logout(): JsonResponse
  {
    auth('sanctum')->logout();
    return response()->json(null, 204);
  }

  public function requestPasswordRecovery(RecoverPasswordRequest $request): JsonResponse
  {
    /** @var User $user */
    $user = User::query()->where('email', $request->input('email'))->firstOrFail();
    $userToken = $user->password_recovery_token;
    PasswordReset::query()->updateOrCreate([
      'email' => $user->email,
      'token' => $userToken,
      'created_at' => now()
    ]);
    $user->notify(new PasswordResetNotification($user, $userToken));
    return $this->respondOk('Recovery password token sent.');
  }

  public function resetPassword(ResetPasswordRequest $request): JsonResponse
  {
    $user = User::query()
      ->leftJoin('password_reset_tokens', 'users.email', '=', 'password_reset_tokens.email')
      ->where('users.email', $request->input('users.email'))
      ->where('recovery_solicitation.token', $request->input('recovery_token'))
      ->firstOrFail();

    $user->update(['password' => $request->input('password')]);
    $user->recovery_solicitation->delete();
    return $this->respondOk('Password successfully changed.');
  }
}
