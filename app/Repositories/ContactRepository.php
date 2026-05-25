<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class ContactRepository
{
    public function getByClient(Client $client)
    {
        return $client->contacts()->latest()->get();
    }

    public function create(Client $client, array $data): Contact
    {
        return DB::transaction(function () use ($client, $data) {
            if (!empty($data['is_primary'])) {
                $client->contacts()->update(['is_primary' => false]);
            }

            return $client->contacts()->create($data);
        });
    }

    public function update(Contact $contact, array $data): Contact
    {
        return DB::transaction(function () use ($contact, $data) {
            if (!empty($data['is_primary'])) {
                $contact->client->contacts()
                    ->where('id', '!=', $contact->id)
                    ->update(['is_primary' => false]);
            }

            $contact->update($data);
            return $contact->fresh();
        });
    }

    public function delete(Contact $contact): ?bool
    {
        return $contact->delete();
    }
}
