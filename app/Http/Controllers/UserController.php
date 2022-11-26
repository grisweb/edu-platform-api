<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function login(Request $request): string
    {
        $request->validate([
            'azureToken' => 'required'
        ]);

        $token = $request->input('azureToken');

        $response = Http::acceptJson()->withHeaders([
            'Authorization' => "Bearer ${token}"
        ])->get('https://graph.microsoft.com/v1.0/me');

        return $response;
    }
}
