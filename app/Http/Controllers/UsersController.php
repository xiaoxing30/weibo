<?php

namespace App\Http\Controllers;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

}