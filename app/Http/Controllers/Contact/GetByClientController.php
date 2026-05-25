<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Contacts\ContactRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetByClientController extends Controller
{
    protected ContactRepositoryInterface $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function __invoke(int $clientId): JsonResponse
    {
        try {
            $contacts = $this->contactRepository->getByClient($clientId);

            return response()->json($contacts);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
