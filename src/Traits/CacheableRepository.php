<?php

namespace Eilander\Repository\Traits;

use Eilander\Repository\Traits\Cacheable;

/**
 * Class CacheableRepository
 * @package Eilander\Repository\Traits
 */
trait CacheableRepository {
    use Cacheable;

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        if ( !$this->allowedCache('all') || $this->shouldCache() ){
            return parent::all($columns);
        }

        $key     = $this->getCacheKey('all', func_get_args());
        $minutes = $this->getCacheMinutes();
        $value   = $this->getCacheRepository()->remember($key, $minutes, function() use($columns) {
            return parent::all($columns);
        });

        return $value;
    }

    /**
     * Retrieve all data of repository, paginated
     * @param null $limit
     * @param array $columns
     * @return mixed
     */
    public function paginate($limit = null, $columns = array('*'))
    {
        if ( !$this->allowedCache('paginate') || $this->shouldCache() ){
            return parent::paginate($limit, $columns);
        }
        $key = $this->getCacheKey('paginate', func_get_args());
        $minutes = $this->getCacheMinutes();
        $value   = $this->getCacheRepository()->remember($key, $minutes, function() use($limit, $columns) {
            return parent::paginate($limit, $columns);
        });

        return $value;
    }

    /**
     * Find data by id
     *
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        if ( !$this->allowedCache('find') || $this->shouldCache() ){
            return parent::find($id, $columns);
        }

        $key     = $this->getCacheKey('find', func_get_args());
        $minutes = $this->getCacheMinutes();
        $value   = $this->getCacheRepository()->remember($key, $minutes, function() use($id, $columns) {
            return parent::find($id, $columns);
        });

        return $value;
    }

    /**
     * Find data by field and value
     *
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = array('*'))
    {
        if ( !$this->allowedCache('findByField') || $this->shouldCache() ){
            return parent::findByField($field, $value, $columns);
        }

        $key     = $this->getCacheKey('findByField', func_get_args());
        $minutes = $this->getCacheMinutes();
        $value   = $this->getCacheRepository()->remember($key, $minutes, function() use($field, $value, $columns) {
            return parent::findByField($field, $value, $columns);
        });

        return $value;
    }
}