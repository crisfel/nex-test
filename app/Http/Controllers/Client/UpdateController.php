<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Repositories\Contracts\Clients\ClientRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    protected ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(UpdateClientRequest $request): JsonResponse
    {
        try {
            $id = intval($request->input('id'));
            $name = $request->input('name');
            $status = $request->input('status');
            $description = $request->input('description');

            $client = $this->clientRepository->update($id, $name, $status, $description);

            return response()->json($client);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
