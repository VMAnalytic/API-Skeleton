<?php

namespace App\Service\Fractal;

use Doctrine\ORM\Tools\Pagination\Paginator;
use League\Fractal\Pagination\PaginatorInterface;

class DoctrinePaginatorAdapter implements PaginatorInterface
{
    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * DoctrinePaginatorAdapter constructor.
     *
     * @param Paginator  $paginator
     * @param Pagination $pagination
     */
    public function __construct(Paginator $paginator, Pagination $pagination)
    {
        $this->paginator = $paginator;
        $this->pagination = $pagination;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->pagination->getOffset();
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return ceil($this->getTotal() / $this->getPerPage());
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->paginator->count();
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->paginator->count();
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->pagination->getLimit();
    }

    /**
     * @param int $page
     * @return string
     */
    public function getUrl($page): string
    {
        return '/';
    }
}
