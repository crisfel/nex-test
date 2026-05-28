<?php

namespace App\Http\Controllers\Export\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportPdfController extends Controller
{
    public function __invoke()
    {
        $search = request()->input('search', '');
        $status = request()->input('status');

        $query = Client::with('contacts');

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $clients = $query->latest()->get();

        $pdf = Pdf::loadView('exports.clients-pdf', compact('clients'));

        return $pdf->download('clientes.pdf');
    }
}
