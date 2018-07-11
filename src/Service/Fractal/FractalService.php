<?php

namespace App\Service\Fractal;

use App\Domain\Entity\Pagination;
use ArrayIterator;
use Doctrine\ORM\Tools\Pagination\Paginator;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\RequestStack;

class FractalService
{
    private $requestStack;
    private $fractalManager;

    public function __construct(RequestStack $requestStack, DataSerializer $dataSerializer)
    {
        $this->requestStack = $requestStack;
        $this->fractalManager = (new FractalManager())
            ->setSerializer($dataSerializer);
    }

    /**
     * @param $item
     * @param TransformerAbstract $transformer
     *
     * @return array
     */
    public function item($item, TransformerAbstract $transformer): array
    {
        return $this
            ->fractalManager
            ->createData(new Item($item, $transformer))
            ->toArray();
    }

    /**
     * @param array|ArrayIterator $data
     * @param TransformerAbstract $transformer
     * @return array
     */
    public function collection($data, TransformerAbstract $transformer): array
    {
        $resource = new Collection($data, $transformer);

        if ($data instanceof Paginator) {
            $resource->setPaginator(
                new DoctrinePaginatorAdapter($data, $this->getPaginationSettings())
            );
        }

        return $this->fractalManager
            ->createData($resource)
            ->toArray();
    }

    /**
     * @return Pagination
     */
    private function getPaginationSettings(): Pagination
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return $this->getDefaultPaginationSettings();
        }

        foreach ($request->attributes->all() as $value) {
            if ($value instanceof Pagination) {
                return $value;
            }
        }

        return $this->getDefaultPaginationSettings();
    }

    /**
     * @return Pagination
     */
    private function getDefaultPaginationSettings(): Pagination
    {
        return new Pagination();
    }
}
