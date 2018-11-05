<?php

namespace App\Repositories;

interface RepositoryContract
{
    /**
     * Get all items
     *
     * @param  string $columns specific columns to select
     * @param  string $orderBy column to sort by
     * @param  string $sort sort direction
     */
    public function getAll($columns = null, $orderBy = 'created_at', $sort = 'desc');

    /**
     * Get paged items
     *
     * @param  integer $paged Items per page
     * @param  string $orderBy Column to sort by
     * @param  string $sort Sort direction
     */
    public function getPaginated($paged = 15, $orderBy = 'created_at', $sort = 'desc');

    /**
     * Items for select options
     *
     * @param  string $data column to display in the option
     * @param  string $key column to be used as the value in option
     * @param  string $orderBy column to sort by
     * @param  string $sort sort direction
     * @return array array with key value pairs
     */
    public function getForSelect($data, $key = 'id', $orderBy = 'created_at', $sort = 'desc');

    /**
     * Get item by its id
     *
     * @param  mixed $id
     */
    public function getById($id);

    /**
     * Get instance of model by column
     *
     * @param  string $column column to search
     * @param  mixed $term search term
     */
    public function getItemByColumn($column, $term);

    /**
     * Get instance of model by column
     *
     * @param  string $column column to search
     * @param  mixed $term search term
     */
    public function getCollectionByColumn($column, $term);

    /**
     * Create new using mass assignment
     *
     * @param array $data
     */
    public function create(array $data);

    /**
     * Update or crate a record and return the entity
     *
     * @param array $identifiers columns to search for
     * @param array $data
     */
    public function updateOrCreate(array $identifiers, array $data);

    /**
     * Delete a record by it's ID.
     *
     * @param $id
     * @return bool
     */
    public function delete($id);

}
