<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Disconnect a game account
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

}
