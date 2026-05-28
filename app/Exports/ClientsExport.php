<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithMapping
{
    protected string $search;
    protected ?string $status;

    public function __construct(string $search = '', ?string $status = null)
    {
        $this->search = $search;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Client::with('contacts');

        if (!empty($this->search)) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Estado',
            'Descripción',
            'Contacto principal',
            'Email contacto',
            'Teléfono contacto',
            'Creado el',
        ];
    }

    public function map($client): array
    {
        $primary = $client->contacts->firstWhere('is_primary', true);

        return [
            $client->name,
            $client->status,
            $client->description ?? '',
            $primary?->name ?? '',
            $primary?->email ?? '',
            $primary?->phone ?? '',
            $client->created_at ? $client->created_at->format('d/m/Y') : '',
        ];
    }
}
