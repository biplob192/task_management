<?php

namespace App\DataTransferObjects;

class PaginateData
{
    public int $page;
    public int $perPage;
    public ?string $search;
    public string $sortKey;
    public string $sortOrder;
    public array $conditions;
    public array $customConditions;
    public array $searchableColumns;
    public array $specialSearch;

    public function __construct(
        int $page,
        int $perPage,
        ?string $search,
        string $sortKey,
        string $sortOrder,
        array $conditions,
        array $customConditions,
        array $searchableColumns,
        array $specialSearch
    ) {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->search = $search;
        $this->sortKey = $sortKey;
        $this->sortOrder = $sortOrder;
        $this->conditions = $conditions;
        $this->customConditions = $customConditions;
        $this->searchableColumns = $searchableColumns;
        $this->specialSearch = $specialSearch;
    }
}
