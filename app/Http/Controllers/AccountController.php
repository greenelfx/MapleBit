<?php

namespace App\Http\Controllers;

use App\Helpers\PasswordHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Disconnect a game account.
     *
     * @param  Request  $request
     * @return Response
     *
     * @OA\Post(
     *     path="/user/disconnect",
     *     tags={"user"},
     *     summary="Disconnect the authenticated user account",
     *     operationId="disconnectAccount",
     *     @OA\Response(
     *         response=200,
     *         description="Indicates the account has been updated with loggedin=0"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function disconnectAccount(Request $request)
    {
        $request->user()->loggedin = 0;
        $request->user()->save();

        return ['status' => 'success', 'message' => 'Successfully disconnected account'];
    }

    /**
     * Modify the password of the authenticated user account.
     * @param  Request  $request
     * @return Response
     *
     * @OA\Post(
     *     path="/user/update",
     *     tags={"user"},
     *     summary="Modify the password of the authenticated user account",
     *     operationId="updateAccount",
     *     @OA\Response(
     *         response=200,
     *         description="Indicates the account has been updated."
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *         @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *              type="object",
     *              required={"password", "new_password", "new_verify_password"},
     *              @OA\Property(
     *                  property="password",
     *                  description="current password",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="new_password",
     *                  description="new password",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="new_verify_password",
     *                  description="confirm new password",
     *                  type="string"
     *              ),
     *          )
     *       )
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function updateAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'new_password'   => 'required|min:6',
            'new_verify_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return ['status' => 'validation', 'errors' => $validator->errors()];
        }
        if (! Hash::check($request->password, $request->user()->site_password)) {
            return ['status' => 'invalid_info', 'errors' => ['information' => 'Your current password is incorrect.']];
        }
        $user = $request->user();
        $user->password = PasswordHelper::hash($request->password);
        $user->site_password = Hash::make($request->new_password);
        $user->save();

        return ['status' => 'success'];
    }

    /**
     * Get basic information of the authenticated user.
     * @param  Request  $request
     * @return Response
     *
     * @OA\Get(
     *     path="/user/me",
     *     tags={"user"},
     *     summary="Get basic information of the authenticated user.",
     *     operationId="getMe",
     *     @OA\Response(
     *         response=200,
     *         description="Successfully returned basic information"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function getMe(Request $request)
    {
        return ['status' => 'success', 'user' => $request->user()->getBasicInfo()];
    }
}
