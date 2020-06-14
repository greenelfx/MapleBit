<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'create']]);
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"auth"},
     *     summary="Login as a user",
     *     operationId="login",
     *     @OA\Response(
     *         response=200,
     *         description="Success with token"
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *         @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *              required={"email", "password"},
     *              type="object",
     *              @OA\Property(
     *                  property="email",
     *                  description="user@email.com",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  description="password",
     *                  type="string"
     *              ),
     *          )
     *       )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation', 'errors' => $validator->errors()];
        }

        $user = User::where('email', Str::lower($request->email))->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return ['status' => 'authentication', 'errors' => ['credentials' => 'invalid credentials']];
        }

        return ['status' => 'success', 'token' => $user->createToken($request->server('HTTP_USER_AGENT'))->plainTextToken];
    }

    /**
     * @OA\Post(
     *     path="/auth/create",
     *     tags={"auth"},
     *     summary="Create a user",
     *     operationId="create",
     *     @OA\Response(
     *         response=200,
     *         description="Success with token"
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *         @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *              type="object",
     *              required={"email", "username","password", "password_confirm"},
     *              @OA\Property(
     *                  property="email",
     *                  description="user@email.com",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="username",
     *                  description="username",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  description="password",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="password_confirm",
     *                  description="Password Confirmation",
     *                  type="string"
     *              ),
     *          )
     *       )
     *     )
     * )
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:accounts,email|max:255',
            'username' => 'required|string|unique:accounts,name',
            'password' => 'required|max:255',
            'password_confirm' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation', 'errors' => $validator->errors()];
        }

        $user = new User();
        $user->email = $request->email;
        $user->name = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        return ['status' => 'success', 'token' => $user->createToken($request->server('HTTP_USER_AGENT'))->plainTextToken];
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     tags={"auth"},
     *     summary="Destroys current session",
     *     operationId="logout",
     *     @OA\Response(
     *         response=200,
     *         description="Indicates current session is destroyed"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function logout(Request $request)
    {
        $current_token_id = $request->user()->currentAccessToken()->id;
        $request->user()->tokens()->where('id', $current_token_id)->delete();

        return ['status' => 'success'];
    }

    /**
     * @OA\Post(
     *     path="/auth/revokeAll",
     *     tags={"auth"},
     *     summary="Destroys all sessions other than the one used to make this request",
     *     operationId="revokeAll",
     *     @OA\Response(
     *         response=200,
     *         description="Indicates sessions were destroyed"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function revokeAll(Request $request)
    {
        $current_token_id = $request->user()->currentAccessToken()->id;
        $request->user()->tokens()->where('id', '!=', $current_token_id)->delete();

        return ['status' => 'success', 'tokens' => _get_user_tokens($request->user())];
    }

    /**
     * @OA\Get(
     *     path="/auth/devices",
     *     tags={"auth"},
     *     summary="Gets all authenticated devices (sessions) for user",
     *     operationId="getUserDevices",
     *     @OA\Response(
     *         response=200,
     *         description="Device identifiers, names, last used"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function getUserDevices(Request $request)
    {
        return ['status' => 'success', 'tokens' => _get_user_tokens($request->user())];
    }
}

function _get_user_tokens($user)
{
    $tokens = $user->tokens()->get(['id', 'name', 'last_used_at'])->toArray();
    foreach ($tokens as $key => $field) {
        // Do not send back raw token ids
        $tokens[$key]['id'] = Hashids::encode($tokens[$key]['id']);

        // format timestamp to something readable for easier frontend display
        $tokens[$key]['last_used_at'] = Carbon::parse($tokens[$key]['last_used_at'])->diffForHumans();
    }

    return $tokens;
}
