<?php

namespace App\Services;

use Exception;
use App\DataTransferObjects\PaginateData;


class TaskService
{
    public function formateDataForIndexWithPaginate($request)
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('perPage', -1);
            $search = $request->get('search', null);
            $sortKey = $request->get('sort_key', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            // Basic conditions (e.g., is_active)
            $conditions = [
                ['column' => 'created_by', 'operator' => '=', 'value' => auth()->user()->id],
                // Add more simple conditions as needed
            ];

            // Searchable columns for the main search
            $searchableColumns = ['title',];

            // Define special searchable columns for specific column-based search
            $specialSearchableColumns = ['status', 'assigned_to'];

            // Collect any special search fields
            $specialSearch = [];
            foreach ($specialSearchableColumns as $column) {
                if ($request->has($column)) {
                    $specialSearch[$column] = $request->get($column);
                }
            }

            // Custom condition filters (e.g., for "not equal", "greater than")
            $customConditions = [];

            return new PaginateData(
                $page,
                $perPage,
                $search,
                $sortKey,
                $sortOrder,
                $conditions,
                $customConditions,
                $searchableColumns,
                $specialSearch
            );
        } catch (Exception $e) {
            throw new Exception("Error formating data for indexWithPaginate: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }
}
