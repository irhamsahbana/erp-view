<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    public function test()
    {
        $username = 'userAdmin';
        $password = 'userAdmin';

        $response = Http::post($this->hostname.'/user/login', [
            'username' => $username,
            'password' => $password,
        ]);

        if ($response->status() == 200) {
            Session::flush();
            Session::put('jwtToken', $response->json()['data']['access_token']);

            $token = Session::get('jwtToken');

            $payload = explode('.', $token)[1];
            $payload = base64_decode($payload);
            $payload = (array) json_decode($payload);

            foreach ($payload as $key => $value) {
                Session::put('user.'.$key, $value);
            }

            dd(Session::all());

        } else {
            return redirect('/login');
        }
    }
}
