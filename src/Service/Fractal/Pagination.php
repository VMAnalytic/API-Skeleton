<?php

namespace App\Service\Fractal;

class Pagination
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int|null
     */
    private $before;

    /**
     * Pagination constructor.
     *
     * @param int      $offset
     * @param int      $limit
     * @param int|null $before
     */
    public function __construct(int $offset = 0, int $limit = 20, $before = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->before = (int) $before;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getBefore(): ?int
    {
        return $this->before;
    }

}
