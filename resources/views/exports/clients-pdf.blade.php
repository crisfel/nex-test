<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Clientes - {{ config('app.name', 'Mini CRM') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #333; font-size: 20px; margin-bottom: 5px; }
        p.date { color: #666; font-size: 11px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #4338ca; color: white; font-size: 11px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status { font-size: 10px; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Listado de clientes</h1>
    <p class="date">Generado el {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Descripción</th>
                <th>Contacto principal</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Creado el</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clients as $client)
                @php $primary = $client->contacts->firstWhere('is_primary', true); @endphp
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->status }}</td>
                    <td>{{ $client->description ?? '' }}</td>
                    <td>{{ $primary?->name ?? '' }}</td>
                    <td>{{ $primary?->email ?? '' }}</td>
                    <td>{{ $primary?->phone ?? '' }}</td>
                    <td>{{ $client->created_at?->format('d/m/Y') ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">No se encontraron clientes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
