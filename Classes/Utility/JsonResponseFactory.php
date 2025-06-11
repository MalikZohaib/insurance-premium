<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Utility;

use TYPO3\CMS\Core\Http\JsonResponse;

/**
 * Helper class to create consistent JSON responses for AJAX/API calls.
 */
class JsonResponseFactory
{
    /**
     * Create a success response with optional data and message.
     */
    public static function success(array $data = [], string $message = '', int $statusCode = 200): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Create an error response with message and optional HTTP status code.
     */
    public static function error(string $message = 'An error occurred', int $statusCode = 400): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => $message,
            'data' => null,
        ], $statusCode);
    }

    /**
     * Create a validation error response with specific field messages.
     */
    public static function validationError(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return new JsonResponse([
            'status' => 'validation_error',
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }
}
