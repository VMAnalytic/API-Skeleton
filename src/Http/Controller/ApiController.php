<?php

namespace App\Http\Controller;

use App\Service\Fractal\FractalService;
use App\Service\Fractal\ResponseFinalizer;
use Doctrine\ORM\Tools\Pagination\Paginator;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
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

    protected function collection($data, $transformer, array $metadata = [])
    {
        return $this->fractal->collection($data, $transformer, $metadata);
    }

    protected function item($data, $transformer, array $metadata = [])
    {
        return new ResponseFinalizer(
            array_merge($this->get(FractalService::class)->item($data, $transformer), $metadata)
        );
    }

    protected function resource($data, $transformer, array $metadata = []): Resource
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
