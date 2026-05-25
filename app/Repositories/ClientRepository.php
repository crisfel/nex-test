<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientRepository
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Client::withCount('contacts');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%");
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(10);
    }

    public function findById(int $id): Client
    {
        return Client::with(['contacts' => function ($q) {
            $q->latest();
        }])->findOrFail($id);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client->fresh();
    }

    public function delete(Client $client): ?bool
    {
        return $client->delete();
    }
}
