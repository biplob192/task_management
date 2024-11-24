<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\DataTransferObjects\PaginateData;
use App\Interfaces\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    protected $modelName;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->modelName = class_basename($model);
    }


    protected function getModelName($capitalize = false, $plural = false)
    {
        $modelName = $this->modelName;

        // Capitalize the model name if requested
        if (!$capitalize) {
            $modelName = strtolower($modelName);
        }

        // Pluralize the model name if requested
        if ($plural) {
            $modelName = Str::plural($modelName);
        }

        return $modelName;
    }


    public function index()
    {
        try {
            // Find the items or throw exception if found nothing
            $items = $this->model->all();

            if (!$items->isEmpty()) {
                return ['data' => $items, 'message' => "{$this->getModelName(true, true)} retrieved successfully."];
            } else {
                throw new Exception("No records found", 404);
            }
        } catch (Exception $e) {
            throw new Exception("Error fetching all {$this->getModelName(false, true)}: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function show($id)
    {
        try {
            // Find the item or throw exception if found nothing
            $item = $this->model->find($id);

            if (!$item) {
                throw new Exception("No record found", 404);
            }

            return ['data' => $item, 'message' => "{$this->getModelName(true)} retrieved successfully."];
        } catch (Exception $e) {
            throw new Exception("Error fetching {$this->getModelName()} with ID $id: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function store(array $data, $request)
    {
        try {
            // Collect validated data
            $validatedData = $data;

            // Create new record with the validated data
            $item = $this->model->create($validatedData);

            return ['data' => $item, 'message' => "{$this->getModelName(true)} created successfully.", 'status' => 201];
        } catch (Exception $e) {
            throw new Exception("Error creating {$this->getModelName()}: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function update($id, array $data, $request)
    {
        try {
            // Collect validated data
            $validatedData = $data;

            // Find the item or throw exception if found nothing
            $item = $this->model->find($id);

            if (!$item) {
                throw new Exception("{$this->getModelName(false, true)} not found", 404);
            }

            // Update item with the validated data
            $item->update($validatedData);

            return ['data' => $item, 'message' => "{$this->getModelName(true)} updated successfully."];
        } catch (Exception $e) {
            throw new Exception("Error updating {$this->getModelName()} with ID $id: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function delete($id)
    {
        try {
            // Find the item or throw exception if found nothing
            $item = $this->model->find($id);

            if (!$item) {
                throw new Exception("{$this->getModelName(true)} not found", 404);
            }

            // Delete the item
            $item = $this->model->destroy($id);

            return ['data' => $item, 'message' => "{$this->getModelName(true)} deleted successfully."];
        } catch (Exception $e) {
            throw new Exception("Error deleting {$this->getModelName()} with ID $id: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function indexWithPaginate(PaginateData $formatedData)
    {

        try {
            // Start a query builder instance on the model
            $query = $this->model->newQuery();

            // Apply conditions dynamically
            foreach ($formatedData->conditions as $condition) {
                if (isset($condition['value'])) {
                    $query->where($condition['column'], $condition['operator'], $condition['value']);
                }
            }

            // Apply custom conditions (e.g., greater than, not equal)
            foreach ($formatedData->customConditions as $condition) {
                if (!empty($condition['value'])) {
                    $query->where($condition['column'], $condition['operator'], $condition['value']);
                }
            }

            // Apply special search columns if any are provided
            foreach ($formatedData->specialSearch as $column => $value) {
                if (!empty($value)) {
                    $query->where($column, $value);
                    // $query->where($column, 'LIKE', "%{$value}%");
                }
            }

            // Apply general search if a search term is provided
            if (!empty($formatedData->search) && !empty($formatedData->searchableColumns)) {
                $query->where(function ($q) use ($formatedData) {
                    foreach ($formatedData->searchableColumns as $column) {
                        $q->orWhere($column, 'LIKE', "%{$formatedData->search}%");
                    }
                });
            }

            // Get selected data for Server Side pagination
            $perPage = $formatedData->perPage;
            if ($perPage != -1 && is_numeric($perPage)) {
                $offset = ($formatedData->page - 1) * $perPage;
                $query->offset($offset)->limit($perPage);
            }

            // Set paginate number same as total records (when user will select 'All' from paginate)
            // $totalRecords = count($query->get());
            // $paginateNumber = $perPage != -1 ? $perPage : $totalRecords;

            // Set maximum paginate number 150 (when user will select 'All' from paginate)
            $paginateNumber = $perPage != -1 ? $perPage : 150;

            // Get paginated results with ordered by
            $items = $query->orderBy($formatedData->sortKey, $formatedData->sortOrder)->paginate($paginateNumber);

            return ['data' => $items, 'message' => "{$this->getModelName(true, true)} retrieved successfully."];
        } catch (Exception $e) {
            throw new Exception("Error fetching all {$this->getModelName(false, true)}: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }
}
