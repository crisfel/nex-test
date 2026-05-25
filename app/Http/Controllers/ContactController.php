<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(
        private ContactRepository $contactRepository
    ) {}

    public function index(Client $client): JsonResponse
    {
        $contacts = $this->contactRepository->getByClient($client);

        return response()->json($contacts);
    }

    public function store(StoreContactRequest $request, Client $client): JsonResponse
    {
        $contact = $this->contactRepository->create($client, [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_primary' => $request->boolean('is_primary', false),
            'user_id' => auth()->id(),
        ]);

        return response()->json($contact, 201);
    }

    public function update(UpdateContactRequest $request, Contact $contact): JsonResponse
    {
        $data = $request->validated();

        if ($request->has('is_primary')) {
            $data['is_primary'] = $request->boolean('is_primary');
        }

        $contact = $this->contactRepository->update($contact, $data);

        return response()->json($contact);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $this->contactRepository->delete($contact);

        return response()->json(['message' => 'Contacto eliminado correctamente.']);
    }
}
