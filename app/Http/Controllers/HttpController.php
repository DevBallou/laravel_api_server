<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HttpController extends Controller
{
    public function store(Request $request)
    {
        $response = Http::withHeaders([
            'X-First' => 'Laravelia',
            'X-Second' => 'CodeCheef'
        ])->post('http://127.0.0.1:8000/users', [
            'name' => 'Mahedi Hasan',
        ]);

        dd($response);
    }
}
