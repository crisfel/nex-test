<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Contacts\ContactRepositoryInterface;
use Illuminate\Http\JsonResponse;

class DeleteController extends Controller
{
    protected ContactRepositoryInterface $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function __invoke(int $id): JsonResponse
    {
        try {
            $this->contactRepository->delete($id);

            return response()->json(['message' => 'Contacto eliminado correctamente.']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
