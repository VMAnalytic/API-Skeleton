<?php

namespace App\Http\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
//use FutureWorld\Domain\User\User;
//use FutureWorld\Service\Fractal\Resource;
//use FutureWorld\Service\Fractal\SymfonyFractalHelper;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiController extends Controller
{
    protected function flushChanges()
    {
        $this->get('doctrine.orm.default_entity_manager')->flush();
    }

    protected function collection($data, $transformer, array $metadata = []): Resource
    {
        return $this->get(Collection::class)->collection($data, $transformer, $metadata);
    }

    protected function item($data, $transformer, array $metadata = [])
    {
        return new ResponseFinalizer(
            $this->get(Item::class)->item($data, $transformer, $metadata)
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
