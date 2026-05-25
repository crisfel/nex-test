<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Repositories\Contracts\Contacts\ContactRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    protected ContactRepositoryInterface $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function __invoke(StoreContactRequest $request, int $clientId): JsonResponse
    {
        try {
            $contact = $this->contactRepository->store(
                $clientId,
                $request->input('name'),
                $request->input('email'),
                $request->input('phone'),
                $request->boolean('is_primary', false),
                auth()->id()
            );

            return response()->json($contact, 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
