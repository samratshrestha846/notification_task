<?php

namespace App\Helpers;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ResponseHelper
{
    private array $headers = [
        'Content-Disposition' => 'attachment; filename=',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0',
    ];

    public function responseOk(mixed $data, array|string $message = '', ?array $metaData = null): JsonResponse
    {
        $responseFormat = [
            'status' => 'ok',
            'message' => $this->getFormattedMessage($message) ?: 'Operation Successful',
            'data' => $data,

        ];
        if ($metaData) {
            $responseFormat['meta_data'] = $metaData;
        }
        if (config('app.debug')) {
            $responseFormat['debug'] = app('debugbar')->getData();
        }

        return response()->json($responseFormat, Response::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    private function getFormattedMessage(array|string $message): string
    {
        if (is_array($message)) {
            return (string)$message[0];
        }

        return $message;
    }

    public function responseCreated(mixed $data, array|string $message = ''): JsonResponse
    {
        $responseData = [
            'status' => 'ok',
            'message' => $this->getFormattedMessage($message) ?: 'Data created Successfully',
            'data' => $data,
        ];

        if (config('app.debug')) {
            $responseData['debug'] = app('debugbar')->getData();
        }

        return response()->json($responseData, Response::HTTP_CREATED, [], JSON_PRETTY_PRINT);
    }

    public function responseUpdated(mixed $data, array|string $message = ''): JsonResponse
    {
        $responseData = [
            'status' => 'ok',
            'message' => $this->getFormattedMessage($message) ?: 'Data modified successfully',
            'data' => $data,
        ];
        if (config('app.debug')) {
            $responseData['debug'] = app('debugbar')->getData();
        }

        return response()->json($responseData, Response::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    public function responseSoftDeleted(bool $deleted = true, array|string $message = ''): JsonResponse
    {
        $responseData = [
            'status' => 'ok',
            'message' => $this->getFormattedMessage($message) ?: 'Deleted successfully',
        ];
        if (config('app.debug')) {
            $responseData['debug'] = app('debugbar')->getData();
        }

        return response()->json($responseData, Response::HTTP_ACCEPTED, [], JSON_PRETTY_PRINT);
    }

    public function responseDeleted(bool $deleted = true, string $message = ''): JsonResponse
    {
        $responseData = [
            'status' => 'ok',
            'message' => $this->getFormattedMessage($message) ?: 'Deleted successfully',
        ];
        if (config('app.debug') == true) {
            $responseData['debug'] = app('debugbar')->getData();
        }

        return response()->json($responseData, Response::HTTP_ACCEPTED, [], JSON_PRETTY_PRINT);
    }

    public function responsePaginate(LengthAwarePaginator $models, array|string $message = ''): JsonResponse
    {
        $responseData = [
            'status' => 'ok',
            'message' => $this->getFormattedMessage($message) ?: 'Data loaded successfully',
            'data' => $models->items(),
            'meta_data' => [
                'pagination' => [
                    'total' => $models->total(),
                    'per_page' => $models->perPage(),
                    'last_page' => $models->lastPage(),
                    'current_page' => $models->currentPage(),
                    'from' => $models->firstItem(),
                    'to' => $models->lastItem(),
                ],
            ],
        ];
        if (config('app.debug')) {
            $responseData['debug'] = app('debugbar')->getData();
        }

        return response()->json($responseData, Response::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    public function responseStream(Closure $callBack, array $headers): StreamedResponse
    {
        return response()->streamDownload($callBack, (string)request()->input('file_name'), $headers);
    }

    public function csvHeaders(): array
    {
        $this->headers['Content-type'] = 'text/csv';
        $this->headers['Content-Disposition'] = $this->headers['Content-Disposition'] . request()->input('file_name');

        return $this->headers;
    }

    public function responseOkWithNoMessage(array $data): JsonResponse
    {
        return response()->json([], Response::HTTP_NO_CONTENT, [], JSON_PRETTY_PRINT);
    }

    /**
     * @param string $codeText
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseServerError(
        string $codeText = 'internal server error occurred',
        string $message = 'internal_server_error',
        array  $headers = []
    ): JsonResponse
    {
        return $this->responseError(SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR, $message, $codeText);
    }

    public function responseError(
        int          $code = 500,
        array|string $message = '',
                     $codeContext = '',
                     $errors = []
    ): JsonResponse
    {
        $responseData = [
            'status' => 'error',
            'code' => $code,
            'code_context' => $codeContext,
            'message' => $this->getFormattedMessage($message) ?: 'There was some error.',
        ];
        if (count($errors)) {
            $responseData['errors'] = $errors;
        }
        return response()->json($responseData, $code, [], JSON_PRETTY_PRINT);
    }

    /**
     * @param string $message
     * @param string $codeText
     * @param array $headers
     * @return JsonResponse
     */
    public function responseUnAuthorize(
        string $message = 'You are not authorized to perform the operation',
        string $codeText = 'unauthorized',
        array  $headers = []
    )
    {
        return $this->responseError(SymfonyResponse::HTTP_UNAUTHORIZED, $message, $codeText);
    }

    /**
     * @param string $codeText
     * @param string $message
     * @param array $headers
     * @return JsonResponse
     */
    public function responseForbidden(
        string $message = 'Operation not allowed',
        string $codeText = 'forbidden',
        array  $headers = []
    ): JsonResponse
    {
        return $this->responseError(SymfonyResponse::HTTP_FORBIDDEN, $message, $codeText);
    }

    /**
     * @param string $codeText
     * @param string $message
     * @param array $headers
     * @return JsonResponse
     */
    public function responseNotFound(
        string $message = 'Resource not found',
        string $codeText = 'not found',
        array  $headers = []
    ): JsonResponse
    {
        return $this->responseError(SymfonyResponse::HTTP_NOT_FOUND, $message, $codeText);
    }

    /**
     * @param string $codeText
     * @param string $message
     * @param array $headers
     * @return JsonResponse
     */
    public function responseBadRequest(
        string $codeText = 'bad request',
        string $message = 'Bad Request - request has bad information',
        array  $headers = []
    ): JsonResponse
    {
        return $this->responseError(SymfonyResponse::HTTP_BAD_REQUEST, $message, $codeText);
    }

    /**
     * @param string $codeText
     * @param string $message
     * @param array $headers
     * @return JsonResponse
     */
    public function responsePreConditionFailed(
        string $codeText = 'precondition failed',
        string $message = 'precondition_failed',
        array  $headers = []
    ): JsonResponse
    {
        return $this->responseError(SymfonyResponse::HTTP_PRECONDITION_FAILED, $message, $codeText);
    }

    /**
     * @param string $message
     * @param string $codeText
     * @param array $headers
     * @return JsonResponse
     */
    public function responseConflict(
        string $message = 'resource conflict, server could not identify which resource to be processed',
        string $codeText = 'conflict',
        array  $headers = []
    ): JsonResponse
    {
        return $this->responseError(SymfonyResponse::HTTP_CONFLICT, $codeText, $message);
    }

    /**
     * @param null $body
     * @param string $codeText
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseExpectationFailed(
        string $message = 'expectation failed',
        string $codeText = 'expectation failed',
        array  $headers = []
    ): JsonResponse
    {
        return $this->responseError(SymfonyResponse::HTTP_EXPECTATION_FAILED, $message, $codeText);
    }

    /**
     * @param null $body
     * @param string $codeText
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseValidationError(
        string $message = 'Invalid data provided',
        string $codeText = 'form validation failed',
        array  $errors = []
    ): JsonResponse
    {
        return $this->responseError(SymfonyResponse::HTTP_EXPECTATION_FAILED, $message, $codeText, $errors);
    }

    /**
     * @param string $message
     * @param string $codeText
     * @param array $headers
     * @return JsonResponse
     */
    public function responseTooManyAttempts(
        string $message = 'too many request',
        string $codeText = 'too many request',
        array  $headers = []
    ): JsonResponse
    {
        return $this->responseError(SymfonyResponse::HTTP_TOO_MANY_REQUESTS, $message, $codeText,);
    }
}
