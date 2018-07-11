<?php

namespace App\Service\Fractal;

use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\DataArraySerializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DataSerializer extends DataArraySerializer
{
    /**
     * @var NormalizerInterface
     */
    private $serializer;

    /**
     * DataSerializer constructor.
     *
     * @param NormalizerInterface $serializer
     */
    public function __construct(NormalizerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function collection($resourceKey, array $data)
    {
        return $this->serializer->normalize(parent::collection($resourceKey, $data));
    }

    public function item($resourceKey, array $data)
    {
        return $this->serializer->normalize($data);
    }

    /**
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function paginator(PaginatorInterface $paginator): array
    {
        if (!$paginator instanceof DoctrinePaginatorAdapter) {
            return [];
        }

        return [
            'pagination' => [
                'total' => $paginator->getTotal(),
                'offset' => $paginator->getCurrentPage(),
                'limit' => $paginator->getPerPage(),
            ]
        ];
    }
}
