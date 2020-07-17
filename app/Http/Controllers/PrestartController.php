<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestartController extends Controller
{
    /**
     * Boot up the frontend with all the data we need.
     *
     * @param  Request  $request
     * @return Response
     */
    public function serverInfo(Request $request)
    {
        return ['status' => 'success', 'data' => [
            'server_data' => config('maplebit.server_data'),
        ]];
    }
}
