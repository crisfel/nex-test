<?php

namespace App\Repositories\Clients;

use App\Models\Client;
use App\Repositories\Contracts\Clients\ClientRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientRepository implements ClientRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Client::withCount('contacts');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(10);
    }

    public function findById(int $id)
    {
        return Client::with('contacts')->findOrFail($id);
    }

    public function store(string $name, string $status, ?string $description, int $userId)
    {
        $client = new Client();
        $client->name = $name;
        $client->status = $status;
        $client->description = $description;
        $client->user_id = $userId;
        $client->save();

        return $client;
    }

    public function update(int $id, ?string $name, ?string $status, ?string $description)
    {
        $client = $this->findById($id);
        $client->name = !is_null($name) ? $name : $client->name;
        $client->status = !is_null($status) ? $status : $client->status;
        $client->description = !is_null($description) ? $description : $client->description;
        $client->save();

        return $client;
    }

    public function delete(int $id): ?bool
    {
        $client = $this->findById($id);
        return $client->delete();
    }
}
