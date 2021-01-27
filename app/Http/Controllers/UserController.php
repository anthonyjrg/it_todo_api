<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index(){
        return Auth::user();
    }

    public function tasks(){
        return Auth::user()->tasks;
    }

    public function destroy(){
        return Auth::user()->tokens()->where('id', Auth::user()->currentAccessToken()->id)->delete();
    }
}