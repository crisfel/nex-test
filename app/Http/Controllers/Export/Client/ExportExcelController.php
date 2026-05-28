<?php

namespace App\Http\Controllers\Export\Client;

use App\Exports\ClientsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelController extends Controller
{
    public function __invoke()
    {
        $search = request()->input('search', '');
        $status = request()->input('status');

        return Excel::download(
            new ClientsExport($search, $status),
            'clientes.xlsx'
        );
    }
}
