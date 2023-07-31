<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Gets the current user.
     * @return \Illuminate\Http\Response
     */
    public function getUser()
    {
        $user =  Auth::user();

        return response()->json($user);
    }

}
