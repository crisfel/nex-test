<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Clients\ClientRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class GetAllController extends Controller
{
    protected ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(): JsonResponse
    {
        try {
            $filters = request()->only(['search', 'status']);
            $clients = $this->clientRepository->getAll($filters);

            return response()->json($clients);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
