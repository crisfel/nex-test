<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Repositories\Contracts\Contacts\ContactRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    protected ContactRepositoryInterface $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function __invoke(UpdateContactRequest $request): JsonResponse
    {
        try {
            $id = intval($request->input('id'));
            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $isPrimary = $request->has('is_primary') ? $request->boolean('is_primary') : null;

            $contact = $this->contactRepository->update($id, $name, $email, $phone, $isPrimary);

            return response()->json($contact);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
