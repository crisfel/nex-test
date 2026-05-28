<?php

namespace App\Http\Controllers\Web\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;

class EditController extends Controller
{
    public function __invoke(Client $client)
    {
        return view('clients.form', ['client' => $client]);
    }
}
