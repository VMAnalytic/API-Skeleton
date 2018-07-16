<?php

namespace App\Service\Fractal;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFinalizer
{
    private $body;

    /**
     * ResponseFinalizer constructor.
     *
     * @param $body
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @param callable $fn
     * @return $this
     */
    public function using(callable $fn)
    {
        $this->body = $fn($this->body);

        return $this;
    }

    /**
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    public function asResponse(int $statusCode = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($this->body, $statusCode, $headers);
    }
}
