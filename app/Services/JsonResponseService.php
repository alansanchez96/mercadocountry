<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseService
{
    public function success(string $action, mixed $value = null): JsonResponse
    {
        $data = [
            'msg' => "Se ha {$action} satisfactoriamente.",
            ($value === null) ?: 'data' => $value,
        ];

        return response()->json(array_filter($data), Response::HTTP_OK);
    }

    public function catch(string $message): JsonResponse
    {
        Log::error('Ocurrió un error: ' . $message);

        return response()->json([
            'error' => 'Problema inesperado al procesar la solicitud.'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function ModelError(string $message, string $type): JsonResponse
    {
        Log::error('Ocurrió un error: ' . $message);

        return response()->json([
            'error' => "El {$type} es incorrecto o no existe, vuelve a intentarlo",
        ], Response::HTTP_NOT_FOUND);
    }

    public function statusOk(): JsonResponse
    {
        return response()->json([
            'status' => '1'
        ]);
    }
}
