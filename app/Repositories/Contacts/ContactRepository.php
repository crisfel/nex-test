<?php

namespace App\Repositories\Contacts;

use App\Models\Contact;
use App\Repositories\Contracts\Contacts\ContactRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ContactRepository implements ContactRepositoryInterface
{
    public function getByClient(int $clientId)
    {
        return Contact::where('client_id', $clientId)->latest()->get();
    }

    public function store(int $clientId, string $name, string $email, ?string $phone, bool $isPrimary, int $userId)
    {
        return DB::transaction(function () use ($clientId, $name, $email, $phone, $isPrimary, $userId) {
            if ($isPrimary) {
                Contact::where('client_id', $clientId)->update(['is_primary' => false]);
            }

            $contact = new Contact();
            $contact->name = $name;
            $contact->email = $email;
            $contact->phone = $phone;
            $contact->is_primary = $isPrimary;
            $contact->client_id = $clientId;
            $contact->user_id = $userId;
            $contact->save();

            return $contact;
        });
    }

    public function update(int $id, ?string $name, ?string $email, ?string $phone, ?bool $isPrimary)
    {
        return DB::transaction(function () use ($id, $name, $email, $phone, $isPrimary) {
            $contact = Contact::findOrFail($id);

            if ($isPrimary === true) {
                Contact::where('client_id', $contact->client_id)
                    ->where('id', '!=', $contact->id)
                    ->update(['is_primary' => false]);
            }

            if (!is_null($name)) $contact->name = $name;
            if (!is_null($email)) $contact->email = $email;
            $contact->phone = !is_null($phone) ? $phone : $contact->phone;
            if (!is_null($isPrimary)) $contact->is_primary = $isPrimary;

            $contact->save();

            return $contact;
        });
    }

    public function delete(int $id): ?bool
    {
        $contact = Contact::findOrFail($id);
        return $contact->delete();
    }
}
