<?php

namespace Eilander\Repository\Eloquent;

use Eilander\Repository\Contracts\Eloquent as Repository;
use Eilander\Repository\Events\Eloquent\Created as EventCreated;
use Eilander\Repository\Events\Eloquent\Creating as EventCreating;
use Eilander\Repository\Events\Eloquent\Deleted as EventDeleted;
use Eilander\Repository\Events\Eloquent\Updated as EventUpdated;
use Eilander\Repository\RepositoryException;
use Eilander\Repository\Traits\Eloquent\Filterable as Filter;
use Event;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository.
 */
abstract class BaseRepository implements Repository
{
    use Filter;
    /**
     * @var Application
     */
    protected $app;
    /**
     * @var Model
     */
    protected $model;
    /**
     * @var array
     */
    protected $fieldSearchable = [];
    /**
     * @var array
     */
    protected $isUsedBy = [];
    /**
     * @var string
     */
    public $with = '';
    /**
     * Catch relations to fill presenter.
     */

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    abstract public function model();

    /**
     * @throws RepositoryException
     */
    protected function resetModel()
    {
        $this->makeModel();
    }

    /**
     * Get Searchable Fields.
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Get Repository classes.
     *
     * @return array
     */
    public function isUsedBy()
    {
        return $this->isUsedBy;
    }

    /**
     * @throws RepositoryException
     *
     * @return Model
     */
    protected function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        // parse url filters on the model
        return $this->model = $this->filter($model, $this->getFieldsSearchable());
    }

    /**
     * Wrapper result data.
     *
     * @param mixed $result
     *
     * @return mixed
     */
    protected function parserResult($result)
    {
        return $result;
    }

    /**
     * Retrieve all data of repository.
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        if ($this->model instanceof \Illuminate\Database\Eloquent\Builder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Retrieve all data of repository, paginated.
     *
     * @param null  $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'])
    {
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->select($columns)->paginate($limit);
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Find data by id.
     *
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        $model = $this->model->findOrFail($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by field and value.
     *
     * @param $field
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        $model = $this->model->where($field, '=', $value)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Save a new entity in repository.
     *
     * @param array $attributes
     *
     * @throws ValidatorException
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        // Create slug before save
        Event::dispatch(new EventCreating($this, $model));
        $model->save();
        $this->resetModel();
        // Clear cache on save
        Event::dispatch(new EventCreated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Update a entity in repository by id.
     *
     * @param array $attributes
     * @param $id
     *
     * @throws ValidatorException
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->save();
        $this->resetModel();
        // Clear cache on save
        Event::dispatch(new EventUpdated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Delete a entity in repository by id.
     *
     * @param $id
     *
     * @return int
     */
    public function delete($id)
    {
        $model = $this->find($id);
        $deleted = $model->delete();

        $this->resetModel();
        // Clear cache on delete
        Event::dispatch(new EventDeleted($this, $model));

        return $deleted;
    }

    /**
     * Load relations.
     *
     * @param array|string $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        $this->with = $relations;
        $this->model = $this->model->with($this->with);

        return $this;
    }

    /**
     * Load relations.
     *
     * @param array|string $relations
     *
     * @return $this
     */
    public function where(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }

        return $this;
    }

    public function __call($method, $args)
    {
        try {
            return call_user_func_array([$this->model, $method], $args);
        } catch (Exception $e) {
            throw new RepositoryException('Method '.$method.' does not exist.');
        }
    }
}
