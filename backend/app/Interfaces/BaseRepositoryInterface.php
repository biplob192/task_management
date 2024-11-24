<?php

namespace App\Interfaces;

use App\DataTransferObjects\PaginateData;

interface BaseRepositoryInterface {
    public function index();
    public function show($id);
    public function store(array $data, $request);
    public function update($id, array $data, $request);
    public function delete($id);
    // public function indexWithPaginate($perPage);
    public function indexWithPaginate(PaginateData $paginateData);
}
