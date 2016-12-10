<?php

namespace Eilander\Repository\Contracts;

/**
 * Interface Search.
 */
interface Eloquent extends Repository
{
    /**
     * Retrieve all data of repository.
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all();

    /**
     * Retrieve all data of repository, paginated.
     *
     * @param null  $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($limit = null);

    /**
     * Find data by id.
     *
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id);

    /**
     * Find data by field and value.
     *
     * @param $field
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value);

    /**
     * Find data by multiple fields.
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update a entity in repository by id.
     *
     * @param array $attributes
     * @param $id
     *
     * @return mixed
     */
    public function update(array $attributes, $id);

    /**
     * Delete a entity in repository by id.
     *
     * @param $id
     *
     * @return int
     */
    public function delete($id);
}
