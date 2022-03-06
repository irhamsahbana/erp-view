<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class Controller extends LaravelController
{
    public function __construct()
    {
        if (Auth::check()) {
            $authUser = Auth::user();
        }
    }
}