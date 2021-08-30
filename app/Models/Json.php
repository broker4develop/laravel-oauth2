<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class Json extends Model
{
    use HasFactory;

    /**
     * @param $resultCode string
     * @param $reply array
     * @return JsonResponse
     */
    public function apiResponseJson(string $resultCode, array $reply): JsonResponse
    {
        return response()->json([
            'result_code' => $resultCode,
            'reply' => $reply,
        ]);
    }

    /**
     * @param $resultCode string
     * @param $resultText string
     * @param array|null $errorMessages array
     * @return JsonResponse
     */
    public function apiResponseJsonError(string $resultCode, string $resultText, array $errorMessages = null): JsonResponse
    {
        return response()->json([
            'result_code' => $resultCode,
            'result_text' => $resultText,
            'reply' => $errorMessages
        ]);
    }
}
