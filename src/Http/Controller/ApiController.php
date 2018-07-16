<?php

namespace App\Http\Controller;

use App\Service\Fractal\FractalService;
use App\Service\Fractal\ResponseFinalizer;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiController extends Controller
{
    /**
     * @var FractalService
     */
    private $fractal;

    /**
     * ApiController constructor.
     * @param FractalService $fractal
     */
    public function __construct(FractalService $fractal)
    {
        $this->fractal = $fractal;
    }

    protected function flushChanges():void
    {
        $this->get('doctrine.orm.default_entity_manager')->flush();
    }

    protected function collection($data, $transformer, array $metadata = []): ResponseFinalizer
    {
        $data = $this->fractal->collection($data, $transformer);

        if (isset($data['meta'])) {
            $data['meta'] = array_merge($data['meta'], $metadata);
        }

        return new ResponseFinalizer(
            $data
        );
    }

    protected function item($data, $transformer, array $metadata = []): ResponseFinalizer
    {
        return new ResponseFinalizer(
            array_merge($this->fractal->item($data, $transformer), $metadata)
        );
    }

    protected function resource($data, $transformer, array $metadata = []): ResponseFinalizer
    {
        if (\is_array($data) || $data instanceof Paginator) {
            return $this->collection($data, $transformer, $metadata);
        }

        return $this->item($data, $transformer, $metadata);
    }

    /**
     * @return JsonResponse
     */
    protected function emptyCollection(): JsonResponse
    {
        return new JsonResponse(
            [
                'data' => [],
                'meta' => [
                    'pagination' => [
                        'total' => 0,
                        'offset' => 0,
                        'limit' => 0,
                    ]
                ]
            ]
        );
    }
}
