<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index(){
        // проста возвращаю список всех юзеров
        return User::all();
    }
}
