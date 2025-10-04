<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    protected $settings;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(SettingRepositoryInterface $settings)
    {
        $this->middleware('auth:api', ['except' => ['login']]);

        $this->settings = $settings;

        $allSettings = $this->settings->allInArray();
        Config::set('app.settings', $allSettings);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['name', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => __('messages.unauthorized')], 401);
        }

        $user = auth()->user();

        if (! $user->status) {
            auth()->logout();

            return response()->json(['error' => __('messages.user_inactive')], 403);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
