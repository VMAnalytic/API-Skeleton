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

    public function using(callable $fn)
    {
        $this->body = $fn($this->body);

        return $this;
    }

    public function asResponse($statusCode = 200, array $headers = [])
    {
        return new JsonResponse($this->body, $statusCode, $headers);
    }
}
