<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Clients\ClientRepositoryInterface;
use Illuminate\Http\JsonResponse;

class DeleteController extends Controller
{
    protected ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(int $id): JsonResponse
    {
        try {
            $this->clientRepository->delete($id);

            return response()->json(['message' => 'Cliente eliminado correctamente.']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
