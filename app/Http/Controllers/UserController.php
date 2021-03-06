<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
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

    public function incompleteTasks(){
        return Auth::user()->incompleteTasks;
    }

    public function completeTasks(){
        return Auth::user()->completeTasks;
    }

    public function destroy(){
        return Auth::user()->tokens()->where('id', Auth::user()->currentAccessToken()->id)->delete();
    }

    public function all(){
        return User::all(["id", "fName", "lName", "pay_number", "email", "rank"])->values();
    }
}

