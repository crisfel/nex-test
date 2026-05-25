<?php

namespace App\Repositories\Contracts\Contacts;

interface ContactRepositoryInterface
{
    public function getByClient(int $clientId);
    public function store(int $clientId, string $name, string $email, ?string $phone, bool $isPrimary, int $userId);
    public function update(int $id, ?string $name, ?string $email, ?string $phone, ?bool $isPrimary);
    public function delete(int $id): ?bool;
}
