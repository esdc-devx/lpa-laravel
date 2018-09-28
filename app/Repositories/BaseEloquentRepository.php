<?php

/**
 * Based on https://github.com/dannyweeks/laravel-base-repository
 * Author: Danny Weeks
 * Liscence: The MIT License (MIT)
 */

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseEloquentRepository implements RepositoryContract
{
    /**
     * Name of model associated with this repository
     * @var Model
     */
    protected $model;

    /**
     * Array of method names of relationships available to use
     * @var array
     */
    protected $relationships = [];

    /**
     * Array of relationships to include in next query
     * @var array
     */
    protected $requiredRelationships = [];

    /**
     * Array of traits being used by the repository.
     * @var array
     */
    protected $uses = [];
    protected $cacheTtl = 60;
    protected $caching = true;

    /**
     * Get the model from the IoC container
     */
    public function __construct()
    {
        $this->model = app()->make($this->model);
        $this->setUses();
    }

    /**
     * Get all items
     *
     * @param  string $columns specific columns to select
     * @param  string $orderBy column to sort by
     * @param  string $sort sort direction
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll($columns = null, $orderBy = 'id', $sort = 'asc')
    {
        $query = function () use ($columns, $orderBy, $sort) {
            return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($orderBy, $sort)
                ->get($columns);
        };
        return $this->doQuery($query);
    }

    /**
     * Get paged items
     *
     * @param  integer $paged Items per page
     * @param  string $orderBy Column to sort by
     * @param  string $sort Sort direction
     * @return \Illuminate\Pagination\Paginator
     */
    public function getPaginated($paged = 15, $orderBy = 'id', $sort = 'asc')
    {
        $query = function () use ($paged, $orderBy, $sort) {
            return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($orderBy, $sort)
                ->paginate($paged);
        };
        return $this->doQuery($query);
    }

    /**
     * Items for select options
     *
     * @param  string $data column to display in the option
     * @param  string $key column to be used as the value in option
     * @param  string $orderBy column to sort by
     * @param  string $sort sort direction
     * @return array  array with key value pairs
     */
    public function getForSelect($data, $key = 'id', $orderBy = 'created_at', $sort = 'desc')
    {
        $query = function () use ($data, $key, $orderBy, $sort) {
            return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($orderBy, $sort)
                ->lists($data, $key)
                ->all();
        };
        return $this->doQuery($query);
    }

    /**
     * Get item by its id
     *
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        $query = function () use ($id) {
            return $this->model
                ->with($this->requiredRelationships)
                ->findOrFail($id);
        };
        return $this->doQuery($query);
    }

    /**
     * Get instance of model by column
     *
     * @param  string $column column to search
     * @param  mixed $term search term
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getItemByColumn($column, $term)
    {
        $query = function () use ($term, $column) {
            return $this->model
                ->with($this->requiredRelationships)
                ->where($column, '=', $term)
                ->first();
        };
        return $this->doQuery($query);
    }

    /**
     * Get instance of model by column
     *
     * @param  string $column column to search
     * @param  mixed $term search term
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCollectionByColumn($column, $term)
    {
        $query = function () use ($term, $column) {
            return $this->model
                ->with($this->requiredRelationships)
                ->where($column, '=', $term)
                ->get();
        };
        return $this->doQuery($query);
    }

    /**
     * Create new using mass assignment
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a record using the primary key.
     *
     * @param $id mixed primary key
     * @param $data array
     */
    public function update($id, array $data)
    {
        return $this->model->where($this->model->getKeyName(), $id)->update($data);
    }

    /**
     * Update or crate a record and return the entity
     *
     * @param array $identifiers columns to search for
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate(array $identifiers, array $data)
    {
        $existing = $this->model->where(array_only($data, $identifiers))->first();
        if ($existing) {
            $existing->update($data);
            return $existing;
        }
        return $this->create($data);
    }

    /**
     * Delete a record by the primary key.
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->model->where($this->model->getKeyName(), $id)->delete();
    }

    /**
     * Force delete a record by the primary key.
     *
     * @param $id
     * @return bool
     */
    public function forceDelete($id)
    {
        return $this->model->where($this->model->getKeyName(), $id)->forceDelete();
    }

    /**
     * Choose what relationships to return with query.
     *
     * @param mixed $relationships
     * @return $this
     */
    public function with($relationships)
    {
        $this->requiredRelationships = [];
        if ($relationships == 'all') {
            $this->requiredRelationships = $this->relationships;
        } elseif (is_array($relationships)) {
            $this->requiredRelationships = array_filter($relationships, function ($value) {
                return in_array($value, $this->relationships);
            });
        } elseif (is_string($relationships)) {
            array_push($this->requiredRelationships, $relationships);
        }
        return $this;
    }

    /**
     * Perform the repository query.
     *
     * @param $callback
     * @return mixed
     */
    protected function doQuery($callback)
    {
        $previousMethod = debug_backtrace(null, 2)[1];
        $methodName = $previousMethod['function'];
        $arguments = $previousMethod['args'];
        $result = $this->doBeforeQuery($callback, $methodName, $arguments);
        return $this->doAfterQuery($result, $methodName, $arguments);
    }

    /**
     *  Apply any modifiers to the query.
     *
     * @param $callback
     * @param $methodName
     * @param $arguments
     * @return mixed
     */
    private function doBeforeQuery($callback, $methodName, $arguments)
    {
        $traits = $this->getUsedTraits();
        if (in_array(CachesResults::class, $traits) && $this->caching && $this->isCacheableMethod($methodName)) {
            return $this->processCacheRequest($callback, $methodName, $arguments);
        }
        return $callback();
    }

    /**
     * Handle the query result.
     *
     * @param $result
     * @param $methodName
     * @param $arguments
     * @return mixed
     */
    private function doAfterQuery($result, $methodName, $arguments)
    {
        $traits = $this->getUsedTraits();
        if (in_array(CachesResults::class, $traits)) {
            // Reset caching to enabled in case it has just been disabled.
            $this->caching = true;
        }
        return $result;
    }

    /**
     * @return int
     */
    protected function getCacheTtl()
    {
        return $this->cacheTtl;
    }

    /**
     * @return $this
     */
    protected function setUses()
    {
        $this->uses = array_flip(class_uses_recursive(get_class($this)));
        return $this;
    }

    /**
     * @return array
     */
    protected function getUsedTraits()
    {
        return $this->uses;
    }
}
