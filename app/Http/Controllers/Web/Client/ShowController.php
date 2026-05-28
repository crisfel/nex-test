<?php

namespace App\Http\Controllers\Web\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;

class ShowController extends Controller
{
    public function __invoke(Client $client)
    {
        return view('clients.detail', ['client' => $client]);
    }
}
