<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Clients\ClientRepositoryInterface;
use Illuminate\Http\JsonResponse;

class GetByIdController extends Controller
{
    protected ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(int $id): JsonResponse
    {
        try {
            $client = $this->clientRepository->findById($id);

            return response()->json($client);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
