<?php

namespace App\Repositories\Contracts\Clients;

use Illuminate\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator;
    public function findById(int $id);
    public function store(string $name, string $status, ?string $description, int $userId);
    public function update(int $id, ?string $name, ?string $status, ?string $description);
    public function delete(int $id): ?bool;
}
