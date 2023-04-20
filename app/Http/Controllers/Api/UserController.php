<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('employee.person')->load('settings')->load('permissions');

        return new UserResource($user);
    }
}
